<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// include database and object files
include_once '../config/database.php';
include_once '../objects/league.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$league = new League($db);

$stmt = $league->read_all();
$num = $stmt->rowCount();


// check if more than 0 records found
if($num>0){

    // products array
    $leagues_arr=array();
    $leagues_arr["leagues"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $league_item=array(
            "id" => $id,
            "name" => $name,
            "acronym" => $acronym,
            "league_location" => $league_location,
            "shield_link" => $shield_link,
            "created" => $created
        );

        array_push($leagues_arr["leagues"], $league_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($leagues_arr);
}
else{

    // set response code - 404 Not found
    http_response_code(200);

    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}