<?php
class DataBase{
  private $host = "hockey-database.csvpzkxmb6zs.us-east-2.rds.amazonaws.com";
  private $db_name = "postgres";
  private $user = "postgres";
  private $password = "hockeyisthebest";
  public $conn;

  public function getConnection(){
      $this->conn = null;

      try{
          $this->conn = new PDO("pgsql:host=".$this->host.";dbname=".$this->db_name, $this->user, $this->password);
          $this->conn->exec("set names utf8");
      } catch(PDOException $exception){
          echo "Connection error: ".$exception->getMessage();
      }

      return $this->conn;
  }
}
?>