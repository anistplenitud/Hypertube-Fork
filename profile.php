<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once("setup.php");

$db->query("USE ".$dbname);
$db->exec("USE hypertube");
$query = $db->prepare("SELECT * FROM users WHERE id = :id");
$query->bindParam(":id", $_SESSION['id']);
$query->execute();
$data = $query->fetch(PDO::FETCH_ASSOC);
$username = $data['username'];
$oauth = $data['oauth'];
$pp = $data['picture'];

?>

<!DOCTYPE html>
<html>
<title></title>
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
<link rel="manifest" href="/Hypertube/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/Hypertube/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">

	<script type="text/javascript" src="sort.js"></script>
	<script type="text/javascript" src="filter.js"></script>
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
	<style type="text/css">
		/* AESTHETIC */
	</style>
	<style type="text/css">
		.vl
		{
			width: 1px;
			background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0) 0%, rgb(139, 139, 139) 50%, rgba(0, 0, 0, 0) 100%);
		}
		.credit_table
		{
			width: 100%;
			overflow-x: scroll;
		}
		.cell_name
		{
			height: 45px;
			padding-bottom: 10px;
		}
		.cell_image
		{
			height: 90px;
		}
		.cell_role
		{
			height: 60px;
			font-size: smaller;
		}
		td:nth-child(even) 
		{
			background: #222222;
		}
		td:nth-child(odd)
		{
			background: #282828;
		}
		td
		{
			padding:0.25%;
		}
	</style>
</head>
<body>
	<div id="google_translate_element"></div>
<div class="topnav" id="myTopnav">
		<a class="navbar-brand" href="#">
			<img src="<?php echo $pp?>" alt="profile picture" style="width:40px;">
		</a>
		<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
			<?php echo $username ?>
		</a>
		<div class="dropdown-menu">
			<a class="dropdown-item" href="./profile.php">My Profile</a>
			<a class="dropdown-item" href="/Hypertube/logout.php">Logout</a>
		</div>
		<center>
		<div class="topnav-centered">
			<a href="/Hypertube/home.php"><img src="logo.png" alt="logo" height="70%" width="70%"></a>
		</div>
		</center>
</div>
	<br>
	<div id="result" class="card border-info mb-3">		
	</div>
</body>
</html>
<head>
	<title>Settings</title>
</head>
<body class="container">
<h1> Change Profile Picture </h1>
<form action="modProfileImg.php" method="post" enctype="multipart/form-data">
	Select image to upload:
	<input type="file" name="file" id="file">
	<input type="submit" value="Upload Image" name="submit">
</form>
<form action="modUsername.php" method="post">
	<h1> Change Username</h1>
  <label for="email" class="minor"><b>New Username</b></label>
  <input type="text" placeholder="Enter Username" name="newuser" required>
	<br /><br />
  <label for="email" class="minor"><b>Repeat Username</b></label>
  <input type="text" placeholder="Enter Username" name="newuser2" required>
	<br /><br />
  <button type="submit">change username</button>
 </div>
</form>
<?php if ($oauth == 0): ?>
<form action="modPassword.php" method="post">
<div>
  <h1>Password</h1>
  <label for="psw" class="minor" ><b>Old Password</b></label>
  <input type="password" placeholder="Enter Password" name="oldpasswd" required>
	<br /><br />
  <label for="psw" class="minor" ><b>New Password</b></label>
  <input type="password" placeholder="Enter Password" name="passwd" required>
	<br /><br />
	<label for="psw" class="minor" ><b>Repeat Password</b></label>
  <input type="password" placeholder="Enter Password" name="passwd2" required>
	<br /><br />
  <button type="submit">reset password</button>
 </div>
</form>
<form action="modEmail.php" method="post">
	<div>
		<h1>Email</h1>
				<label for="email" class="minor"><b>Old Email</b></label>
				<input type="text" placeholder="Enter Email" name="oldemail" required>
			<br /><br />
			<label for="email" class="minor"><b>New Email</b></label>
				<input type="text" placeholder="Enter Email" name="email1" required>
			<br /><br />
			<label for="email" class="minor"><b>Email</b></label>
				<input type="text" placeholder="Enter Email" name="email2" required>
			<br /><br />
			<button type="submit">change email</button>
	</div>
 </form>
<?php endif; ?>
	<script type="text/javascript">

			$("form").submit(function(event) 
			{
				event.preventDefault(); // Prevent the form from submitting via the browser
				
				$.ajax(
				{
					url: $(this).attr("action"),
					type: $(this).attr("method"),
					data: new FormData(this),
					processData: false,
					contentType: false,
					// success: function (data, status)
					// {
					// 	console.log(data);
					// },
					// error: function (xhr, desc, err)
					// {
					// 	console.log(err);
					// }
				});  
			});
	</script>
</body>
</html>