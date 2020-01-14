<?php
class Player{
    // database connection and table name
    private $conn;
    private $table_name = "playerinfo";

    public $reference_ids;
    public $current_league;
    public $link;
    public $first_name;
    public $last_name;
    public $primary_number;
    public $birth_day;
    public $birth_month;
    public $birth_year;
    public $birth_city;
    public $birth_state;
    public $birth_country;
    public $nationality;
    public $height;
    public $weight;
    public $active;
    public $captain;
    public $rookie;
    public $shoots_catches;
    public $teams;
    public $current_team;
    public $primary_position;

    public function __construct($db)
    {
        $this->conn = $db;
//        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }

    public function create()
    {
        $temp_refid = $this->referenceids_for_sql();
        $temp_teams = $this->teams_for_sql();

        $query = "INSERT INTO " . $this->table_name . "(
                     first_name, last_name, link, current_league, primary_number, birth_day, birth_month, birth_year, birth_city, birth_state, birth_country, nationality, height, weight, active, captain, rookie, shoots_catches, current_team, primary_position, teams, reference_ids
                  )
                  values (
                    :first_name,:last_name,:link,:current_league,:primary_number,:birth_day,:birth_month,:birth_year,:birth_city,:birth_state,:birth_country,:nationality,:height,:weight,:active,:captain,:rookie,:shoots_catches,:current_team,:primary_position,:teams,:reference_ids
                  )";
        // prepare the query
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":link", $this->link);
        $stmt->bindParam(":current_league", $this->current_league);
        $stmt->bindParam(":primary_number", $this->primary_number);
        $stmt->bindParam(":birth_day", $this->birth_day);
        $stmt->bindParam(":birth_month", $this->birth_month);
        $stmt->bindParam(":birth_year", $this->birth_year);
        $stmt->bindParam(":birth_city", $this->birth_city);
        $stmt->bindParam(":birth_state", $this->birth_state);
        $stmt->bindParam(":birth_country", $this->birth_country);
        $stmt->bindParam(":nationality", $this->nationality);
        $stmt->bindParam(":height", $this->height);
        $stmt->bindParam(":weight", $this->weight);
        $stmt->bindParam(":active", $this->active);
        $stmt->bindParam(":captain", $this->captain);
        $stmt->bindParam(":rookie", $this->rookie);
        $stmt->bindParam(":shoots_catches", $this->shoots_catches);
        $stmt->bindParam(":current_team", $this->current_team);
        $stmt->bindParam(":primary_position", $this->primary_position);
        $stmt->bindParam(":teams", $temp_teams);
        $stmt->bindParam(":reference_ids", $temp_refid);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        print_r($this->conn->errorInfo());
        return false;
    }

    private function teams_for_sql(){
        $sql = "{";
        for($i=0; $i < sizeof($this->teams); $i++){
            $sql = $sql . $this->teams[$i];
            if ($i < (count($this->teams) - 1)) {
                $sql = $sql . ",";
            }
        }
        return $sql . "}";
    }

    private function referenceids_for_sql(){
        $sql="";
        $counter = 0;
        foreach($this->reference_ids as $k => $v){
            $sql = $sql . $k . " => " . $v;
            if ($counter < (sizeof($this->reference_ids) - 1)){
                $sql = $sql . ", ";
            }
            $counter++;
        }
        return $sql;
    }

    public function insert_new_post($req){
        //take posted data and put in appropriate attributes
        $this->process($req);
        //insert the data into the db
        return $this->create();
    }

    public function process($req){
        foreach($req as $k => $v) {
            if ($k == "reference_ids") {
                $this->$k = array();
                foreach ($v as $i => $j) {
                    $this->$k[$i] = $j;
                }
            } else {
                $this->$k = $v;
            }
        }
    }

    public function read_all(){
        $query = "select * from " . $this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}
