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
            
            if($this->user->isAdmin()){
                return Redirect::to("admintools");
            }
            else{
                $this->SaveUser();
                return Redirect::to("game");
            }
        }
        else{
            return View::make('SignUp')->with('infos',$this->signupInfo);
        }
    }

    public function execAdminTools()
    {
        if(isset($_GET['GameName'])){

            $this->gamesDBhandler = new GamesDB();
            $this->gamesDBhandler->addNewGame($_GET['GameName']);
            $this->adminInfo = 'Game inserted!';
        }
        if(isset($_GET['userdeleted'])){

            $this->accntHandler = new User();
            $this->accntHandler->deletePlayer($_GET['userdeleted']);
            $this->adminInfo = sprintf('User %s deleted',$_GET['userdeleted']);
        }
        
        return View::make('admin')->with('info',$this->adminInfo);
    }

    public function execProfile()
    {
        $this->accntHandler = new User();
        session_start();

        $userTemp = $_SESSION['user'] ;

        $unlockedachevies = Acheivments::getAllUnlocked($_SESSION['user']);

        return View::make('profile')
                ->with('name',$userTemp->getUsername())
                ->with('score',$userTemp->getScore())
                ->with('playtime',$userTemp->getPlayTime())
                ->with('gamesplayed',$userTemp->getGamesPlayed())
                ->with('unlockedachevies',$unlockedachevies);

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
        $totalGames = count($games);

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
                ->with('totalGames',$totalGames)
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

    /* ----------------- */

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
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

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

                    if($this->user == null){
                        $this->signupInfo = 'user already exists';
                    }
            }
            else {
                    $this->signupInfo = 'both passwords not same';
            }
        }
        else{
            $this->user = $this->accntHandler->LogIn($userN,$passW);

            if($this->user == null && isset($_GET['user'])){
                $this->signupInfo = 'not correct login info';
            }
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
        parent::setAdmin(true);
    }
}

class Acheivments{
    
    public static function getAllUnlocked($user)
    {
        $allAcheivesInFile = self::openfile();
    
        $unlocked = [];
        
        foreach($allAcheivesInFile as $all){
            
            $names = [];
            $scores = [];
            $playtimes = [];
            $gamesplayed = [];
            
            //4 parts to type explode
            $typeExplode = explode('|||', $all);
            
            foreach($typeExplode as $typeE){
                
                //2 parts to attribute explode
                $attExplode = explode('=', $typeE);
                
                if($attExplode[0] == 'name'){
                    $names[] = $attExplode[1];
                }
                else if($attExplode[0] == 'score'){
                    $scores[] = $attExplode[1];
                }
                else if($attExplode[0] == 'playtime'){
                    $playtimes[] = $attExplode[1];
                }
                else if($attExplode[0] == 'gamesplayed'){
                    $gamesplayed[] = $attExplode[1];
                }
            }
            
            for($i = 0; $i < count($names); $i++){
                
                if($user->getGamesPlayed() >= $gamesplayed[$i] 
                        && $user->getScore() >= $scores[$i] && $user->getPlayTime() >= $playtimes[$i]){
                    
                    $unlocked[] = sprintf('%s - Score:%s, Play Time:%s, Games Played:%s',$names[$i],$scores[$i],$playtimes[$i],$gamesplayed[$i]);
                }
            }
        }
        
        return $unlocked;
    }
    
    private static function openfile()
    {
        $allAcheivesInFile = [];
        
        $file = public_path() . '/achiev_require.txt';
        $fp = fopen($file, 'r');
        
        if($fp){
            $allAcheivesInFile = explode("\n", fread($fp, filesize($file)));
        }
        
        return $allAcheivesInFile;
    }
}