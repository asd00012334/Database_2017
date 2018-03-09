<?php session_start();
	if(!isset($_SESSION['account'])){
		?><h1 style='color:red' size='25px'>低能兒，你忘記登入la :P</h1><?php
		exit();
	} 
?>
<html>
<head>
<script src='register.js'></script>
<link rel='stylesheet' type='text/css' href='UI.css'>
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
<div style="width:100%; height:3em"></div>
<div class='nav'>
	<a href='browse.php'>Browse</a>
	<a href='management.php'>Management</a>
	<a href='favorite.php'>Favorite</a>
	<a href='user.php'>User</a>
	<a href='Order.php'>Order</a>
	<a class='active'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>

<div style='width:65em; margin:auto;' align='center'>
<table class='list' align='center'>
	<th>House</th>
	<th>Check-in</th>
	<th>Check-out</th>
	<th>Visitor</th>
	
<?php
	$result = $conn->query("
		SELECT House.name, `Order`.check_in, `Order`.check_out, user_info.name as user
		FROM `Order`
		INNER JOIN House ON `Order`.house_id = House.id
		INNER JOIN user_info ON `Order`.user_id = user_info.id
		WHERE House.owner_id = ".$_SESSION['id']."
	");
	while($row = $result->fetchObject()){
?>	
	<tr>
	<td><?php echo $row->name; ?></td>
	<td><?php echo $row->check_in; ?></td>
	<td><?php echo $row->check_out; ?></td>
	<td><?php echo $row->user; ?></td>
	
	</tr>
<?php
	}$conn=NULL;
?>
</table>

</div>

<script>
	function regCallback(){
		if(this.readyState==4 && this.status==200){
			var msg = document.getElementById('msg');
			var res = this.responseText;
			console.log('res');
			console.log(res);
			console.log('res_end');
			if(res=="OK\n"){
				msg.innerHTML = 'Registration Complete';
				msg.setAttribute('class','msg_ok');
				close();
				update();
			} else{
				msg.innerHTML = res;
				msg.setAttribute('class','msg_warn');
			}
		}
	}
	
	function update(){
		console.log('update');
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				document.documentElement.innerHTML = res;
			}
		};
		xhttp.open('GET','information.php',true);
		xhttp.send();
	}
	function modify(loc,op){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				update();
			}
		};
		xhttp.open('POST','info_modify.php',true);
		xhttp.setRequestHeader('Content-type',
			'application/x-www-form-urlencoded');
		xhttp.send('op='+op+'&info='+loc);
	}

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
	window.onload = function(){}
</script>
</body>


</html>
