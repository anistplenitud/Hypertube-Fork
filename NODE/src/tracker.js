const magnetLink = require('magnet-link');
const { discover } = require('scrape-torrent-stats')
const util = require('util');
const emitter = require('./emitter');

var getMagnet = util.promisify(magnetLink);

async function getPeers(linkorfile) {
	if (linkorfile.indexOf('magnet') == -1)
	{
		var peerlist = await getMagnet(linkorfile)
			.then((link) => {
				const config = {
					source: 'dht',
					waitTime: 10000, // 10 second wait before closing peer search
					verbose: true
				};

				var Peers = discover(link, config)
					.then((result) => {
						var peers = []
						var peerData = result.peersObj
						for (let peer in peerData)
						{
							peers.push(peerData[peer]);
						};
						return (peers)
					})
					.then((peers) => {
						return peers;
					})
					.catch((err) => {
						console.error(err)
					})

				return Peers;
			})
			.then((peers) => {
				if (peers.length > 10)
					return peers;
				else
				{
					emitter.emit('failedYTS');
					console.log("NO PEERS!");
				}
				return peers;
			})
			.catch((err) => {
				console.log(err);
			})
		
			return peerlist;
	}
	else {
		const config = {
			source: 'dht',
			waitTime: 10000, // 10 second wait before closing peer search
			verbose: true
		};

		var Peerlist = await discover(linkorfile, config)
			.then((result) => {
				var peers = []
				var peerData = result.peersObj
				for (let peer in peerData)
				{
					peers.push(peerData[peer]);
				};
				return (peers)
			})
			.then((peers) => {
				return peers;
			})
			.catch((err) => {
				console.error(err)
			})
		
		return Peerlist;
	}
};

module.exports.getPeers = getPeers;