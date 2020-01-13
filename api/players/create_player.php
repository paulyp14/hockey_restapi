<?php


// required headers
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

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->first_name) &&
    !empty($data->last_name) &&
    !empty($data->birth_day) &&
    !empty($data->birth_month) &&
    !empty($data->birth_year)
) {
    // create the leagues
    if($player->insert_new_post($data)){

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Player was created."));
    }

    // if unable to create the product, tell the user
    else{

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create player."));
    }
}
else{
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create player. Data is incomplete."));
}