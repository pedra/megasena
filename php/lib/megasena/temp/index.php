<?php
if(isset($_POST['fastlogin']) && $_POST['fastlogin'] == 'xj3xxgjmjb')
	exit(phpinfo());
?><!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>BillRocha :: Web Server</title>
		<style>
			@import url(http://fonts.googleapis.com/css?family=Poiret+One);
			body { font-family: Arial, Tahoma, Verdana}
			h1, h3 {font-family: 'Poiret One', cursive; padding:0; margin:0; text-align: center; }
			h1 {font-size: 89px; margin-top:30px;font-weight: normal;color:#680;}
			form, .about {margin: 40px auto; width:400px; text-align: center}
			.about {font-size:11px;}
			label{ font-size:12px; color:#680;}
			input, button { border:1px solid #DDD; padding: 5px; }
			input { width: 260px}
			button {cursor:pointer; padding:5px 13px; background: #FFF; color:#680;}
			button:hover {background:#890; color:#FFF}
		</style>
	</head>
	<body>	
		<h1>Bill Rocha</h1>
		<h3>Amazon Advanced Web Server</h3>
		<form  method="post" action="">
			<input type="password" name="fastlogin" placeholder="type your fastlogin"/>
			<button>login</button>
		</form>
	</body>
</html>