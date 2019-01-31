<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once("setup.php");
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['id'])) {
  header ('Location: ./');
}

$torrent_id = $_GET['torrent_id'];
$movie_title = $_GET['title'];

$db->exec("USE hypertube");
$query = $db->prepare("SELECT * FROM users WHERE id = :id");
$query->bindParam(":id", $_SESSION['id']);
$query->execute();
$data = $query->fetch(PDO::FETCH_ASSOC);
$username = $data['username'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="apple-touch-icon" sizes="57x57" href="/Hypertube/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/Hypertube/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/Hypertube/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/Hypertube/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/Hypertube/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/Hypertube/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/Hypertube/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/Hypertube/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/Hypertube/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/Hypertube/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/Hypertube/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/Hypertube/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/Hypertube/favicon/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/Hypertube/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script 
	src="https://unpkg.com/popper.js">
  </script>
	<script
	src="https://code.jquery.com/jquery-3.3.1.js"
	integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
	crossorigin="anonymous">
  </script>
  <script 
	src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
	integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
	crossorigin="anonymous">
  </script>

  <link 
	rel="stylesheet" 
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
	integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
	crossorigin="anonymous">
  <link 
	rel="stylesheet" 
	href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
	integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
	crossorigin="anonymous">
  <link 
	href="https://stackpath.bootstrapcdn.com/bootswatch/4.2.1/cyborg/bootstrap.min.css" 
	rel="stylesheet" 
	integrity="sha384-e4EhcNyUDF/kj6ZoPkLnURgmd8KW1B4z9GHYKb7eTG3w3uN8di6EBsN2wrEYr8Gc" 
	crossorigin="anonymous">
	<link href="style.css" rel="stylesheet" type="text/css" />
	<title>Hypertube</title>
	<style>
				body {
		  font-family: Arial;
		  background-color: rgb(66, 66, 68);
		}
		
		* {
		  box-sizing: border-box;
		}
		
		form.example input[type=text] {
		  padding: 10px;
		  font-size: 17px;
		  border: 1px solid grey;
		  float: left;
		  width: 50%;
		  background: #333;
		  color: white;
		  margin-top: 3px;
		}
		
		form.example button {
		  float: left;
		  margin-top: 3px;
		  width: 10%;
		  padding: 10px;
		  background: grey;
		  color: white;
		  font-size: 17px;
		  border: 1px solid grey;
		  border-left: none;
		  cursor: pointer;
		}
		
		form.example button:hover {
		  background: rgb(66, 66, 68);
		}
		
		form.example::after {
		  content: "";
		  clear: both;
		  display: table;
		}
		
		.topnav {
		  overflow: hidden;
		  background-color: #333;
		}

		.topnav {
		  overflow: hidden;
		  background-color: #333;
		}

		.topnav a {
		  float: left;
		  display: block;
		  color: #f2f2f2;
		  text-align: center;
		  padding: 14px 16px;
		  text-decoration: none;
		  font-size: 17px;
		}

		.topnav a:hover {
		  background-color: rgb(66, 66, 68);
		}

		.active {
		  background-color: grey;
		  color: white;
		}

		.topnav .icon {
		  display: none;
		}

		@media screen and (max-width: 600px) {
		  .topnav a:not(:first-child) {display: none;}
		  .topnav a.icon {
			float: right;
			display: block;
		  }
		}

		@media screen and (max-width: 600px) {
		  .topnav.responsive {position: relative;}
		  .topnav.responsive .icon {
			position: absolute;
			right: 0;
			top: 0;
		  }
		  .topnav.responsive a {
			float: none;
			display: block;
			text-align: left;
		  }
		}

		.sidenav {
		  height: 100%;
		  width: 0;
		  position: fixed;
		  z-index: 1;
		  top: 0;
		  left: 0;
		  background-color: #333;
		  overflow-x: hidden;
		  transition: 0.5s;
		  padding-top: 60px;
		}

		.sidenav a {
		  padding: 8px 8px 8px 32px;
		  text-decoration: none;
		  font-size: 25px;
		  color: #818181;
		  display: block;
		  transition: 0.3s;
		}

		.sidenav a:hover {
		  color: #f1f1f1;
		}

		.sidenav .closebtn {
		  position: absolute;
		  top: 0;
		  right: 25px;
		  font-size: 36px;
		  margin-left: 50px;
		}

		@media screen and (max-height: 450px) {
		  .sidenav {padding-top: 15px;}
		  .sidenav a {font-size: 18px;}
		}

		.row {
		  display: -ms-flexbox; /* IE10 */
		  display: flex;
		  -ms-flex-wrap: wrap; /* IE10 */
		  flex-wrap: wrap;
		  padding: 0 4px;
		}

		/* Create four equal columns that sits next to each other */
		.column {
		  -ms-flex: 25%; /* IE10 */
		  flex: 25%;
		  max-width: 25%;
		  padding: 0 4px;
		}

		.column img {
		  margin-top: 8px;
		  vertical-align: middle;
		}

		.column img:hover {
		  opacity: 0.7;
		}

		/* Responsive layout - makes a two column-layout instead of four columns */
		@media screen and (max-width: 800px) {
		  .column {
			-ms-flex: 50%;
			flex: 50%;
			max-width: 50%;
		  }
		}

		/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
		@media screen and (max-width: 600px) {
		  .column {
			-ms-flex: 100%;
			flex: 100%;
			max-width: 100%;
		  }
		}

		.btn {
		  background-color: grey;
		  border: none;
		  color: white;
		  padding: 12px 30px;
		  cursor: pointer;
		  font-size: 20px;
		}

		/* Darker background on mouse-over */
		.btn:hover {
		  background-color: #333;
		}

		hr {
			background-color: grey;
			color: red;
		}
	</style>
</head>
<body>
	<!--a href=""><i class="fa fa-eye fa-fw" style="color: white;"></i><a-->
	<div class="container-fluid">
	  <div class="row">
		<!--div class="column">
			<br />
			<button class="btn"><i class="fa fa-download"></i> Download</button> 
		</div-->
		  <div class="column">
			  <p style="color: white; align: center;">
				<div>
				  <form action="user/commentinfo.php?torrent_id=<?php echo $torrent_id.'&title='.$movie_title; ?>" method=POST id="commentform" accept-charset="UTF-8">
					<center>

							<textarea rows="4" style="background-color: #333; color: white; width: 97.5vw; box-sizing: border-box; margin-left: auto; margin-right: auto;" name="comment_text" form="commentform" required placeholder="Hey, say something :D (max chars:255)"></textarea>
							<button class="btn" style="width: 97.5vw; box-sizing: border-box;" type="submit" name="submit" required>comment</button>

					</center>
				  </form>
				</div>
			  </p>
		  </div>
	  </div>
		  
	  <br /> 

			<p style="color: white; align: center">
			  <?php
			  
				$stmt = $db->prepare("SELECT * FROM user_comments WHERE torrent_id = '$torrent_id' ORDER BY id DESC");
				$stmt->execute();

				echo '
				
				<p style="color: white";>
				  <center><b>COMMENTS</b></center>
				</p>
				
				<div>';
				
				while ($com = $stmt->fetch()) {
				  $userid = $com['userid'];
				  $stmt2 = $db->prepare("SELECT * FROM users WHERE id = $userid ORDER BY id DESC");
				  $stmt2->execute();

				  $user = $stmt2->fetch();
				  echo '
				  <div class="dialogbox">
					<div class="body">
					  <span class="tip tip-left"></span>
					  <div class="message">
						<span>
						  <img src="'.$user['picture'].'" width=50px height=50px> '.$user['username'].': '.$com['comment_text'] . '<br />
						</span>
					  </div>
					</div>
				  </div>
					';
				}
				echo '</div>';
			  ?>
			</p>

		
	</div>
</div>
<script>

function showUser(str) {
	if (str == "") {
		document.getElementById("txtHint").innerHTML = "";
		return;
	} else {
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("newcomment").innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET","user/realtimecomment.php?com="+str,true);
		xmlhttp.send();
	}
}
</script>

	<script>
		function myFunction() {
		  var x = document.getElementById("myTopnav");
		  if (x.className === "topnav") {
			x.className += " responsive";
		  } else {
			x.className = "topnav";
		  }
		}
	</script>

	<script>
		function openNav() {
		  document.getElementById("mySidenav").style.width = "250px";
		}
		
		function closeNav() {
		  document.getElementById("mySidenav").style.width = "0";
		}
	</script>
	<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>
