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
?>

<html>
<head>
<link rel='stylesheet' type='text/css' href='UI.css'>
<script>

</script>
</head>
<body>


<div class='nav'>
	<a class='active'>Browse</a>
	<a href='management.php'>Management</a>
	<a href='favorite.php'>Favorite</a>
	<a href='user.php'>User</a>
	<a href='Order.php'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>
<div style="width:100%; height:3em;"></div>

<div class='list'
	style="
		padding:.5em;
		clear: both;
		margin: auto;" align='center'>
	<table align='center'>
	<form id="search">
	<tr><th>Id</th>
	<th>Name</th>
	<th>
		<input type="radio" name="sortby" value="price_asc"> ^
		Price
		<input type="radio" name="sortby" value="price_desc"> v
	</th>
	<th>Location</th>
	<th>
		<input type="radio" name="sortby" value="time_asc"> ^
		Time
		<input type="radio" name="sortby" value="time_desc"> v
	</th>
	<th>Owner</th>
	<th>Info</th>
	<th>Option</th>
	</tr>
	<tr>
	<?php
		$info_select = "";
		foreach($info_arr as $key=>$x)
			if(isset($_GET[$x]) && $_GET[$x] == 'selected')
			{
				$x = str_replace("_", " ", $x);
				$info_select = $info_select.
								" AND House.id IN (SELECT house_id FROM House_Information WHERE information_id = '".$key."')";
			}
		
		$sort = "";
		if(isset($_GET['sortby']))
			$sort = $_GET['sortby'];
		if($sort != 'price_asc' && $sort != 'price_desc' && $sort != 'time_asc' && $sort != 'time_desc')
		{
			$sort = "";
		}
		$sort = str_replace("_", " ", $sort);
		if($sort != "")
			$sort = " ORDER BY House.".$sort;
		$name_select = "";
		$location_select = "";
		$time_select = "";
		$owner_select = "";
		$check_in = "";
		$check_out = "";
		isset($_GET['name']) && $name_select = $_GET['name'];
		isset($_GET['location']) && $location_select = $_GET['location'];
		isset($_GET['time']) && $time_select = $_GET['time'];
		isset($_GET['owner']) && $owner_select = $_GET['owner'];
		isset($_GET['check_in']) && $check_in = $_GET['check_in'];
		isset($_GET['check_out']) && $check_out = $_GET['check_out'];
		
		$price_select_string = "";
		$price_select = "";
		if(isset($_GET['price']))
		{
			$price_select = $_GET['price'];
			switch($_GET['price']){
				case "0~3000":
					$price_select_string = " (House.price BETWEEN 0 AND 3000) AND";
					break;
				case "3000~6000":
					$price_select_string = " (House.price BETWEEN 3000 AND 6000) AND";
					break;
				case "6000~12000":
					$price_select_string = " (House.price BETWEEN 6000 AND 12000) AND";
					break;
				case "12000~20000":
					$price_select_string = " (House.price BETWEEN 12000 AND 20000) AND";
					break;
				case "20000~":
					$price_select_string = " House.price > 20000 AND";
					break;
				default:
					$price_select_string = "";
			}
		}


		$id_select = "";
		$id_select_string = "";
		if(isset($_GET['id']) && $_GET['id'] != "")
		{
			$id_select = (int)$_GET['id'];
			$id_select_string = " House.id = ".(string)$id_select." AND";
		}
	?>
		<td><input size="1" placeholder="Id" name="id" value="<?php echo $id_select; ?>"></td>
		<td><input size="10" placeholder="Name" name="name" value="<?php echo $name_select; ?>"></td>
		<td><select name = "price" form="search">
			<option value = '' 
				<?php if($price_select == '') echo 'selected'; ?>
			> </option>
			<option value = '0~3000' 
				<?php if($price_select == '0~3000') echo 'selected'; ?>
			> 0 ~ 3000 </option>
			<option value = '3000~6000' 
				<?php if($price_select == '3000~6000') echo 'selected'; ?>
			> 3000 ~ 6000 </option>
			<option value = '6000~12000' 
				<?php if($price_select == '6000~12000') echo 'selected'; ?>
			> 6000 ~ 12000 </option>
			<option value = '12000~20000' 
				<?php if($price_select == '12000~20000') echo 'selected'; ?>
			> 12000 ~ 20000 </option>
			<option value = '20000~' 
				<?php if($price_select == '20000~') echo 'selected'; ?>
			> 20000 ~ </option>
		</select></td>
		<td><input size="6"
			placeholder="Location" name="location" value = <?php echo $location_select; ?>></td>
		<td><input size="8"
			placeholder="Time" name="time" value = <?php echo $time_select; ?>>
		<td><input size="6"
			placeholder="Owner" name="owner" value = <?php echo $owner_select; ?>>
		<td>
			<?php
				foreach($info_arr as $x)
					echo "<input type='checkbox' name = $x value='selected'> ".str_replace("_", " ", $x)."<br>";
			?>
		</td>
		<td>
			check-in: <input type="date" name='check_in' value=<?php echo $check_in; ?>><br>
			check-out:<input type="date" name='check_out' value=<?php echo $check_out; ?>><br>
			<button type='submit' name='Submit'>Search
		</td>
	</form>
	</tr>
	<?php
		$sql = "
			SELECT *, House.id as id FROM(
			SELECT
			House.id, 
			House.name, 
			House.price, 
			House.time, 
			user_info.name as owner
			FROM user_info 
			LEFT JOIN House ON user_info.id = House.owner_id
			) AS House LEFT JOIN House_Location ON House.id = House_Location.house_id
			LEFT JOIN Location ON House_Location.location_id = Location.id
			WHERE $id_select_string $price_select_string
			House.name LIKE '%' :name '%' AND
			(location LIKE '%' :location '%' OR location is NULL AND :location in ('', 'unknown')) AND
			House.time LIKE '%' :time '%' AND
			House.owner LIKE '%' :owner '%'
			$info_select $sort 
			";
			/*SELECT user_info.name as user, House.name as name,
				price,time,House.id as id
			FROM House INNER JOIN user_info
			ON owner_id=user_info.id
			WHERE ".$id_select_string.$price_select_string."
			House.name LIKE '%' ? '%' AND
			House.time LIKE '%' ? '%' AND
			user_info.name LIKE '%' ? '%'
			".$info_select.$sort."
		";*/
		$result = $conn->prepare($sql);
		$result->execute(array(
				':name' => $name_select, 
				':location' => $location_select,
				':time' => $time_select, 
				':owner' => $owner_select
			));
		for($i=0;$row = $result->fetchObject();$i+=1){
			$house_id = $row->id;
			$tmp_sql = "
					SELECT * FROM `Order` WHERE 
					$house_id = house_id AND(
					(:check_in < check_out AND :check_in >= check_in)
					OR (:check_out > check_in AND :check_out <= check_out)
					OR (check_in < :check_out AND check_in >= :check_in)
					OR (check_out > :check_in AND check_out <= :check_out)
					)
				";
			$tmp_result = $conn->prepare($tmp_sql);
			$tmp_result->execute(array(
				':check_in' => $check_in,
				':check_out' => $check_out
			));
			$tmp_row = "";
			if($tmp_row = $tmp_result->fetchObject())
			{
				//echo "<tr><td>$tmp_row->id</td><td>$tmp_row->check_in</td></tr>";
				$i -= 1;
				continue;
			}
	?>
		<tr id="<?php echo $i;?>.row">
		<td><?php echo $row->id;?></td>
		<td><?php echo $row->name;?></td>
		<td><?php echo $row->price;?></td>
		<td><?php echo $row->location;?></td>
		<td><?php echo $row->time;?></td>
		<td><?php echo $row->owner;?></td>
		<td>
		<?php
			$sub = $conn->query("
				SELECT information_id FROM House_Information
				WHERE house_id=$row->id
			");
			while($sr = $sub->fetchObject())
				echo "<li>".$info_arr[$sr->information_id]."</li>";
		?>
		</td>
		<td><button onclick="addFavorite(<?php echo $row->id ?>);">Favorite</button>
			<?php if($_SESSION['class']=='admin'){?>
			<button onclick='del(<?php echo $row->id; ?>)'>Delete</button>
			<?php }?>
			<button onclick='book(<?php echo "$row->id, \"$check_in\", \"$check_out\""?>)'>
				Book
			</button>
		</td>
		</tr>
	<?php
		}$conn=NULL;
		$recordNum = $i;
		echo "<script>recordNum=$recordNum;</script>";
		$pageNum = floor($recordNum/5);
		if($recordNum%5>0) $pageNum+=1;
		for($i=0;$i<$pageNum;$i+=1){
			echo "<a href='javascript:
				window.location.hash = $i;
				window.location.reload();
			'>|$i|</a>";
		}
	?>
	</table>
	
</div>
<script>
	function book(id, check_in, check_out){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				alert(res);
			}
		};
		xhttp.open('GET',
			'book.php?id='+id.toString()+
			'&check_in='+check_in+
			'&check_out='+check_out,
			true);
		/*alert(
			'book.php?id='+id.toString()+
			'&check_in='+check_in.toString()+
			'&check_out='+check_out.toString()
		)*/
		xhttp.send();
	}
	function del(id){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				alert(res);
			update();
			}
		};
		xhttp.open('GET','admin_del_house.php?id='+id.toString(),true);
		xhttp.send();
	}
	
	function addFavorite(id){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				alert(res);
			}
		};
		xhttp.open('GET','addFavorite.php?id='+id.toString(),true);
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
		xhttp.open('GET','browse.php',true);
		xhttp.send();
	}
	window.onload=function(){
		var hashCode = window.location.hash;
		var recordNum = <?php echo $recordNum; ?>;
		var pageSize = 5;
		var curPage = hashCode==""?0:parseInt(hashCode.slice(1));
		var baseIdx = curPage*pageSize;
		for(var i=0;i<recordNum;++i){
			var obj = document.getElementById(""+i+".row");
			if(baseIdx<=i && i<baseIdx+pageSize){
				obj.style.display = "";
			} else{
				obj.style.display = "none";
			}
		}
};
</script>
</body>


</html>
