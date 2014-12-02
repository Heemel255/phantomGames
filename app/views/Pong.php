<!DOCTYPE HTML>
<html>
	<head>
		<title>Game Name - Phantom Games</title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="../css/style.css" />
          	
                <style>
                canvas {
		display: block;
		position: absolute;
		margin: auto;
		top: 0;
		bottom: 0;
		left: 0;
		right: 0;
                }
                </style>
	</head>
	<body>
            
            <script >
                var startTime = new Date();
                var totalscore = 0;
                function end()
                {
                    var endTime = new Date();        
                    var timeSpent= Math.round((endTime - startTime) / 1000);
                    window.location.href = String("../getdata?time=" + timeSpent + "&score=" + totalscore);
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
                    <div style="height: 40em;">
                        <script>
                            var WIDTH  = 600, HEIGHT = 500;
                            var UpArrow = 38,DownArrow = 40;
                            var canvas,context,keystate;
                           
                            
                            player = {
                            x: null,
                            y: null,
                            width:  20,
                            height: 100,
                            
                            update: function() {
                                    if (keystate[UpArrow]) this.y -= 4;
                                    if (keystate[DownArrow]) this.y += 4;
                                    // keep the paddle inside of the canvas
                                    this.y = Math.max(Math.min(this.y, HEIGHT - this.height), 0);
                            },
                            
                            draw: function() {
                                    context.fillRect(this.x, this.y, this.width, this.height);
                            }
                    },
                    
                    ai = {
                            x: null,
                            y: null,
                            width:  20,
                            height: 100,
                            
                            update: function() {
                                    
                                    if(ball.y < this.y + (this.height / 2))
                                        this.y += -3;
                                    else if(ball.y == this.y + (this.height / 2))
                                        this.y += 0;
                                    else
                                        this.y += 3;
                            },
                            
                            draw: function() {
                                    context.fillRect(this.x, this.y, this.width, this.height);
                            }
                    },
                    
                    ball = {
                            x:   null,
                            y:   null,
                            vel: null,
                            side:  20,
                            speed: 12,
                            
                            serve: function(side) {
                                    
                                    this.x = 1;
                                    this.y = 1;
                                    
                                    this.vel = {
                                            x: 6,
                                            y: 6
                                    }
                            },
                            
                            update: function() {
                                    
                                    this.x += this.vel.x;
                                    this.y += this.vel.y;
                                    
                                    if (0 > this.y || this.y+this.side > HEIGHT) 
                                            this.vel.y *= -1;
                                    
                                    var pdle = this.vel.x < 0 ? player : ai;
                                    var intersectPaddle = pdle.x < this.x + this.side && pdle.y < this.y + this.side && this.x < pdle.x + pdle.width && this.y < pdle.y + pdle.height;
                                    
                                    if (intersectPaddle)
                                        this.vel.x = -this.vel.x;
                                    
                                    if (this.x >= WIDTH)
                                        totalscore++;
                                    
                                    if (0 > this.x + this.side || this.x > WIDTH) {
                                            this.serve(pdle == player ? 1 : -1);
                                    }
                            },
                            
                            draw: function() {
                                    context.fillRect(this.x, this.y, this.side, this.side);
                            }
                    };
                    
                    function runall() {
                            
                            canvas = document.createElement("canvas");
                            canvas.width = WIDTH;
                            canvas.height = HEIGHT;
                            context = canvas.getContext("2d");
                            document.body.appendChild(canvas);
                            keystate = {};
                            
                            document.addEventListener("keydown", function(evt) 
                            {
                                    keystate[evt.keyCode] = true;
                            });
                            document.addEventListener("keyup", function(evt) 
                            {
                                    delete keystate[evt.keyCode];
                            });
                            
                            startgame(); 
                            
                            var run = function() {
                                    update();
                                    draw();
                                    window.requestAnimationFrame(run, canvas);
                            };
                            window.requestAnimationFrame(run, canvas);
                            
                    }
                    
                    function startgame() {
                            player.x = player.width;
                            player.y = (HEIGHT - player.height)/2;
                            ai.x = WIDTH - (player.width + ai.width);
                            ai.y = (HEIGHT - ai.height)/2;
                            ball.serve(1);
                    }
                    
                    function update() {
                            ball.update();
                            player.update();
                            ai.update();
                            
                    }
                    
                    function draw() {
                            context.fillRect(0, 0, WIDTH, HEIGHT);
                           
                            context.save();
                            context.fillStyle = "#fff";
                            
                            ball.draw();
                            player.draw();
                            ai.draw();
                            context.font="30px Verdana";
                            context.fillText(String(totalscore),WIDTH / 2 - 10,50);
                            context.restore();
                            
                    }
                    
                    runall(); 
                    </script>
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