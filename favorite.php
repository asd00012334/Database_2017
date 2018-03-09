<?php session_start();
	if(!isset($_SESSION['account'])){
		?><h1 style='color:red' size='25px'>低能兒，你忘記登入la :P</h1><?php
		exit();
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
	<a class='active'>Favorite</a>
	<a href='user.php'>User</a>
	<a href='Order.php'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>
<div style="width:100%; height:3em;"></div>
<div class='list'
	style="
		line-height:.5em; padding:.5em;
		clear: both;
		margin: auto;" align='center'>
	<table align='center'>
	<tr>
	<th>Id</th>
	<th>Name</th>
	<th>Price</th>
	<th>Location</th>
	<th>Time</th>
	<th>Owner</th>
	<th>Option</th>
	</tr>
	<?php
		$flag = 1;
		$result = $conn->query("
			SELECT *, tmp.id AS id, Favorite.id AS favo_id FROM(
			SELECT
			House.id, 
			House.name, 
			House.price, 
			House.time, 
			user_info.name as owner
			FROM user_info 
			LEFT JOIN House ON user_info.id = House.owner_id
			) AS tmp LEFT JOIN House_Location ON tmp.id = House_Location.house_id
			LEFT JOIN Location ON House_Location.location_id = Location.id
			JOIN Favorite ON tmp.id = Favorite.favorite_id
			WHERE Favorite.user_id = ".$_SESSION['id']."
		");
		function NullToStr($x){
			if($x===NULL) return "unknown";
			else return $x;
		}
		while($row = $result->fetchObject()){
			$flag = 0;
	?>
		<tr>
		<td><?php echo $row->id; ?></td>
		<td><?php echo $row->name; ?></td>
		<td><?php echo $row->price; ?></td>
		<td><?php echo NullToStr($row->location); ?></td>
		<td><?php echo $row->time; ?></td>
		<td><?php echo $row->owner; ?></td>
		<td>
		    <button onclick="delete_favorite('<?php echo $row->favo_id;?>');">
			Delete</button>
		</td>
		</tr>
	<?php
		}$conn=NULL;
		if($flag)
			echo "<tr>You don’t have any favorite house</tr>"
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
	function update(){
		console.log('update');
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				document.documentElement.innerHTML = res;
			}
		};
		xhttp.open('GET','favorite.php',true);
		xhttp.send();
	}
	function delete_favorite(id){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				update();
			}
		};
		xhttp.open('POST','favorite_modify.php',true);
		xhttp.setRequestHeader('Content-type',
			'application/x-www-form-urlencoded');
		xhttp.send('id='+id);
	}
</script>
</body>


</html>
