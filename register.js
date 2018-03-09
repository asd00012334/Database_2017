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
		} else{
			msg.innerHTML = res;
			msg.setAttribute('class','msg_warn');
		}
	}
}
function checkPswd(form){
	var a = form.getElementsByTagName('input');
	var pswd, retype;
	for(var i=0;i<a.length;++i){
		if(a[i].name=='password') pswd=a[i];
		else if(a[i].name=='retype') retype=a[i];
	}
	var valid = pswd.value==retype.value;
	if(!valid){
		var msg = document.getElementById('msg');
		msg.innerHTML = 'Password Mismatch';
		msg.setAttribute('class','msg_warn');
		pswd.value = '';
		retype.value = '';
	}
	return valid;
}
function submit(form, target,callback){
	//console.log(a.children[0].children[1]);
	var a = form.getElementsByTagName('input');
	var name=[], value=[];
	for(var i=0;i<a.length;++i){
		var val = a[i].value;
		if(val==''){
			console.log(a[i].getAttribute('name'));
			var msg=document.getElementById('msg');
			msg.setAttribute('class','msg_warn');
			msg.innerHTML='Data Incomplete';
			return;
		}
		name.push(a[i].getAttribute('name'));
		value.push(a[i].value);
	}
	for(var i=0;i<a.length;++i)
		a[i].value='';
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = callback;
	xhttp.open('POST',target,true);
	xhttp.setRequestHeader('Content-type',
		'application/x-www-form-urlencoded');
	var reqVar=[]
	for(var i=0;i<name.length;++i){
		if(a[i].type=='radio'&&!a[i].checked) continue;
		if(i>0) reqVar.push('&');
		reqVar=reqVar.concat([name[i],'=',value[i]]);
	}
	reqVar = reqVar.join('');
	console.log(reqVar);
	xhttp.send(reqVar);
}
function startRegister(){
	var a=document.getElementById('regBox');
	a.setAttribute('class','shadow');
}
function close(){
	var regBox=document.getElementById('regBox');
	var msg=document.getElementById('msg');
	var form = document.getElementById('regForm');
	var a = form.getElementsByTagName('input');
	regBox.setAttribute('class','disable');
	msg.setAttribute('class','disable');
	for(var i=0;i<a.length;++i)
		a[i].value='';
}
