const fs = require('fs');
const bencode = require('bencode');
const torrentParser = require('parse-torrent');
const crypto = require('crypto');
const Buffer = require('buffer').Buffer;
const Uint64BE = require('int64-buffer').Uint64BE;
const BN = require('bn.js');
const downloadFile = require('download');

module.exports.openBencode = (filepath) => {
    return bencode.decode(fs.readFileSync(filepath));
}

module.exports.openTParser = (filepath) => {
    if (filepath.indexOf('magnet') != -1)
    {
        return torrentParser(filepath);
    }
   
    return torrentParser(fs.readFileSync(filepath));
}

module.exports.infoHash = (torrent) => {
    //return : buffer of 20 bytes -> <Buffer 11 7e 3a 66 65 e8 ff 1b 15 7e 5e c3 78 23 57 8a db 8a 71 2b>
    const info = bencode.encode(torrent.info);
    return crypto.createHash('sha1').update(info).digest();
};

module.exports.infoHashTparser = (torrentfile) => {
    //return : buffer of 20 bytes -> <Buffer 11 7e 3a 66 65 e8 ff 1b 15 7e 5e c3 78 23 57 8a db 8a 71 2b>
    const torrent = torrentParser(torrentfile);
    return torrent.infoHash;
};

module.exports.BLOCK_LEN = Math.pow(2, 14);

module.exports.size = (torrent) => {
    var size = torrent.info.files ? torrent.info.files.map(file => file.length).reduce((a, b) => a + b) : torrent.info.length;

    // var bignum = new Uint64BE(size);
    // return bignum.toBuffer();

    var bignum = new BN(size, 10);
    return(bignum.toBuffer(1, 8));

    //return bignum.toBuffer(size, {size: 8});
};

module.exports.pieceLen = (torrent, pieceIndex) => {
    var totalLength = new Uint64BE(this.size(torrent)).toNumber();
    //var totalLength = bignum.fromBuffer(this.size(torrent)).toNumber();
    var pieceLength = torrent.info['piece length'];

    var lastPieceLength = totalLength % pieceLength;
    var lastPieceIndex = Math.floor(totalLength / pieceLength);

    return lastPieceIndex === pieceIndex ? lastPieceLength : pieceLength;
};

module.exports.blockLen = (torrent, pieceIndex, blockIndex) => {
    var pieceLength = this.pieceLen(torrent, pieceIndex);

    var lastPieceLength = pieceLength % this.BLOCK_LEN;
    var lastPieceIndex = Math.floor(pieceLength / this.BLOCK_LEN);

    return blockIndex === lastPieceIndex ? lastPieceLength : this.BLOCK_LEN;
}

module.exports.blocksPerPiece = (torrent, pieceIndex) => {
    var pieceLength = this.pieceLen(torrent, pieceIndex);
    return Math.ceil(pieceLength / this.BLOCK_LEN);
}

