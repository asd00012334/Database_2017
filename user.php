<?php session_start();
	if(!isset($_SESSION['account'])){
		?><h1 style='color:red' size='25px'>低能兒，你忘記登入la :P</h1><?php
		exit();
	}
	if($_SESSION['class']=='admin'){
	?><script>window.location='admin.php';</script><?php
	}
?>
<html>
<head>
<link rel='stylesheet' type='text/css' href='UI.css'>
<script>
window.onload=function(){
	
};
</script>
</head>
<body>

<?php
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
?>

<div class='nav'>
	<a href='browse.php'>Browse</a>
	<a href='management.php'>Management</a>
	<a href='favorite.php'>Favorite</a>
	<a class='active'>User</a>
	<a href='Order.php'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>
<div style="width: 100%; height: 3em;"></div>
<div class='list'
	style="
		line-height:.5em; padding:.5em;
		clear: both;
		margin: auto;" align='center'>
	<table align='center'>
	<?php
		$result = $conn->query("
			SELECT name,account,email FROM user_info
			WHERE account='".$_SESSION['account']."'
		");
		while($row = $result->fetchObject()){
	?>
		<tr><th>Name:</th>
		<td><?php echo $row->name; ?></td></tr>
		<tr><th>Account:</th>
		<td><?php echo $row->account; ?></td></tr>
		<tr><th>Email:</th>
		<td><?php echo $row->email; ?></td>
		</tr>
	<?php
		}$conn=NULL;
	?>
	</table>
	
</div>
<script>
	function logout(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);

				window.location = 'home.php';
			}
			
		};
		xhttp.open('GET','logout.php',true);
		xhttp.send();
	}

</script>
</body>


</html>
