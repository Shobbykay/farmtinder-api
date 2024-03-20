<?php

class UserController extends BaseController{

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

        $userModel = new UserModel();
        $checkExist = $userModel->checkUser($data->identity);

        if ($checkExist > 0){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "User already exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new UserValidation();
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

            //call TAO User Auth Service
            $curl = curl_init();

            $login_data = [
                "identity" => $data->identity,
                "password" => $data->password
            ];

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://auth.boatafrica.com/api/v1/user/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($login_data, true),
            CURLOPT_HTTPHEADER => array(
                'X-CLIENT-ID: 296dca15-6c49-4757-b499-f1ad072425dd',
                'X-AUTH-SIGNATURE: j4rfqutkZY+vcMUD6szso0LTiCdkyUukq1m7kpM59VV1rLSv3uc7tDufA5ThTFVPVIlzH38RGKKDpDk7dVA3dw==',
                'Content-Type: application/json'
            ),
            ));

            $response = curl_exec($curl);

            // convert json => object
            $obj = json_decode($response);

            curl_close($curl);

            //insert data
            $arrSubs = $userModel->insertUser($obj->data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

            

        }
        
    }



    //retrieve users list
    public function list(){

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

        $userModel = new UserModel();
        $response = $userModel->listUsers();

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All users";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }



    //retrieve single users
    public function view($user_id){

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

        $userModel = new UserModel();
        $response = $userModel->singleUser($user_id);

        $response['message'] = "View single user";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }






    //loan eligibility
    public function loan_eligibility($user_id){
        /**
         * 
         * Eligibility Criteria:
         * 
         * 1. Check if user exist
         * 2. Check if user has a store 
         * 3. Check if user has purchased anything
         * 
         */

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

        $userModel = new UserModel();
        $response = $userModel->checkEligibility($user_id);

        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );

    }
}
