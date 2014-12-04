<?php

class HomeController extends BaseController {
        
    private $user;
    
    private $accntHandler;
    private $gamesDBhandler;
    
    private $signupInfo = '';
    private $adminInfo = '';

    public function exec()
    {
        $this->accntHandler = new User();
        $this->CreateUserObj(Input::get('user'),Input::get('pass'),Input::get('verifypass'));

        if($this->user != null){
            
            $this->SaveUser();
			
            if($this->user->isAdmin())
                return Redirect::to("admintools");
            else
				return Redirect::to("game");
        }
        else{
            return View::make('SignUp')->with('infos',$this->signupInfo);
        }
    }

    public function execAdminTools()
    {
        if(isset($_GET['GameName'])){

            $this->gamesDBhandler = new GamesDB();
            
            if($this->gamesDBhandler->addNewGame($_GET['GameName']))
				$this->adminInfo = 'Game inserted!';
			else
				$this->adminInfo = 'Game already exists.';
			
        }
        if(isset($_GET['userdeleted'])){

            $this->accntHandler = new User();
            
            if($this->accntHandler->deletePlayer($_GET['userdeleted']))
                $this->adminInfo = sprintf('User %s deleted.',$_GET['userdeleted']);
            else
                $this->adminInfo = sprintf('User %s does not exist.',$_GET['userdeleted']);
        }
        
        return View::make('admin')->with('info',$this->adminInfo);
    }

    public function execProfile()
    {
        $this->accntHandler = new User();
        
        session_start();
        $usertemp = $_SESSION['user'];
        
        $unlockedachevies = Acheivments::getAllUnlocked($usertemp);
        $checkAllAchieves = Acheivments::allAchievesUnlocked($usertemp);

        return View::make('profile')
                ->with('name',$usertemp->getUsername())
                ->with('score',$usertemp->getScore())
                ->with('playtime',$usertemp->getPlayTime())
                ->with('gamesplayed',$usertemp->getGamesPlayed())
                ->with('unlockedachevies',$unlockedachevies)
		->with('allAchievesUnlocked',$checkAllAchieves);

    }
    public function execLeaderBoard()
    {
        $this->accntHandler = new User();

        $leadtemp = $this->accntHandler->getLeaderboards();
        
        $name = [];
        $score = [];
		
        foreach($leadtemp as $lt){

            $tempexplode = explode(':', $lt);
            $name[] = $tempexplode[0];
            $score[] = $tempexplode[1];
		}
		
		return View::make('leaderboard')
                ->with('names',$name)
                ->with('scores',$score);
    }
    public function execGamePage()
    {
        $this->gamesDBhandler = new GamesDB();
		$games = $this->gamesDBhandler->getAllGames();
        
		$gamename = [];
        $gamehit = [];
        $gamesimage = [];
        $gamenameurlparam = [];

        foreach($games as $g){

            $tempexplode = explode(':::', $g);
            $gamename[] = $tempexplode[0];
            $gamehit[] = $tempexplode[1];

            if(file_exists(sprintf('%s/%s.png',public_path(), (str_replace(' ', '_', $tempexplode[0])))))
                $gamesimage[] = sprintf('%s.png',(str_replace(' ', '_', $tempexplode[0])) );
            else
                $gamesimage[] = 'temp.png';

            if(file_exists(sprintf('%s/views/%s.php',app_path(), (str_replace(' ', '_', $tempexplode[0])))))
                $gamenameurlparam[] = str_replace(' ', '_', $tempexplode[0]);
            else
                $gamenameurlparam[] = 'gameTemplate';
		}

        return View::make('game')
                ->with('totalGames',count($games))
                ->with('gameNameArr',str_replace('_',' ',$gamename))
                ->with('gameHitsArr',$gamehit)
                ->with('gamesimageArr',$gamesimage)
                ->with('gameurl',$gamenameurlparam);

    }
	
    public function execPlayGame($gamename)
    {
        $this->gamesDBhandler = new GamesDB();
        $this->gamesDBhandler->updategamehits(str_replace('_',' ', $gamename));

        $this->accntHandler = new User();
        $this->user = $this->CreateLoggedInUserOj();
        $this->user->updateGamesPlayed();

        $this->UpdateUser();

        return View::make($gamename)->with('gamename',  str_replace("_",' ', $gamename));
    }
    
    public function execGetGameData()
    {
        $this->accntHandler = new User();
        $this->user = $this->CreateLoggedInUserOj();
        
        if(isset($_GET['time']))
            $this->user->updatePlayTime($_GET['time']);
            
        if(isset($_GET['score']))
            $this->user->updateScore($_GET['score']);
            
        
        $this->UpdateUser();
        return Redirect::to("game");
    }

