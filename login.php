<?php
session_start();
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
$sql = "select id, account, class, name, password from user_info where account=:account";
$stmt = $conn->prepare($sql);
$account = $_POST['account'];
$stmt->execute(array(':account'=>$account));
if($row=$stmt->fetchObject()) {
	if($row->password == hash('sha256',$_POST['password'])){
		echo $row->class;
		$_SESSION['account'] = $row->account;
		$_SESSION['class'] = $row->class;
		$_SESSION['id'] = $row->id;
		$_SESSION['username'] = $row->name;
	} else echo "Wrong password";
} else echo "No user";// echo "<script>window.location='login.html'</script>";
// html special strip tag
?>
