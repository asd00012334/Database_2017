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

if(isset($_POST['info'])) {
	$info = str_replace("_"," ",htmlspecialchars($_POST['info']));
} else{
	echo 'Fail';
	$conn = NULL;
	exit();
}

if($_POST['op']=='add'){
	// lval=str_replace("_"," ",str)
	$sql = "INSERT INTO Information (information) VALUES (?)";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($info));
} else if($_POST['op']=='delete'){
	$sql = "DELETE FROM Information WHERE information=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute(array($info));
}
echo $info;
$conn=NULL;
?>
