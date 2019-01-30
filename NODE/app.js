const process = require('./src/search');
const express = require('express');
const emitter = require('./src/emitter');
const path = require('path');
const query = require('yify-search');
const torrentParse = require('./src/torrent-parser');
const torrentSearch = require('torrent-search-api');
const https = require('https');
const downloadFile = require('download');
const fetch = require('node-fetch');
const fs = require('fs');
const cloudscrapper = require('cloudscraper');
const util = require('util');
const ts = require('tail-stream');
const growingFile = require('growing-file');
const Transcoder = require('stream-transcoder');
const find = require('find');
const parseRange = require('range-parser');
var cors =  require('cors');

var app = express();
var check = 0;
var movieDownloaded = null;

app.use(cors());
app.use(express.static(path.join(__dirname, 'views')));
app.use('/public', express.static(path.join(__dirname, 'public')));

app.get('/startDownload/:movieName', (req, res) => {
	if (req.params.movieName)
	{
		emitter.on('failedYTS', function(){
			process.startDownloadMulti(req.params.movieName, __dirname , function(name) {
				if (name == 'failed')
				{
					movieDownloaded = name;
					console.log("Download Status: " + name);
					emitter.emit('downloadFailed');
				}
				else
				{
					movieDownloaded = name;
					console.log("Download Status: started...");
					res.send('Pending');
				}
			});
		})
		
		console.log("Starting Download: " + req.params.movieName);
		process.startDownloadYTS(req.params.movieName, __dirname , function(name) {
			if (name == 'failed')
			{
				emitter.emit('failedYTS');
				res.send('Pending');
			}
			else
			{
				movieDownloaded = name;
				console.log("Download Status: started...");
				res.send('Pending');
			}
		})

	}
})


app.get('/checkStatus', (req, res) => {
	if (check >= 1) {
		res.send(movieDownloaded);
	}
	else {
		res.send('Pending');
	}
})

app.get('/', (req, res) => {
	res.sendFile(path.join(__dirname, 'views', 'index.html'));
})

// app.get('/startStream/:movie', (req, res) => {
// 	if (req.params.movie);
// 	{
// 		var directories = find.dirSync(path.join(__dirname, 'downloads'));
// 		var movieToStream = (req.params.movie != null) ? req.params.movie :  movieDownloaded;

// 		for(var i = 0; i < directories.length; i++)
// 		{
// 			if (fs.existsSync(path.join(directories[i], movieToStream)))
// 			{
// 				var filepath = path.join(directories[i], movieToStream);
// 				break ;
// 			}
// 		}

// 		check = 0;
// 		movieDownloaded = ""
// 		console.log(filepath);

// 		app.get('/video', function(req, res){
// 			var stat = fs.statSync(filepath);
// 			var fileSize = stat.size;
// 			var range = req.headers.range;

// 			if(range)
// 			{
// 				var parts = range.replace('/bytes=/', "").split("-");
// 				var start = 0;
// 				var end = parts[1] ? parseInt(parts[1], 10) : fileSize - 1;
				
// 				var chunksize = (end - start) + 1;
// 				var file = fs.createReadStream(filepath);
				
// 				var head = {
// 					'Content-Range' : `bytes ${start}-${end}/${fileSize}`,
// 					'Accept_Ranges' : 'bytes',
// 					'Content-Length' : chunksize,
// 					'Content-Type': 'video/mp4'
// 				}

// 				res.writeHead(206, head);

// 				file.pipe(res);
// 			}
// 			else
// 			{
// 				var head = {
// 					'Content-Length': fileSize,
// 					'Content-Type': 'video/mp4'
// 				}

// 				res.writeHead(200, head);
// 				fs.createReadStream(filepath).pipe(res);
// 			}
// 		});
// 	}

// 	setTimeout(function(){
// 		res.redirect('/watchVideo');
// 	}, 3000)
// })

app.get('/startStream/:movie', (req, res) => {
	if (req.params.movie);
	{
		var directories = find.dirSync(path.join(__dirname, 'downloads'));
		var movieToStream = (req.params.movie != null) ? req.params.movie :  movieDownloaded;

		for(var i = 0; i < directories.length; i++)
		{
			if (fs.existsSync(path.join(directories[i], movieToStream)))
			{
				var filepath = path.join(directories[i], movieToStream);
				break ;
			}
		}

		check = 0;
		movieDownloaded = ""
		console.log(filepath);

		// res.setHeader('Accept-Ranges', 'bytes');
		// getTorrentFile.then(function (file) {
		// res.setHeader('Content-Length', file.length);
		// res.setHeader('Content-Type', `video/${file.ext}`);
		// const ranges = parseRange(file.length, req.headers.range, { combine: true });
		// 	if (ranges === -1) {
		// 		// 416 Requested Range Not Satisfiable
		// 		res.statusCode = 416;
		// 		return res.end();
		// 	}
		// 	else if (ranges === -2 || ranges.type !== 'bytes' || ranges.length > 1) {
		// 		// 200 OK requested range malformed or multiple ranges requested, stream entire video
		// 		if (req.method !== 'GET') return res.end();
		// 		return file.createReadStream().pipe(res);
		// 	}
		// 	else {
		// 		// 206 Partial Content valid range requested
		// 		const range = ranges[0];
		// 		res.statusCode = 206;
		// 		res.setHeader('Content-Length', 1 + range.end - range.start);
		// 		res.setHeader('Content-Range', `bytes ${range.start}-${range.end}/${file.length}`);
		// 		if (req.method !== 'GET') return res.end();
		// 		return file.createReadStream(range).pipe(res);
		// 	}
		// }).catch(function (e) {
		// 	console.error(e);
		// 	res.end(e);
		// });

		app.get('/video', function(req, res){
			res.setHeader('Accept-Ranges', 'bytes');
			var stat = fs.statSync(filepath);
			var file = fs.createReadStream(filepath);
 			var fileSize = stat.size;
			console.log(fileSize);
			
			res.setHeader('Content-Length', fileSize);
			res.setHeader('Content-Type', `video/${movieToStream.slice(-3)}`);
			
			var ranges = parseRange(fileSize, req.headers.range, { combine: true });
			if (ranges === -1) {
				res.statusCode = 416;
				return res.end();
			}
			else if (ranges === -2 || ranges.type !== 'bytes' || ranges.length > 1) {
				if (req.method !== 'GET')
					return res.end();
				return file.pipe(res);
			}
			else {
				var range = ranges[0];
				res.statusCode = 206;
				res.setHeader('Content-Length', 1 + range.end - range.start);
				res.setHeader('Content-Range', `bytes ${range.start}-${range.end}/${fileSize}`);
				if (req.method !== 'GET')
					return res.end();
				var file = fs.createReadStream(range)
				return file.pipe(res);
			}
		});
	}

	setTimeout(function(){
		res.redirect('/watchVideo');
	}, 3000)
})

app.get('/watchVideo', function(req, res){
	res.sendFile(path.join(__dirname, 'views', 'watchStream.html'));
});

emitter.on('ready', function(percent) {
	if (percent)
		check = percent;
})

emitter.on('downloadFailed', function() {
	check = 1;
})

app.listen(3000, () => {
	console.log('Listening on Port 3000');
})
