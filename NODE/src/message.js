const Buffer = require('buffer').Buffer;
const torrentParser = require('./torrent-parser');
const util = require('./util');

module.exports.buildHandshake = (torrent) => {
    //console.log('building handshake');

    var buf = Buffer.alloc(68);

    //handshake: <pstrlen><pstr><reserved><info_hash><peer_id>
    
    // pstrlen: string length of <pstr>, as a single raw byte
    // pstr: string identifier of the protocol
    // reserved: eight (8) reserved bytes. All current implementations use all zeroes.
    // peer_id: 20-byte string used as a unique ID for the client.

    // In version 1.0 of the BitTorrent protocol, pstrlen = 19, and pstr = "BitTorrent protocol".
    
    // pstrlen
    buf.writeUInt8(19, 0);
   
    // pstr
    buf.write('BitTorrent protocol', 1);
   
    // reserved
    buf.writeUInt32BE(0, 20);
    buf.writeUInt32BE(0, 24);
   
    // info hash
    torrentParser.infoHash(torrent).copy(buf, 28);
   
    // peer id
    util.genId().copy(buf, 48);
    return buf;
}

module.exports.buildKeepAlive = () => {
    Buffer.alloc(4);
}

module.exports.buildChoke = () => {
    var buf = Buffer.alloc(5);
    
    // length
    buf.writeUInt32BE(1, 0);
    
    // id
    buf.writeUInt8(0, 4);
    return buf;
};

module.exports.buildUnchoke = () => {
    var buf = Buffer.alloc(5);
    
    // length
    buf.writeUInt32BE(1, 0);
    
    // id
    buf.writeUInt8(1, 4);
    return buf;
};
  
module.exports.buildInterested = () => {
    //console.log('building Interested');

    var buf = Buffer.alloc(5);
    
    // length
    buf.writeUInt32BE(1, 0);
    
    // id
    buf.writeUInt8(2, 4);
    return buf;
};
  
module.exports.buildUninterested = () => {
    //console.log('building Uninterested');

    var buf = Buffer.alloc(5);
    
    // length
    buf.writeUInt32BE(1, 0);
    
    // id
    buf.writeUInt8(3, 4);
    return buf;
};
  
module.exports.buildHave = (payload) => {
    //console.log('building have');

    var buf = Buffer.alloc(9);
    
    // length
    buf.writeUInt32BE(5, 0);
   
    // id
    buf.writeUInt8(4, 4);
    
    // piece index
    buf.writeUInt32BE(payload, 5);
    return buf;
};
  
module.exports.buildBitfield = bitfield => {
    var buf = Buffer.alloc(14);
    
    // length
    buf.writeUInt32BE(payload.length + 1, 0);
    
    // id
    buf.writeUInt8(5, 4);
    
    // bitfield
    bitfield.copy(buf, 5);
    return buf;
};
  
module.exports.buildRequest = (payload) => {
    //console.log('building Request');

    var buf = Buffer.alloc(17);
    
    // length
    buf.writeUInt32BE(13, 0);
    
    // id
    buf.writeUInt8(6, 4);
    
    // piece index
    buf.writeUInt32BE(payload.index, 5);
    
    // begin
    buf.writeUInt32BE(payload.begin, 9);
    
    // length
    buf.writeUInt32BE(payload.length, 13);
    return buf;
};
  
module.exports.buildPiece = (payload) => {
    var buf = Buffer.alloc(payload.block.length + 13);
    
    // length
    buf.writeUInt32BE(payload.block.length + 9, 0);
    
    // id
    buf.writeUInt8(7, 4);
    
    // piece index
    buf.writeUInt32BE(payload.index, 5);
    
    // begin
    buf.writeUInt32BE(payload.begin, 9);
    
    // block
    payload.block.copy(buf, 13);
    return buf;
};
  
module.exports.buildCancel = (payload) => {
    var buf = Buffer.alloc(17);
    
    // length
    buf.writeUInt32BE(13, 0);
    
    // id
    buf.writeUInt8(8, 4);
    
    // piece index
    buf.writeUInt32BE(payload.index, 5);
    
    // begin
    buf.writeUInt32BE(payload.begin, 9);
    
    // length
    buf.writeUInt32BE(payload.length, 13);
    return buf;
};
  
module.exports.buildPort = (payload) => {
    var buf = Buffer.alloc(7);
    
    // length
    buf.writeUInt32BE(3, 0);
    
    // id
    buf.writeUInt8(9, 4);
    
    // listen-port
    buf.writeUInt16BE(payload, 5);
    return buf;
};

module.exports.parse = (msg) => {
    var id = msg.length > 4 ? msg.readInt8(4) : null;
    var payload = msg.length > 5 ? msg.slice(5) : null;
    
    if(id === 6 || id === 7 || id === 8) {
        var rest = payload.slice(8);
        payload = {
            index: payload.readInt32BE(0),
            begin: payload.readInt32BE(4)
        };
        payload[id === 7 ? 'block' : 'length'] = rest;
    }

    return {
        size: msg.readInt32BE(0),
        id: id,
        payload: payload
    }
};
