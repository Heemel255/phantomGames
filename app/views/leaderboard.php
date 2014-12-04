<!DOCTYPE HTML>
<html>
	<head>
		<title>Leaderboard - Phantom Games</title>
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
					<a href="game" >Games</a>
					<a href="profile" >Profile</a>
                                        <a href="leaderboard" class="current-page-item">Leaderboard</a>
					<a href="../public">Log Out</a>
				</nav>
			</header>
		</div>
		<div id="main">
			<div id="container">
				<section>
					
                            <h2> Leaderboards</h2>
                                <table>
                                    <tr>
                                        <th style="border-top: 0; border-left:0;">Name</th>
                                        <th>Score</th>
                                    </tr>
                                    <?php 
                                        for($i = 0; $i < count($names); $i++){
                                            echo '
                                            <tr style="border:1px solid #878e83; text-align: center;">
                                                    <td style="width:20%;">'.$names[$i].'
													</td>
                                                    <td style="width:20%;">'.$scores[$i].'</td>
                                            </tr>';
                                        }
                                    ?>
                                </table>
                                    
						
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