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

$id = $_GET['id'];
$check_in = $_GET['check_in'];
$check_out = $_GET['check_out'];
if(
	$check_in == '' || $check_out == '' ||
	($check_in >= $check_out) ||
	$check_in <= date("Y-m-d")
	)
{
	if($check_in == '' || $check_out == '')
		echo "NULL date";
	else if($check_in >= $check_out)
		echo "check out should be after check in.";
	else
		echo "check in should be after today.";
	exit();
}

/*$sql = "SELECT * FROM Order
	WHERE user_id=:user_id AND favorite_id=:favorite_id";
$stmt = $conn->prepare($sql);
$stat = $stmt->execute($symbol);
if($stmt->fetchObject()) exit();*/

$sql = "
	INSERT INTO `Order` (user_id, house_id, check_in, check_out)
	VALUES( :user_id, :house_id, :check_in, :check_out)
";

$stmt = $conn->prepare($sql);
$stat = $stmt->execute(array(
	':user_id'=>$_SESSION['id'],
	':house_id'=>$_GET['id'],
	':check_in' => $check_in,
	':check_out' => $check_out
	));
if($stat) echo 'OK';
else echo 'Failure';

?>

