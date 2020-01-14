<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// include database and object files
include_once '../config/database.php';
include_once '../objects/player.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$player = new Player($db);

$stmt = $player->read_all();
$num = $stmt->rowCount();

// check if more than 0 records found
if($num>0){
    // products array
    $plyrs=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        // extract reference ids from result string
        $reference_ids = str_replace("\"", "", $reference_ids);

        $ref_ids = explode(", ", $reference_ids);
        $reference_ids = array();
        foreach($ref_ids as $rfid){
            $split = explode("=>", $rfid);
            $k = $split[0];
            $v = $split[1];
            $reference_ids[$k] = $v;
        }

        $teams = str_replace("{", "", $teams);
        $teams = str_replace("}", "", $teams);

        //extract teamids from result string
        $team_ids = explode(",", $teams);
        $teams = array();
        foreach($team_ids as $team){
            array_push($teams, $team);
        }

        $plyr_item=array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "reference_ids" => $reference_ids,
            "current_league" => $current_league,
            "link" => $link,
            "primary_number" => $primary_number,
            "birth_day" => $birth_day,
            "birth_month" => $birth_month,
            "birth_year" => $birth_year,
            "birth_city" => $birth_city,
            "birth_state" => $birth_state,
            "birth_country" => $birth_country,
            "nationality" => $nationality,
            "height" => $height,
            "weight" => $weight,
            "active" => $active,
            "captain" => $captain,
            "rookie" => $rookie,
            "shoots_catches" => $shoots_catches,
            "teams" => $teams,
            "current_team" => $current_team,
            "primary_position" => $primary_position
        );

        array_push($plyrs, $plyr_item);

        // set response code - 200 OK
        http_response_code(200);
        echo json_encode($plyrs);
    }
} else {

    http_response_code(200);
    echo json_encode(array());
}
