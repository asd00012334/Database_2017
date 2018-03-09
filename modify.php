<?php session_start();
if(!isset($_SESSION['account'])||$_SESSION['class']!="admin"){
	echo 'Modification Failed';
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
}
$account = htmlspecialchars($_POST['account']);
if($_POST['op']=='promote'){
	$sql = "UPDATE user_info SET class='admin'
		WHERE account=? and class!='admin'";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($account));
} else if($_POST['op']=='delete'){
	$sql = "DELETE FROM user_info
		WHERE account=? and account!='".$_SESSION['account']."'";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($account));
}
echo $account;
$conn=NULL;
?>
