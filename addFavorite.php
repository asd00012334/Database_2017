<?php
session_start();
if(!isset($_SESSION['id'])||!isset($_GET['id'])){
	echo 'no id';
	exit();
}

$id=htmlspecialchars($_GET['id']);

try{
	$servername = 'dbhome.cs.nctu.edu.tw';
	$username = 'huangyh9999_cs';
	$password =  'password';
	$dbname = 'huangyh9999_cs_DBproj';
	$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,
		$password);
} catch(PDOException $e){
	echo 'Connection Failed';
	exit();
}
$symbol = array(
	':user_id'=>$_SESSION['id'],
	':favorite_id'=>$_GET['id']
);
$sql = "SELECT id FROM Favorite
	WHERE user_id=:user_id AND favorite_id=:favorite_id";
$stmt = $conn->prepare($sql);
$stat = $stmt->execute($symbol);
if($stmt->fetchObject()) 
{
	echo 'already in favorite.';
	exit();
}

$sql = "
	INSERT INTO Favorite (user_id,favorite_id)
	VALUES( :user_id, :favorite_id)
";

$stmt = $conn->prepare($sql);
$stat = $stmt->execute(array(
	':user_id'=>$_SESSION['id'],
	':favorite_id'=>$_GET['id']
));
if($stat) echo 'OK';
else echo 'Failure';

?>

