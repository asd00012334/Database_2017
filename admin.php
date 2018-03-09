<?php session_start();
	if(!isset($_SESSION['account'])){
		?><h1 style='color:red' size='25px'>低能兒，你忘記登入la :P</h1><?php
		exit();
	} else if($_SESSION['class']!="admin"){
		?><h1 style='color:red' size='25px'>低能兒，你不是admin :P</h1><?php
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
	<a class='active'>User</a>
	<a href='Order.php'>Order</a>
	<a href='house.php'>House</a>
	<a onclick='logout();'>Logout</a>
	</div>
</div>

<div style='width:65em; margin:auto;' align='center'>
<table class='list' align='center'>
<th>Name</th>
<th>Account</th>
<th>Email</th>
<th>Class</th>
<th></th>
<?php
	$result = $conn->query("
		SELECT * FROM user_info
	");
	while($row = $result->fetchObject()){
?>	
	<tr>
	<td><?php echo $row->name; 
		if($row->account==$_SESSION['account']){?>
		<span style='color:red'>*</span><?php }?>
	</td>
	<td><?php echo $row->account; ?></td>
	<td><?php echo $row->email; ?></td>
	<td><?php echo $row->class; ?></td>
	<td>
	<?php if($row->account!=$_SESSION['account']){?>
	<button onclick="modify('<?php echo $row->account?>','delete');">
		Delete</button>
	<?php } else{?>
	<button onclick='logout()'>Logout</button>
	<button onclick='startRegister()'>Create</button>
	<?php }if($row->class!='admin'){?>
	<button onclick="modify('<?php echo $row->account?>','promote');">
		Promote</button><?php }?>
	</td>
	
	</tr>
<?php
	}$conn=NULL;
?>
</table>
<div class='disable' id='regBox'>
	<div class='lightbox'>
	<div class='disable' id='msg'>msg</div>
	<div id='regForm' style='padding-top:3em; padding-left:3em;'>
		<input placeholder='Account' name='account'><br>
		<input type='password'
			placeholder='Password' name='password'><br>
		<input type='password'
			placeholder='Password retype' name='retype'><br>
		<input placeholder='Name' name='name'><br>
		<input placeholder='Email' name='email'><br>
		<input type='radio' name='class' value='user' checked>user
		<input type='radio' name='class' value='admin'>admin<br>
		<button onclick="checkPswd(this.parentNode)&&
				submit(this.parentNode,'register.php',regCallback)">
			Register</button>
	</div>
	<div class='x' id='xbut'>X</div>
	</div>
</div>

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
		xhttp.open('GET','admin.php',true);
		xhttp.send();
	}
	function modify(account,op){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200){
				var res = this.responseText;
				console.log(res);
				update();
			}
		};
		xhttp.open('POST','modify.php',true);
		xhttp.setRequestHeader('Content-type',
			'application/x-www-form-urlencoded');
		xhttp.send('op='+op+'&account='+account);
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
	window.onload = function(){
		var xbut=document.getElementById('xbut');
		xbut.addEventListener('click',function(){
			close();
		})
	}
</script>
</body>


</html>
