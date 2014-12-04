<!DOCTYPE HTML>
<html>
	<head>
		<title>Administration - Phantom Games</title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>
		<div id="header-wrapper">
			<header id="header">
				<h1><a id="logo">Phantom Games</a></h1>
				<img src="logo.png" style="margin-left:300px;">
				<nav id="nav">
					<a href="../public">Log Out</a>
				</nav>
			</header>
		</div>
		<div id="main">
			<div id="container">
				
					<h2>Add Game</h2>
					<form>
					Game Name: <input type="text" name="GameName"><br><br>
					<input type="submit" value="Add Game" style="margin-top:10px;">
					</form>
					<br><br><br>
					<h2>Remove User</h2>
					<form>
					Username: <input type="text" name="userdeleted"><br><br>
					<input type="submit" value="Remove User" style="margin-top:10px;">
					</form>
						<br>
						<?php echo $info; ?>
				</section>
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