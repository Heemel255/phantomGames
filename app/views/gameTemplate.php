<!DOCTYPE HTML>
<html>
	<head>
		<title>Game Name - Phantom Games</title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="../css/style.css" />
	</head>
	<body>
            
            <script >
                var startTime = new Date();
                function end()
                {
                    var endTime = new Date();        
                    var timeSpent= Math.round((endTime - startTime) / 1000);
                    window.location.href = String("../getdata?time=" + timeSpent);
                }
                    
            </script>
		<div id="header-wrapper">
			<header id="header">
				<h1><a id="logo">Phantom Games</a></h1>
				<img src="../logo.png" style="margin-left:300px;">
				<nav id="nav">
					<a onclick="end();" style="cursor: pointer;" class="current-page-item">Go Back</a>
				</nav>
			</header>
		</div>
		<div id="main">
			<div id="container">
				<h2><?php echo $gamename; ?></h2>
			</div>
			<div id="container2">
                            <h3> Placeholder</h3>
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