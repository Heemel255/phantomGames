<?php



class User extends Eloquent{
    
    private $con;
    
    public function __construct()
    {
        $this->con = new PDO("mysql:dbname=f4907207_phantomGames;host=gblearn.com","f4907207_phantom","zRX,zU*F+?G$");
    }
    private function fetchFromTable($table)
	{
		$qry = $this->con->query("SELECT * FROM ".$table);
        return $qry->fetchAll();
	}
    public function LogIn($userEn,$passEn)
    {
        $fetched = $this->fetchFromTable("player");
        for($i = 0; $i < count($fetched); $i++){

            if($userEn == $fetched[$i]['username'] && $passEn == $fetched[$i]['password']){

                return new Player($fetched[$i]['username'],$fetched[$i]['password'],
                        $fetched[$i]['score'],$fetched[$i]['playTime'],$fetched[$i]['gamesPlayed']);

            }
        }
        
        //is not player, check if account is admin
		$fetched = $this->fetchFromTable("admin");
          
        for($i = 0; $i < count($fetched); $i++){

            if($userEn == $fetched[$i]['username'] && $passEn == $fetched[$i]['password']){

                return new Admin($fetched[$i]['username'],$fetched[$i]['password']);

            }
        }
        
        return null;
    }
    public function SignUp($userEn,$passEn)
    {
        $fetched = $this->fetchFromTable("player");
        for($i = 0; $i < count($fetched); $i++){

            if($userEn == $fetched[$i]['username']){
                
                return null;

            }
        }
        
        $query = sprintf("INSERT INTO player values ('%s','%s',0,0,0)",$userEn,$passEn);
        $this->con->exec($query);
        
        return new Player($userEn,$passEn,0,0,0);
    }
    public function updatePlayer($score,$playTime,$gamesPlayed,$user)
    {
        $query = sprintf("UPDATE player SET score=%d,playTime=%d,gamesPlayed=%d WHERE username='%s'",
                $score,$playTime,$gamesPlayed,$user);
        $q = $this->con->prepare($query);
        $q->execute();
    }
    public function getLeaderboards()
    {
        $arrayLead = [];
        $fetched = $this->fetchFromTable("player ORDER BY score DESC");
        
		for($i = 0; $i < count($fetched); $i++)
			$arrayLead[$i] = $fetched[$i]['username'] . ':' . $fetched[$i]['score'];
        
        return $arrayLead;
    }
    
    public function deletePlayer($userEn)
    {
        $fetched = $this->fetchFromTable("player");
		
        for($i = 0; $i < count($fetched); $i++){

            if($userEn == $fetched[$i]['username']){
                
                $query = sprintf("delete from player where username='%s'",$userEn);
				$this->con->exec($query);
				return true;
            }
        }
		
		return false;
        
    }
}
