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

$check_in = $_GET['check_in'];
$check_out = $_GET['check_out'];
if(
	$check_in == '' || $check_out == '' ||
	($check_in >= $check_out) ||
	$check_in <= date("Y-m-d")
	)
{
	$_msg = "";
	if($check_in == '' || $check_out == '')
		$_msg = "NULL date";
	else if($check_in >= $check_out)
		$_msg = "check out should be after check in.";
	else
		$_msg = "check in should be after today.";
	echo "<script>alert('$_msg'); window.location='Order.php';</script>";
}


$sql = "
	SELECT * FROM `Order`
	WHERE id != :id AND house_id = :house_id
	AND(   
		(:check_in < check_out AND :check_in >= check_in)
		OR (:check_out > check_in AND :check_out <= check_out)
		OR (check_in < :check_out AND check_in >= :check_in)
		OR (check_out > :check_in AND check_out <= :check_out)
	)";
$stmt = $conn->prepare($sql);
$stat = $stmt->execute(array(
	':house_id' => $_GET['house_id'],
	':id' => $_GET['id'],
	':check_in' => $_GET['check_in'],
	':check_out' => $_GET['check_out']
));
$ori_msg = 'Conflict with: \n';
$msg = $ori_msg;
while($row = $stmt->fetchObject()){
	$msg .= "house id: ";
	$msg .= $row->house_id;
	$msg .= " interval: ";
	$msg .= $row->check_in;
	$msg .= " ~ ";
	$msg .= $row->check_out;
	$msg .= '\n';
}
if($msg != $ori_msg)
	echo "<script>alert('$msg'); window.location='Order.php';</script>";
else
{
	$sql = "
		UPDATE `Order` 
		SET check_in = :check_in, check_out = :check_out
		WHERE id = :id AND user_id = :user_id
		";
	$stmt = $conn->prepare($sql);
	$stat = $stmt->execute(array(
		':check_in' => $_GET['check_in'],
		':check_out' => $_GET['check_out'],
		':id'=>$_GET['id'],
		':user_id'=>$_SESSION['id']
	));
	if($stat)
		echo "<script>alert('OK'); window.location='Order.php';</script>";
	else	
		echo "<script>alert('Fail'); window.location='Order.php';</script>";
}
?>
