<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sellers extends MX_Controller
{
    
    function __construct() {
        parent:: __construct();
 
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400'); // cache for 1 day
        }
    
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
            exit(0);
        }
        $this->load->model("sellers_model");
        $this->load->model("kaizalas_model");
}
//function to load the brands
     function create_seller(){
            //receive json post
            $json_string = file_get_contents("php://input");
            //convert json to array
            $json_object = json_decode($json_string);
        
            //validate
            if(is_array($json_object) && (count($json_object)> 0)){
                //retieve data
                $row = $json_object[0];
                $data = array(
                    "seller_name" =>$row->sellername,
                    "Date" =>$row->date,
                    "phone_number	" =>$row->phonenumber,
                    "Transmission" =>$row->transmission,
                    "Price" =>$row->price,
                    "Model" =>$row->model,
                    "Brand" =>$row->brand,
                    "car_image	" =>$row->carimage
                );
                //request to submit /request to save data
               $saving =  $this->sellers_model->save_sellerdetails($data);
               $subscriers = array($row->phonenumber);
               
               if($saving == TRUE){
                   //send a confirmation
                   //
                   $messagetitle = "successful";
                   $mesage_description = "thanks for ".$row->sellername;

                   // echo "succesful";
               }else{
                   //echo "didnt work";
                   $messagetitle = "not successful";
                   $mesage_description = " Sorry ".$row->sellername ."tryagain";
               }
               $this->kaizalas_model-> send_announcement($messagetitle, $mesage_description, $subscriers);
            }
            else {
                    echo " Invalid data/error occured somewhere";
            }
        
    }
}
?>