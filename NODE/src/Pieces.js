
const torrentParser = require('./torrent-parser');
const emitter = require('./emitter');

class Pieces {
    constructor(torrent) {
        function buildPiecesArray() {
          const nPieces = torrent.info.pieces.length / 20;
          const arr = new Array(nPieces).fill(null);
          return arr.map((_, i) => new Array(torrentParser.blocksPerPiece(torrent, i)).fill(false));
        };
        
        this._requested = buildPiecesArray();
        this._received = buildPiecesArray();
        this.runOnce = 0;
    }

	addRequested(pieceBlock) {
        var blockIndex = pieceBlock.begin / torrentParser.BLOCK_LEN;
        this._requested[pieceBlock.index][blockIndex] = true;
    }
    
    addReceived(pieceBlock) {
        var blockIndex = pieceBlock.begin / torrentParser.BLOCK_LEN;
        this._received[pieceBlock.index][blockIndex] = true;
    }
 	
	needed(pieceBlock) {
        if (this._requested.every(blocks => blocks.every(i => i))) {
            this._requested = this._received.map(blocks =>blocks.slice());
        }

        var blockIndex = pieceBlock.begin / torrentParser.BLOCK_LEN;
        return !this._requested[pieceBlock.index][blockIndex];
    }

    isDone() {
        return this._received.every(blocks => blocks.every(i => i));
    }

    printPercentDone() {
        var downloaded = this._received.reduce((totalBlocks, blocks) => {
            return blocks.filter(i => i).length + totalBlocks;
        }, 0);

        var total = this._received.reduce((totalBlocks, blocks) => {
            return blocks.length + totalBlocks;
        }, 0);
        
        var percent = Math.floor(downloaded/total * 100);

        if (percent == 2 && this.runOnce == 0)
        {
            emitter.emit('ready', percent);
            this.runOnce = 1;
        }

        process.stdout.write('progress: ' + percent + '%\r');
    }
}

module.exports = Pieces;