<?php session_start(); ?>
<html>
<head>
	<meta charset='utf8'></meta>
	<link rel='stylesheet' type='text/css' href='UI.css'>
	<script src='register.js'></script>
</head>
<body>
	<div class='wrapper'>
		<div class='topper'></div>
		<div class='sider'></div>
		<div class='disable' id='loginMsg'>login msg</div>
		<div class='middler'>
		<div style='margin: 0'>
			<input type='text' name='account'
				placeholder='Account'><br>
			<br>
			<input type='password' name='password'
				placeholder='Password'><br>
			<br>
			<button onclick="submit(this.parentNode,'login.php',loginCallback)"
				class='button' type='submit' style='float:left'>
			Login
			</button>
		</form>
		<button style='margin-left: 1em'
			onclick='startRegister();'>註冊帳號</button>
		</div>
		</div>
	</div>
	<div class='disable' id='regBox'>
		<div class='lightbox'>
		<div class='disable' id='msg'>msg</div>
		<div id='regForm' class='formDiv' style='padding-top:3em; padding-left:3em;'>
			<input placeholder='Account' name='account'><br>
			<input type='password'
				placeholder='Password' name='password'><br>
			<input type='password'
				placeholder='Password retype' name='retype'><br>
			<input placeholder='Name' name='name'><br>
			<input placeholder='Email' name='email'><br>
			<button onclick="checkPswd(this.parentNode)&&
				submit(this.parentNode,'register.php',regCallback)">
				Register</button>
		</div>
		<div class='x' id='xbut'>X</div>
		</div>
	</div>
	<script>
	function loginCallback(){
		if(this.readyState==4 && this.status==200){
			var res = this.responseText;
			var msg = document.getElementById('loginMsg');
			console.log('login res');
			console.log(res);
			console.log('login res_end')
			if(res=="user" || res=="admin"){
				msg.innerHTML = 'Login Success';
				msg.setAttribute('class','msg_ok');
				if(res=="user") window.location = 'user.php';
				else window.location = 'admin.php';
			} else {
				msg.innerHTML = 'Login Failed';
				msg.setAttribute('class','msg_warn');
			}	
		}
	}
	window.onload = function(){
		var state=<?php 
			if(isset($_SESSION['account']))
				if($_SESSION['class']=="user") echo 1;
				else echo -1;
			else echo 0;
			?>;
		if(state>0) window.location='user.php';
		else if(state<0) window.location='admin.php';
		var xbut=document.getElementById('xbut');
		xbut.addEventListener('click',function(){
			close();
		});
	};
	</script>
</body>

</html>
