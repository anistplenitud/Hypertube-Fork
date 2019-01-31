
const path = require('path');
const fs = require('fs');
const find = require('find');

module.exports = (req, res) => {

    var params = getParams(req);

    console.log(params);

    if (params.movie == undefined) {
        return;
    }
    else
	{
		var directories = find.dirSync('./downloads');
		var movieToStream = params.movie;

		if (movieToStream == "")
		{
			res.send('Movie is not Available!');
			return ;
		}

		for(var i = 0; i < directories.length; i++)
		{
			if (fs.existsSync(path.join(directories[i], movieToStream)))
			{
				var filepath = path.join(directories[i], movieToStream);
				break ;
			}
		}

        var stat = fs.statSync(filepath);
        var fileSize = stat.size;
        var range = req.headers.range;

        if(range)
        {
            var parts = range.replace('/bytes=/', "").split("-");
            var start = 0;
            var end = parts[1] ? parseInt(parts[1], 10) : fileSize - 1;
            
            var chunksize = (end - start) + 1;
            var file = fs.createReadStream(filepath, {
                start: start,
                end: end,
                autoClose: true
            });
            
            var head = {
                'transferMode.dlna.org': 'Streaming',
                'Cache-Control': 'private, no-cache, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0',
                'Expires' : '-1',
                'Access-Control-Allow-Origin' : '*',
                'Pragma' : 'no-cache',
                'Content-Range' : `bytes ${start}-${end}/${fileSize}`,
                'Accept_Ranges' : 'bytes',
                'Content-Length' : chunksize,
                'Content-Type': 'video/x-matroska',
                'Connection': 'keep-alive'
            }

            res.writeHead(206, head);

            file.pipe(res);
        }
        else
        {
            var head = {
                'Cache-Control': 'private, no-cache, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0',
                'Expires' : '-1',
                'Pragma' : 'no-cache',
                'Access-Control-Allow-Origin' : '*',
                'Content-Length': fileSize,
                'Content-Type': 'video/x-matroska'
            }

            res.writeHead(200, head);
            fs.createReadStream(filepath).pipe(res);
        }
	}
}

function getParams(req) {
    var getRequest = req.url.split('?');
    var result = {};

    if(getRequest.length >= 2){
        getRequest[1].split('&').forEach((item) => {
            try {
                result[item.split('=')[0]] = item.split('=')[1];
            } catch (e) {
                result[item.split('=')[0]]='';
            }
        })
    }
    return result;
}