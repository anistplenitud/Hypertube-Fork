<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$username = "root";
$password = "bitnami";

$table = "users";
$dbname = "hypertube";
$db = null;
try{
	$db = new PDO("mysql:host=$host", $username, $password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("set names utf8");
	$sql = "CREATE DATABASE IF NOT EXISTS hypertube DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    $db->exec($sql);
    $db->query("USE ".$dbname);
	$statement = "CREATE TABLE IF NOT EXISTS `$dbname`.`$table` (
		id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
        username varchar(255) NOT NULL,
        name varchar(255) NOT NULL, 
        surname varchar(255) NOT NULL, 
		email varchar(255) NOT NULL, 
		password varchar(255) NOT NULL,
		token text NOT NULL,
        verified int DEFAULT '0' NOT NULL,
		fb_id bigint DEFAULT '0' NOT NULL,
		oauth int DEFAULT '0' NOT NULL,
		picture varchar(255) NOT NULL)";
    $db->exec($statement);

    $sql = "CREATE TABLE IF NOT EXISTS user_comments (
            id INT(19) AUTO_INCREMENT PRIMARY KEY,
            torrent_id VARCHAR(255) NOT NULL,
            userid VARCHAR(255) NOT NULL,
            comment_text TEXT NOT NULL 
        ) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin";
    
    $db->exec($sql);
}
catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "";
	die();
}
?>