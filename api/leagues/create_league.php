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

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->acronym) &&
    !empty($data->league_location)
){

    // set leagues property values
    $league->name = $data->name;
    $league->acronym = $data->acronym;
    $league->league_location = $data->league_location;
    if (array_key_exists('shield_link', $data)){
        $league->shield_link = $data->shield_link;
    }
    else{
        $league->shield_link = null;
    }

    $league->created = date('Y-m-d H:i:s');

    // create the leagues
    if($league->create()){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "League was created."));
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create leagues."));
    }
}

// tell the user data is incomplete
else{

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create leagues. Data is incomplete."));
}