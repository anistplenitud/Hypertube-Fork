//const tracker = require('./tracker');
const search = require('./src/search');
const download = require('./src/download');
const path = require('path');
const Client = require('bittorrent-tracker');
const Buffer = require('buffer').Buffer;

const filepath = path.join(__dirname, 'torrents', 'sintel_blender.torrent');

download(filepath, path.join(__dirname, 'downloads'));

//console.log(torrent);

// const options = {
// 	infoHash: Buffer.from(torrent.infoHash, "hex"),
// 	peerId: Buffer.from('-GP0001-000000000000'),
// 	announce: [torrent.announce[2]],
// 	port: 6881
// }

// var client = new Client(options)
	
// client.on('error', function (err) {
// 	// fatal client error!
// 	console.log(err.message)
// })
	
// client.on('warning', function (err) {
// 	// a tracker was unavailable or sent bad data to the client. you can probably ignore it
// 	console.log(err.message)
// })

// var peers = [];

// // start getting peers from the tracker
// client.start()
	
// client.on('update', function (data) {
// 	console.log('got an announce response from tracker: ' + data.announce)
// 	console.log('number of seeders in the swarm: ' + data.complete)
// 	console.log('number of leechers in the swarm: ' + data.incomplete)
// })
	
// client.once('peer', function (addr) {
// 	peers.push(addr);
// 	console.log('found a peer: ' + addr) // 85.10.239.191:48623
// })

// client.update()