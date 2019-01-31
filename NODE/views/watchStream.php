<html>
	<head>
		<title>Video stream sample</title>
	</head>
	<body>
		<video id="videoPlayer" controls>
			<source src="http://localhost:3001?movie=<? $_GET['movie'] ?>" type="video/mp4">
		</video>
		<!-- <button id="toggleButton">Start/Stop</button> -->

	</body>
</html>