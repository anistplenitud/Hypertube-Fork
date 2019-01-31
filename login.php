<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("setup.php");

$dbname = "hypertube";
$db->query("USE ".$dbname);
function	userexists($user, $pwd)
{
	$host = "localhost";
	$dbname = "hypertube";
	$db = new PDO("mysql:host=$host", "root", "123456");
	//$db  = new PDO("mysql:host=localhost;dbname=$dbname", $username, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->query("USE ".$dbname);
	$pswd = hash('whirlpool', $pwd);
	$one = "1";
	$query = $db->prepare("SELECT username, password  FROM users WHERE username = :name AND password = :passwd AND verified = :one");
	$query->bindParam(':name', $user);
	$query->bindParam(':passwd', $pswd);
	$query->bindParam(':one', $one);
	$query->execute();
	if ($query->rowcount() > 0)
		return (1);
	return (0);
}

if (isset($_POST['username']) && isset($_POST['password']))
{
	$user = $_POST['username'];
	$pwd = $_POST['password'];
}
if (isset($user) && isset($pwd))
{
	if (userexists($user, $pwd) == 1)
	{
		session_start();
		$query = "SELECT id FROM users where username = :user";
		$line = $db->prepare($query);
		$line->bindParam(':user', $user);
		$id = $line->execute();
		$_SESSION["user_id"] = $id;
		$query = "SELECT * FROM users where username = :user";
		$line = $db->prepare($query);
		$line->bindParam(':user', $user);
		$email = $line->execute();
		while ($row = $line->fetch(PDO::FETCH_ASSOC))
		{
			$email = $row;
		}
		$_SESSION['email'] = $email["email"];
		$_SESSION["username"] = $user;
		$_SESSION["logged_in"] = true;
		$_SESSION["id"] = $email['id'];
		$_SESSION["first_name"] = $email["name"];
		$_SESSION["last_name"] = $email["surname"];
		$_SESSION["email"] = $email["email"];
		header("Location: home.php?user=".$user);
	}
	else
	{
		echo "<script type='text/javascript'>alert('Username and password do not match or Account is not yet verified.');</script>";
		header("Location: index.php?err=yes");
	}
}
?>