var torrentParser = require('./torrent-parser');

class Queue {
    constructor(torrent) {
        this._torrent = torrent;
        this._queue = [];
        this.choked = true;
    }

    queue(pieceIndex) {
        var nBlocks = torrentParser.blocksPerPiece(this._torrent, pieceIndex);
        for (var i = 0; i < nBlocks; i++) {
            var pieceBlock = {
                index: pieceIndex,
                begin: i * torrentParser.BLOCK_LEN,
                length: torrentParser.blockLen(this._torrent, pieceIndex, i)
            };
            this._queue.push(pieceBlock);
        }
    }

    deque() { return this._queue.shift(); }

    peek() { return this._queue[0]; }

    length() { return this._queue.length; }
};

module.exports = Queue;