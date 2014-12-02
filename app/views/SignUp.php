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
				<div id="left">			
					<section>
					<?php echo $infos ?>
						<form>
						<h2>Login</h2>
						Username: <input type='text' name='user'>
						<br><br>
						Password: &nbsp;<input type='password' name='pass'>
						<br><br>
						<input type='submit' value='Log In'>
						</form>
					</section>
						
				</div>
				<div id="right">

					<section>
						<h2>Sign Up</h2>
						<form>
						Username: <input type='text' name='user'>
						<br><br>
						Password: <input type='password' name='pass'>
						<br><br>
						Re-Enter Password: <input type='password' name='verifypass'>
						<br><br>
						<input type='submit' value='Register'>
						</form>
					</section>
				</div>
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