const process = require('./src/search');
const express = require('express');
const emitter = require('./src/emitter');
const path = require('path');
const query = require('yify-search');
const torrentParse = require('./src/torrent-parser');
const torrentSearch = require('torrent-search-api');
const http = require('http');
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
const stream = require('./src/stream');

var cors = require('cors');

var app = express();
var check = 0;
var movieDownloaded = null;

app.use(cors());

app.use(express.static(path.join(__dirname, 'views')));
app.use('/public', express.static(path.join(__dirname, 'public')));

//-----------------------------------------------------------------------------------------------
//Start Movie Download
//-----------------------------------------------------------------------------------------------
app.get('/startDownload/:movieName', (req, res) => {
	streaming = null;
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

//-----------------------------------------------------------------------------------------------
//Function queried by client side to check progress of Movie Download
//-----------------------------------------------------------------------------------------------
app.get('/checkStatus', (req, res) => {
	if (check >= 1) {
		check = 0;
		res.send(movieDownloaded);
	}
	else {
		res.send('Pending');
	}
})

//-----------------------------------------------------------------------------------------------
//Index Page
//-----------------------------------------------------------------------------------------------
app.get('/', (req, res) => {
	res.sendFile(path.join(__dirname, 'views', 'index.html'));
})

//-----------------------------------------------------------------------------------------------
//Start Streaming Process
//-----------------------------------------------------------------------------------------------
http.createServer((req, res) => {
	stream(req, res);
}).listen(3001);

// app.get('/startStream/:movie', (req, res) => {
// 	if (req.params.movie)
// 	{
// 		var directories = find.dirSync(path.join(__dirname, 'downloads'));
// 		var movieToStream = (req.params.movie != null) ? req.params.movie :  "";
// 		streaming = movieToStream;

// 		if (movieToStream == "")
// 		{
// 			res.send('Movie is not Available!');
// 			return ;
// 		}

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
// 				var file = fs.createReadStream(filepath, {
// 					start: 0,
// 					end: chunksize,
// 					autoClose: false
// 				});
				
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
// 	else
// 	{
// 		res.send('video not available!');
// 	}

// 	-----------------------------------------------------------------------------------------------
// 	Start Playing, by redirecting to watchVideo, when Start stream is pressed
// 	-----------------------------------------------------------------------------------------------
// 	setTimeout(function(){
// 		res.redirect('/watchVideo?movie=' + req.params.movie);
// 	}, 3000)
// })

//-----------------------------------------------------------------------------------------------
//Start Watching Process
//-----------------------------------------------------------------------------------------------
// app.get('/watchVideo', function(req, res){
// 	res.sendFile(path.join(__dirname, 'views', 'watchStream.html'));
// });

//-----------------------------------------------------------------------------------------------
//Check if Movie Progress is suficient
//-----------------------------------------------------------------------------------------------
emitter.on('ready', function(percent) {
	if (percent)
		check = percent;
})

//-----------------------------------------------------------------------------------------------
//Check if download Failed
//-----------------------------------------------------------------------------------------------
emitter.on('downloadFailed', function() {
	check = 1;
})

app.listen(3000, () => {
	console.log('Listening on Port 3000');
})
