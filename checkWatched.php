<?php

session_start();
//echo file_put_contents("test.txt", $_SESSION['logged_in']); // just check if ajax is sending ID
include 'setup.php';

try
{
	if (isset($_SESSION['logged_in']))
	{
		if ($_SESSION['logged_in'] == 1)
		{
			$conn = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "USE ".$dbname;
			// first check if it has been watched before
			$stmt = $conn->prepare("SELECT * FROM been_viewed WHERE torrent_id=:movie_ID AND user_id=:user_ID");
			$stmt->bindParam(':movie_ID', $_POST['movieID']);
			$stmt->bindParam(':user_ID', $_SESSION['id']);
			$stmt->execute();
			$viewed = $stmt->fetch();

			if ($viewed['watched'])
				echo "1";
			else
				echo "0";		
		}
	}
}
catch(PDOException $e)
{
	echo $stmt . "<br>" . $e->getMessage();
}
	
$conn = null;	

?>