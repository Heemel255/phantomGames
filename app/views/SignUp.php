<!DOCTYPE HTML>
<html>
	<head>
		<title>Login/Signup - Phantom Games</title>
		<meta name="description" content=""/>
		<meta name="keywords" content=""/>
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>
		<div id="header-wrapper">
			<header id="header">
				<h1><a id="logo">Phantom Games</a></h1>
				<img src="logo.png" style="margin-left:300px;">
			</header>
		</div>
		<div id="main">
			<div id="reg_wrap">
			<p style="margin-left: 25%; margin-bottom:100px; font-size: 125%;">Welcome to Phantom Games! Make an account to start playing games and keep track of your score.</p>
				<div id="left">			
					<section>
						<form>
						<h2>Login</h2>
						Username: <input type='text' name='user'>
						<br><br>
						Password: <input type='password' name='pass' style="margin-left:4px;">
						<br><br>
						<input type='submit' value='Log In'>
						</form>
					</section>
						
				</div>
				<div id="right">

					<section>
						<h2>Sign Up</h2>
						<form>
						Username: <input type='text' name='user' style="margin-left:65px;">
						<br><br>
						Password: <input type='password' name='pass' style="margin-left:69px;">
						<br><br>
						Re-Enter Password: <input type='password' name='verifypass' style="margin-left:2px;">
						<br><br>
						<input type='submit' value='Register'>
						</form>
					</section>
				</div>
				<?php echo "<p style='color: red; margin-left:41%; margin-top:50px; font-size:120%'>".$infos."</p>" ?>
			</div>
		</div>
		<div id="footer-wrapper">
			<div id="container">
				<div id="copyright">
					&copy; 
				</div>
			</div>
		</div>
	</body>
</html>