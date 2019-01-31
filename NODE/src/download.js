const net = require('net');
const Buffer = require('buffer').Buffer;
const tracker = require('./tracker');
const torrentParse = require('./torrent-parser');
const message = require('./message');
const Pieces = require('./Pieces');
const Queue = require('./Queue');
const fs = require('fs');
const path = require('path');
const mkdirp = require('mkdirp');
const util = require('util');

module.exports = (torrentfile, destPath) => {
	var torrent = torrentParse.openBencode(torrentfile);
	var torrentInfo = torrentParse.openTParser(torrentfile);
	var fileDescriptors = [];
	var queue = new Queue(torrent); 
   
	torrentInfo.files.forEach(function(file){
		if (!fs.existsSync(path.join(destPath, path.dirname(file.path))))
			mkdirp.sync(path.join(destPath, path.dirname(file.path)));
	});

	torrentInfo.files.forEach(function(file){
		fileDescriptors.push(fs.openSync(path.join(destPath, file.path), 'w'));
	});

	queuePrioritySort(queue, torrentInfo);

	tracker.getPeers(torrentfile)
		.then((peers) => {
			var pieces = new Pieces(torrent);
			
			peers.forEach((peer) => {
				download(peer, torrent, pieces, fileDescriptors, torrentInfo, queue);
			});
		})
		.catch((err) => {
			console.log(err);
		})
}

function queuePrioritySort(queue, torrentInfo) {
    var files = [];
    var queuedPieces = [];

	torrentInfo.files.forEach(function(file) {
		files.push(file);
    });
    
    files.sort((a, b) => a.length - b.length);

	for (var fileIndex = 0; fileIndex < files.length; fileIndex++) {
        //Begin and End piece that make up the File.
        var beginPiece = Math.floor(files[fileIndex].offset / torrentInfo.pieceLength);
        var endPiece = Math.ceil((files[fileIndex].length + files[fileIndex].offset) / torrentInfo.pieceLength);
        
        //The video being the Biggest file in the list, will be sorted to the last position.
        if (fileIndex == files.length - 1) {
            //The Beginning of The video file.
            if (!queuedPieces.includes(beginPiece))
                queue.queue(beginPiece);

            //The Ending of The video file.
            if (!queuedPieces.includes(endPiece - 1))
                queue.queue(endPiece);

            break;
        }
        else {
            for(var pieceIndex = beginPiece; pieceIndex < endPiece; pieceIndex++) {
                if (!queuedPieces.includes(pieceIndex)) {
                    queue.queue(pieceIndex);
                    queuedPieces.push(pieceIndex);
                }
            }
        }
    }	
}

function download(peer, torrent, pieces, fileDescriptors, torrentInfo, queue) {
	const socket = new net.Socket();
	
	socket.connect(peer.port, peer.host, (res) => {
		socket.write(message.buildHandshake(torrent))
	});

	socket.on('error', (error) => {});

	onWholeMsg(socket, (msg) => msgHandler(msg, socket, pieces, queue, torrent, fileDescriptors, torrentInfo));
}

function onWholeMsg(socket, callback) {
	var savedBuf = Buffer.alloc(0);
	var handshake = true;

	socket.on('data', (receivedBuf) => {
		//msglen is used to calculate the length of the whole message
		var msgLen = () => handshake ? savedBuf.readUInt8(0) + 49 : savedBuf.readInt32BE(0) + 4;

		savedBuf = Buffer.concat([savedBuf, receivedBuf]);

		while (savedBuf.length >= 4 && savedBuf.length >= msgLen()) {
			callback(savedBuf.slice(0, msgLen()));
			savedBuf = savedBuf.slice(msgLen());
			handshake = false;
		}
	});
}

function msgHandler(msg, socket, pieces, queue, torrent, fileDescriptors, torrentInfo) {
	if (isHandshake(msg)) {
		//console.log('handshake succeeded!');
		socket.write(message.buildInterested());
	}
	else {
		var m = message.parse(msg);

		if (m.id === 0) chokeHandler(socket);
		if (m.id === 1) unchokeHandler(socket, pieces, queue);
		if (m.id === 4) haveHandler(socket, pieces, queue, m.payload);
		if (m.id === 5) bitfieldHandler(socket, pieces, queue, m.payload);
		if (m.id === 7) fileHandler(socket, pieces, queue, torrent, fileDescriptors, m.payload, torrentInfo);
	}
}

function isHandshake(msg) {
	return  msg.length === msg.readUInt8(0) + 49 &&
			msg.toString('utf8', 1, 20) === 'BitTorrent protocol';
}

//------------------------------------------------------------------------------------------
//HANDLERS
//------------------------------------------------------------------------------------------

function chokeHandler(socket) {
	socket.end();
}

function unchokeHandler(socket, pieces, queue) {
	queue.choked = false;

	requestPiece(socket, pieces, queue);
}

function haveHandler(socket, pieces, queue, payload) {
	var pieceIndex = payload.readUInt32BE(0);
	var queueEmpty = queue.length === 0;

	queue.queue(pieceIndex);
	if (queueEmpty) requestPiece(socket, pieces, queue);
}

function bitfieldHandler(socket, pieces, queue, payload) {
	var queueEmpty = queue.length === 0;
	payload.forEach((byte, i) => {
		for (var j = 0; j < 8; j++) {
			if (byte % 2) queue.queue(i * 8 + 7 - j);
			byte = Math.floor(byte / 2);
		}
	});
	if (queueEmpty) requestPiece(socket, pieces, queue);
}

function fileHandler(socket, pieces, queue, torrent, fileDescriptors, pieceResp, torrentInfo) {
	pieces.printPercentDone();

	pieces.addReceived(pieceResp);

	for(var index = 0; index < fileDescriptors.length; index++) {
		pieceHandler(torrent, fileDescriptors[index], pieceResp, index, torrentInfo);
	}

	if (pieces.isDone()) {
		socket.end();
		console.log('Done!');
		fileDescriptors.forEach(function(file) {
			try { 
				fs.closeSync(file); 
			} catch(e) {
				console.log(e); 
			}
		});
	} else {
		requestPiece(socket, pieces, queue);
	}
}

function pieceHandler(torrent, fileDescriptor, pieceResp, fileIndex, torrentInfo) {

	var offset = (pieceResp.index * torrent.info['piece length']) + pieceResp.begin;
	var file = torrentInfo.files[fileIndex];
	var blockLength = pieceResp.block.length;

	var diff = offset - file.offset;

	//Write to File
	if ((diff <= 0 && -diff < blockLength) || (diff > 0 && diff < file.length))
	{
		var startWrite = Math.max(offset - file.offset, 0);
		var startPull = Math.max(file.offset - offset, 0);
		var length = Math.min(file.length - startWrite, blockLength - startPull);
		fs.write(fileDescriptor, pieceResp.block, startPull, length, startWrite, () => {});
	}
}

//------------------------------------------------------------------------------------------
//END OF HANDLERS
//------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------
//REQUEST PIECES
//------------------------------------------------------------------------------------------

function requestPiece(socket, pieces, queue) {
	if (queue.choked) return null;

	while (queue.length()) {
		var pieceBlock = queue.deque();
		if(pieces.needed(pieceBlock)) {
			socket.write(message.buildRequest(pieceBlock));
			pieces.addRequested(pieceBlock);
			break;
		}
	}
}



