<?php



class GamesDB extends Eloquent{
    
    private $con;
    
    public function __construct()
    {
        $this->con = new PDO("mysql:dbname=f4907207_phantomGames;host=gblearn.com","f4907207_phantom","zRX,zU*F+?G$");
    }
    private function fetchFromTable()
	{
		$qry = $this->con->query("SELECT * FROM games");
        return $qry->fetchAll();
	}
    public function getAllGames()
    {
        $allgames = [];
        
        $fetched = $this->fetchFromTable();
        
        for($i = 0; $i < count($fetched); $i++)
            $allgames[] = $fetched[$i]['name'] . ':::' . $fetched[$i]['hits'];

        return $allgames;
    }
    
    public function addNewGame($name)
    {
		$fetched = $this->fetchFromTable();
		
		for($i = 0; $i < count($fetched); $i++)
            if($fetched[$i]['name'] == $name)
				return false;
				
        $query = sprintf("INSERT INTO games values ('%s',0)",$name);
        $this->con->exec($query);
        
        $this->makeFileCopy($name);
		
		return true;
    }
    
	private function makeFileCopy($name)
	{
		$newname = str_replace(' ', '_', $name);
        
        $phpfile = sprintf('%s/views/gameTemplate.php',app_path());
        $imgfile = sprintf('%s/temp.png',public_path());
        
        $newphpfile = sprintf('%s/views/%s.php',app_path(),$newname);
        $newimgfile = sprintf('%s/%s.png',public_path(),$newname);
        
        copy($phpfile,$newphpfile);
        copy($imgfile,$newimgfile);
	}
	
    public function updategamehits($name)
    {
        $query = sprintf("UPDATE games SET hits=hits + 1 WHERE name='%s'",$name);
        $q = $this->con->prepare($query);
        $q->execute();
    }
}