    public function UpdateUser()
    {

        $this->accntHandler->updatePlayer($this->user->getScore(),$this->user->getPlayTime(),
                $this->user->getGamesPlayed(),$this->user->getUsername());

        $this->SaveUser();
    }

    public function SaveUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = $this->user;
    }

    //this function is for retrieving an already logged in user that is saved into a session
    public function CreateLoggedInUserOj()
    {
        if (session_status() == PHP_SESSION_NONE) 
            session_start();
        
		return new Player($_SESSION['user']->getUsername(),$_SESSION['user']->getPassword(),
                $_SESSION['user']->getScore(),$_SESSION['user']->getPlayTime(),$_SESSION['user']->getGamesPlayed()); 
    }

    //this function is used to either login or create a user that has arrived to the sign up page
    public function CreateUserObj($userN,$passW,$verPassW)
    {
        $this->signupInfo = '';

        if(isset($_GET['verifypass'])){

            if($passW === $verPassW){

                $this->user = $this->accntHandler->SignUp($userN,$passW);

				if($this->user == null)
					$this->signupInfo = 'Username was already taken!';
                    
            }
            else {
				
				$this->signupInfo = 'Passwords do not match!';
            }
        }
        else{
            $this->user = $this->accntHandler->LogIn($userN,$passW);

            if($this->user == null && isset($_GET['user']))
                $this->signupInfo = 'Incorrect login info!';
            
        }
    }
}

abstract class AbstractAccount{
    
    protected $username;
    protected $password;
    protected $isadmin;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    
    public function getUsername()
    { 
        return $this->username;
    }
    
    public function getPassword()
    { 
        return $this->password;
    }
    
    public function isAdmin()
    {
        return $this->isadmin;
    }
    protected function setAdmin($s)
    {
        $this->isadmin = $s;
    }
}

class Player extends AbstractAccount{

    private $score = 0;
    private $playTime = 0;
    private $gamesPlayed = 0;
	
	public function __construct($username, $password, $score, $playTime, $gamesPlayed)
    {
        parent::__construct($username, $password);
        parent::setAdmin(false);
        
        $this->score = $score;
        $this->playTime = $playTime;
        $this->gamesPlayed = $gamesPlayed;
    }
    
    public function getScore()
    { 
        return $this->score;
    }
    public function getPlayTime()
    { 
        return $this->playTime;
    }
    public function getGamesPlayed()
    { 
        return $this->gamesPlayed;
    }
    
    public function updateScore($score)
    { 
        $this->score += $score;
    }
    
    public function updatePlayTime($time)
    { 
        $this->playTime += $time;
    }
    
    public function updateGamesPlayed()
    {
        $this->gamesPlayed++;
    }
}

class Admin extends AbstractAccount{

    
    public function __construct($username, $password)
    {
		parent::__construct($username, $password);
        parent::setAdmin(true);
    }
}

class Acheivments{
    
    public static function allAchievesUnlocked($user)
    {
            $unlocked = count(self::getAllUnlocked($user));
            $all = count(self::openfile());
            return $all == $unlocked;
    }
	
    public static function getAllUnlocked($user)
    {
        $allAcheivesInFile = self::openfile();
        $unlocked = [];
        
        //iterate through each achievemnt
        foreach($allAcheivesInFile as $all){
            
            //4 parts to type explode
            $typeExplode = explode('|||', $all);
            
            $names = self::getAtrributes($typeExplode,'name');
            $scores = self::getAtrributes($typeExplode,'score');
            $playtimes = self::getAtrributes($typeExplode,'playtime');
            $gamesplayed = self::getAtrributes($typeExplode,'gamesplayed');
            
            if($user->getGamesPlayed() >= $gamesplayed 
                    && $user->getScore() >= $scores && $user->getPlayTime() >= $playtimes){

                $unlocked[] = sprintf('<b>%s</b><br>Score: %s - Play Time: %s - Games Played: %s',$names,$scores,$playtimes,$gamesplayed);
            }
            
        }
        
        return $unlocked;
    }
    
    private static function getAtrributes($typeExplode,$attrName)
    {
        $attr = '';
        foreach($typeExplode as $typeE){
                
            //2 parts to attribute explode
            $attExplode = explode('=', $typeE);

            if($attExplode[0] == $attrName){
                $attr = $attExplode[1];
            }
            
        }
        return $attr;
    }
    
    private static function openfile()
    {
        $allAcheivesInFile = [];
        
        $file = public_path() . '/achiev_require.txt';
        $fp = fopen($file, 'r');
        
        if($fp)
            $allAcheivesInFile = explode("\n", fread($fp, filesize($file)));
		
        fclose($fp);
        
        while($emptyline = array_search('', $allAcheivesInFile))
                unset($allAcheivesInFile[$emptyline]);
        
        
        return $allAcheivesInFile;
    }
    
}