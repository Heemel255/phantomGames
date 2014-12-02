<!DOCTYPE HTML>
<html>
	<head>
		<title>Games - Phantom Games</title>
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
					<a href="game" class="current-page-item">Games</a>
					<a href="profile">Profile</a>
					<a href="leaderboard">Leaderboard</a>
					<a href="../public">Log Out</a>
				</nav>
			</header>
		</div>
		<div id="main">
			<div id="container">
				<section>
                                    <?php 
                                    for($i = 0; $i < $totalGames; $i++){
                                        echo sprintf("<b id='G1Name'>%s</b><br><br>
                                            Hits: <i id='G1Hits'>%s</i><br>
                                            <a href='playgame/%s'><img src='%s' alt='Game Name' style='width:450px;height:300px'></a>
                                            <br><br><br>",$gameNameArr[$i],$gameHitsArr[$i],$gameurl[$i],$gamesimageArr[$i]);
                                    }
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