<!DOCTYPE HTML>
<html>
	<head>
		<title>Profile - Phantom Games</title>
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
					<a href="game">Games</a>
					<a href="profile" class="current-page-item">Profile</a>
                                        <a href="leaderboard">Leaderboard</a>
					<a href="../public">Log Out</a>
				</nav>
			</header>
		</div>
		<div id="main">
			<div id="container">	
				<section>
							<?php 	
								if($allAchievesUnlocked) 
									echo '<img src="medal.png" style="float:left;margin-left:85px;margin-top:9px;">
										<h2 style="position:relative;right:100px;">'. $name .'</h2>'; 
								else
									echo '<h2>'.$name.'</h2>';
							?>
							
							<p>Score: <?php echo $score; ?></p>
							<p>Play Time: <?php echo $playtime; ?> seconds</p>
							<p>Games Played: <?php echo $gamesplayed; ?></p>
							<br>
							<h2>Unlocked Acheivements</h2>
							<?php 
								if($allAchievesUnlocked) 
									echo '<b>You have unlocked all the achievements!</b>'; 
								  
								foreach($unlockedachevies as $unlocked)
									echo '<p>'. $unlocked . '</p>';
							?> 
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