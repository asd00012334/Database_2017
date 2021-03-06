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

$info_result = $conn->query("
    SELECT id, information AS info FROM Information;
	");
$info_arr = array();
while($row = $info_result->fetchObject())
	$info_arr[$row->id] = str_replace(" ", "_", $row->info);

$location_result = $conn->query("
    	SELECT id, location FROM Location;
	");
$location_arr = array();
while($row = $location_result->fetchObject())
    $location_arr[$row->id] = str_replace(" ", "_", $row->location);


if(isset($_POST['id'])){
	$name=htmlspecialchars($_POST['name']);
	$price=htmlspecialchars($_POST['price']);
	$location=htmlspecialchars($_POST['location']);
	$time=htmlspecialchars($_POST['time']);


	$sql = "SELECT owner_id FROM House WHERE id=:id;";
	$stmt = $conn->prepare($sql);
	$stat = $stmt->execute(array(
		':id'=>$_POST['id']
	));
	if($row = $stmt->fetchObject()){
		if($row->owner_id != $_SESSION['id']){
			echo "<h1>Access Denied</h1>";
			exit();
		}
	} else{
		echo "<h1>Invalid Id</h1>";
		exit();
	}


	$sql = "
		UPDATE House SET name=:name, price=:price,
			time=:time, owner_id=:owner_id 
			WHERE id=:id;";
	$stmt = $conn->prepare($sql);
	$stat = $stmt->execute(array(
		':name'=>$name,
		':price'=>$price,
		':time'=>$time,
		':owner_id'=>$_SESSION['id'],
		':id'=>$_POST['id']
	));
	$house_id = $_POST['id'];
	$info_select = "";
	
	$sql = "
		DELETE FROM House_Information WHERE
			house_id = :house_id;
		";
	$stmt = $conn->prepare($sql);
	$stmt = $stmt->execute(array(
		':house_id'=>$house_id
	));
	$sql = "
		DELETE FROM House_Location WHERE
			house_id = :house_id;
		";
	$stmt = $conn->prepare($sql);
	$stmt = $stmt->execute(array(
		':house_id'=>$house_id
	));
	

	foreach($info_arr as $key => $x){
		if(isset($_POST[$x]) && $_POST[$x] == 'selected'){
			$sql = "
				INSERT INTO House_Information (information_id,house_id)
					VALUES (:information_id, :house_id);";
			$stmt = $conn->prepare($sql);
			$stat = $stmt->execute(array(
				':information_id'=>$key,
				':house_id'=>$house_id
			));
		}
	}

	$location_id = array_search($location, $location_arr);
    if($location_id !== False)
    {
        $sql = "
    		INSERT INTO House_Location (location_id,house_id) VALUES(
   			:location_id, :house_id);";
        $stmt = $conn->prepare($sql);
        $stmt = $stmt->execute(array(
            ':location_id'=>$location_id,
            ':house_id'=>$house_id
        ));
    }


	if($stat) echo "<script>alert('OK');window.location='management.php';</script>";
	else echo "<script>alert('Fail');</script>";	
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


<div class='nav'>
	<a href='browse.php'>Browse</a>
	<a class='active' href="management.php">Management</a>
	<a href='favorite.php'>Favorite</a>
	<a href='user.php'>User</a>
	<a href='Order.php'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	<a href='management.php' style='float:right;'>Cancel</a>
	</div>
</div>
<div style="width:100%; height:3em;"></div>
<form action="house_modify.php" method="post" id="house_add">
<div class='list'
	style="
		line-height:.5em; padding:.5em;
		clear: both;
		margin: auto;" align='center'>
	<table align='center'>
	<tr>
	<th>House Id</th>
	<td><input type="test" name="id"></td></tr>

	<th>Name</th>
	<td><input type="text" name="name"></td></tr>
	
	<tr><th>Price</th>
	<td><input type="number" name="price"></td></tr>
	
	<tr><th>Location</th>
	<td>
	<select name='location' form="house_add">
	<?
		foreach($location_arr as $x){
			$y = str_replace("_", " ", $x);
			echo "<option value='$x'> $y <br>";
		}
	?>
	</select>

	</td></tr>
	
	<tr><th>Time</th>
	<td><input type="date" name="time"></td></tr>
	
	<tr><th>Owner</th>
	<td><input type="text" placeholder="<?php echo $_SESSION['username'];?>" disabled></td></tr>

	<tr><th>Information</th>
	<td>
	<?
		foreach($info_arr as $x){
			$y = str_replace("_", " ", $x);
			echo "<input type='checkbox' name='$x' value='selected'> $y <br>";
		}
	?>
	</td></tr>
	
	<tr><th></th>
	<td><button>Modify</button>
	</td></tr>
	</table>
</div>
</form>
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
