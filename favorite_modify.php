<?php session_start();
if(!isset($_SESSION['id'])) exit();
try{
	$servername = 'dbhome.cs.nctu.edu.tw';
	$username = 'huangyh9999_cs';
	$password =  'password';
	$dbname = 'huangyh9999_cs_DBproj';
	$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,
		$password);
} catch(PDOException $e){
	echo 'Connection Failed';
}
$favo_id = htmlspecialchars($_POST['id']);
$sql = "DELETE FROM Favorite
	WHERE id=? and user_id ='".$_SESSION['id']."'";
$stmt = $conn->prepare($sql);
$stmt->execute(array($favo_id));

$conn=NULL;
?>
