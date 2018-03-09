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
	<a href='favorite.php'>Favorite</a>
	<a href='user.php'>User</a>
	<a class='active'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>
<br>
<div style="width: 100%; height:3em;"></div>

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
	<th>Information</th>
	<th>check-in</th>
	<th>check-out</th>
	<th>Option</th>
	</tr>
	<?php
		$flag = 1;
		$result = $conn->query("
			SELECT *, `Order`.id AS id FROM (
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
			INNER JOIN `Order` ON tmp.id = `Order`.house_id
			WHERE `Order`.user_id = ".$_SESSION['id']."
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
		<?php
			$sub = $conn->query("
				SELECT Information.information FROM House_Information, Information
				WHERE House_Information.house_id=$row->house_id AND
				information_id = Information.id
			");
			while($sr = $sub->fetchObject())
				echo "<li>$sr->information</li>";
		?>
		</td>
		<td><?php echo $row->check_in; ?></td>
		<td><?php echo $row->check_out; ?></td>
		
		<td>
		    <button onclick="order_delete(<?php echo $row->id; ?>);">
				Delete
			</button>
			<form action="order_modify.php">
				<input type="number" name="house_id" value="<?php echo $row->house_id; ?>" hidden>
				<input type="number" name="id" value="<?php echo $row->id; ?>" hidden>
				<br>check-in
				<input type="date" name="check_in">
				<br>check-out
				<input type="date" name="check_out">
				<br>
				<button>
					Edit
				</button>
			</form>
		</td>
		</tr>
	<?php
		}$conn=NULL;
		if($flag)
			echo "<tr>You don’t order any house yet</tr>"
	?>
	</table>
	
</div>
<script>
	function order_delete(id){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			var res = this.responseText;
			console.log(res);
			update();
		};
		xhttp.open('GET','order_delete.php?id='+id.toString(),true);
		xhttp.send();
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
	function update(){
		console.log('update');
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				document.documentElement.innerHTML = res;
			}
		};
		xhttp.open('GET','Order.php',true);
		xhttp.send();
	}
	function order_modify(id, val){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				update();
			}
		};
		xhttp.open('POST','order_modify.php',true);
		xhttp.setRequestHeader('Content-type',
			'application/x-www-form-urlencoded');
		xhttp.send('id='+id+'&op='+val);
	}
</script>
</body>


</html>
