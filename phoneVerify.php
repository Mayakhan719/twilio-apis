<?php

// debuger
ini_set("display_errors",1);
// Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type:application/json; charst= UTF-8");

// file include
include_once("../config/database.php");
include_once("../classes/Phone.php");
include_once("../Labraries/PHPtwallio/autoload.php");
use Twilio\Rest\Client;


// object
$db= new Database();
$connection=$db->connect();

$user_obj = new Phone($connection);
if($_SERVER['REQUEST_METHOD'] === "POST"){
    $data =json_decode(file_get_contents("php://input"));
    if (!empty($data->user_id) && !empty($data->country_code) && !empty($data->number)) {
    $user_obj->user_id=$data->user_id;
    $user_obj->country_code=$data->country_code;
    $user_obj->number=$data->number;
    $user_obj->code = random_int(1000, 9999);

    if(!empty($row=$user_obj->searchById())){
       // Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACbda72e484905fdb05fbf0728c6b94e9d';
$auth_token = '4d465f22e9cd248cc6ebe988d103d5fd';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["RzMKSPH51tJe_IgtzfM0_Hb5Cc0F-OQS3zhC4y1t"];
// A Twilio number you own with SMS capabilities
$twilio_number = "+17122141201";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    "$user_obj->country_code"."$user_obj->number",
    array(
        'from' => $twilio_number,
        'body' => 'Your verification number is:'.$user_obj->code
    )
);

        // Storing Code
        if ($user_obj->code()) {
            # code...
            http_response_code(200);
            echo json_encode(array(
                "status"=>True,
                "number"=>$user_obj->country_code.$user_obj->number,
                "message"=>"Your verification Sent to $user_obj->country_code$user_obj->number, Please Check Your Phone ($user_obj->code)"
            ));

        }
    }else{
        http_response_code(200);
        echo json_encode(array(
            "status"=>false,
            "message"=>"user id not registered"
        ));
    }

    }else{
    http_response_code(503);
    echo json_encode(array(
    "status"=>false,
    "message"=>"All Data Needed"
));
    }

}else{
    http_response_code(503);
    echo json_encode(array(
    "status"=>false,
    "message"=>"Server error"
));
    }
?>