<?php

class DeliveryController extends BaseController{

    //create new user
    public function create(){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'POST') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );

            exit();
        }

        //create the data
        $json = file_get_contents('php://input');

        // Converts it into a PHP object
        $data = json_decode($json);

        //check if user exist
        $userModel = new UserModel();
        $checkUserExist = $userModel->checkUser($data->user_id);

        if ($checkUserExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist, cannot create store"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        $deliveryModel = new DeliveryModel();
        $checkExist = $deliveryModel->checkDelivery($data->delivery_title, $data->user_id);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Delivery already exist for this buyer"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new DeliveryValidation();
            $val = $vali->validate($data);

            if ($val !== ''){
                $response = [
                    "status" => "error",
                    "message" => $val
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }

            //insert data
            $arrSubs = $deliveryModel->insertDelivery($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

            

        }
        
    }



    //retrieve users delivery list
    public function list($user_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        //check if user exist
        $userModel = new UserModel();
        $checkUserExist = $userModel->checkUser($user_id);

        if ($checkUserExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        $deliveryModel = new DeliveryModel();
        $response = $deliveryModel->listUserDelivery($user_id);

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "Single user delivery";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }





    // //retrieve single users
    public function view($delivery_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'GET') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }

        $deliveryModel = new DeliveryModel();
        $response = $deliveryModel->singleDelivery($delivery_id);

        $response['message'] = "View single delivery";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }










    //remove reviews
    public function delete($user_id, $delivery_id){

        $requestMethod = $_SERVER["REQUEST_METHOD"];
        if (strtoupper($requestMethod) !== 'DELETE') {
            $response = [
                "status" => "error",
                "message" => "This route does not support this REQUEST METHOD"
            ];

            $strErrorHeader = 'HTTP/1.1 400 Bad Request';

            $this->sendOutput(
                json_encode($response), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }


        //check if user exist
        $userModel = new UserModel();
        $checkUserExist = $userModel->checkUser($user_id);

        if ($checkUserExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist, cannot delete delivery"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }




        $deliveryModel = new DeliveryModel();

        //check if favorite exist
        $checkDelExist = $deliveryModel->checkUserDelivery($user_id, $delivery_id);

        if ($checkDelExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "This user cannot delete this delivery"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        $response = $deliveryModel->removeUserDelivery($user_id, $delivery_id);

        $response['message'] = "Removed user delivery";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }



}
