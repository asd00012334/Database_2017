<?php session_start();
if(!isset($_SESSION['account'])){
	?><h1 style='color:red' size='25px'>低能兒，你忘記登入la :P</h1><?php
	exit();
}

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

$sql = "
	DELETE FROM House
	WHERE id = :id AND owner_id = :owner_id;";
$stmt = $conn->prepare($sql);
$stat = $stmt->execute(array(
	':id'=>$_GET['id'],
	':owner_id'=>$_SESSION['id']
));
if($stat) echo "OK";
else echo "Fail";	
?>
