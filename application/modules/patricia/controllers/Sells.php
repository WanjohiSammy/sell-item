<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sells extends MX_Controller
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
        
        $this->load->model("sells_model");
        $this->load->model("kaizala_model");
    }

    function create_sell(){
        //receive JSON POST
        $json_string = file_get_contents("php://input");
        //2.convert JSON to an array
        $json_object =json_decode($json_string);
        




        //3.validate
        if(is_array($json_object) && (count($json_object)>0)){
            //retrieve data
            $row=$json_object[0];
            $data =array(
                "Brand_name" =>$row->Brand,
                "Brand_model_name"=>$row->model,
                "price"=>$row->price,
                "color"=>$row->color,
                "profile_image"=>$row->picture,
                "responder_name"=>$row->name,
                "responder_phone"=>$row->phone,
                "response_time"=>$row->time,

            );
            //4.Request to submit
           $save_status= $this->sells_model->save_sell($data);
           $subcribers =array($row->phone);

           if($save_status ==TRUE){
             $message_title ="Sells Successfull";
             $message_description ="Thankyou ".$row->name.".for checking in";


           }else{
             
            $message_title="Sells Failure";
            $message_description="dint login".$row->name.".try again";
           }
           $this->kaizala_model->send_announcement($message_title,
           $message_description,$subcribers);

        }
        else{
            //send invalid data message
            echo"invalid data provided";
        }
        //4.Request to save data
        //5.send a confirmation
    }
}
    
?>