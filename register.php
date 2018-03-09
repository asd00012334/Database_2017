<?php
session_start();
$account=htmlspecialchars($_POST['account']);
$pw=htmlspecialchars($_POST['password']);
$email=htmlspecialchars($_POST['email']);
$name=htmlspecialchars($_POST['name']);
if($account=='') {
	echo 'Empty account name';
	exit();
}

$hasSpace = false;
$n = strlen($account);
for($i=0;$i<$n;++$i){
	if($account[$i]==' '){
		$hasSpace = true;
		break;
	}
}

if($hasSpace){
	echo 'Account can not include spaces.';
	exit();
}

if($pw=='') {
	echo 'Empty password';
	exit();
}
if(strlen($pw)<1){
	echo 'Password too short';
	exit();
}
if($name=='') {
	echo 'Empty name';
	exit();
}
if($email=='') {
	echo 'Empty email';
	exit();
}
if(preg_match("/.+@.+\..+/", $email)==False) {
	echo 'Wrong email format';
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
$sql = "SELECT account,email FROM user_info WHERE account=? or email=?";
$stmt = $conn->prepare($sql);
$stmt->execute(array($account,$email));
if($row=$stmt->fetchObject()) {
	if($row->account==$account)
		echo 'Duplicated account';
	else echo 'Duplicated email';
	exit();
}
$pswd = hash('sha256',$pw);
$sql = "
	INSERT INTO user_info (name,account,email,password,class) VALUES(
		:name, :account,
		:email,:password,
		:class);";
$stmt = $conn->prepare($sql);
$rto = 'user';
if(isset($_SESSION['class'])&&
	$_SESSION['class']=='admin'&&
	isset($_POST['class'])&&
	$_POST['class']=='admin') $rto = 'admin';

$stat = $stmt->execute(array(
	':name'=>$name,
	':account'=>$account,
	':email'=>$email,
	':password'=>$pswd,
	':class'=>$rto
));
if($stat) echo 'OK';
else echo 'Failure';
?>

