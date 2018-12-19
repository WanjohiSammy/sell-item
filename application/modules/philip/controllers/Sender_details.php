<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sender_details extends MX_Controller
{
    function __construct() {
		parent:: __construct();

		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
	
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
        }
        
        $this->load->model("sender_details_model");
    }

    public function create_sender(){
        // 1. Receive json post
        $json_string = file_get_contents("php://input");
        // 2. convert json to array
        $json_object = json_decode($json_string);
        // 3. validate
        if(is_array($json_object) && (count($json_object) > 0)){
            // Retreive the data
                $row = $json_object[0];
                $data = array(
                    "responder_name" => $row-> name,
                    "responder_phone" => $row-> phone,
                    "response_time" => $row-> time,
                    "brand_name" => $row-> brand,
                    "brand_model" => $row-> model,
                    "transmission_type" => $row-> transmission,
                    "car_image" => $row-> picture,
                    "price" => $row-> money,
                );

            // 4. Request to submit
            $save_status = $this->sender_details_model ->save_sender($data);

            if($save_status ==TRUE){
                echo "saved";
            }
            else{
                echo "unable to save";
            }

        }
        else{
            // send invalid data message
            echo "invalid data provided";

        }
        // 4. request to save data
        // 5. send confirmation
    }
   
}
?>