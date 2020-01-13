<?php

class League
{
    // database connection and table name
    private $conn;
    private $table_name = "leagueinfo";
    // object properties
    public $id;
    public $name;
    public $acronym;
    public $league_location;
    public $shield_link;
    public $created;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function read_all(){
        // select all query
        $query = "SELECT * FROM ".$this->table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    // create product
    public function create(){

        // query to insert record
        $query = "
            INSERT INTO " . $this->table_name . "(name, acronym, league_location, shield_link, created)
            VALUES (:name, :acronym, :league_location, :shield_link, :created)";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->acronym=htmlspecialchars(strip_tags($this->acronym));
        $this->league_location=htmlspecialchars(strip_tags($this->league_location));
        $this->shield_link=htmlspecialchars(strip_tags($this->shield_link));
        $this->created=htmlspecialchars(strip_tags($this->created));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":acronym", $this->acronym);
        $stmt->bindParam(":league_location", $this->league_location);
        $stmt->bindParam(":shield_link", $this->shield_link);
        $stmt->bindParam(":created", $this->created);

        // execute query
        if($stmt->execute()){
            return true;
        }

        return false;

    }
}
