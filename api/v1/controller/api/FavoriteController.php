<?php

class FavoriteController extends BaseController{

    //create new user/buyer's favorite product
    public function add(){

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
                "message" => "User does not exist, cannot add wishlist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //check if product exist
        $productModel = new ProductModel();
        $checkProductExist = $productModel->checkProduct($data->product_id);

        if ($checkProductExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Product does not exist, cannot add wishlist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new FavoriteValidation();
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

            
            $favModel = new FavoriteModel();

            //check if favorite exist
            $checkFavExist = $favModel->checkFavorite($data->user_id, $data->product_id);

            if ($checkFavExist > 0){
                //data already exist
                $response = [
                    "status" => "error",
                    "message" => "Already added to wishlist"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }

            //insert data
            $arrSubs = $favModel->insertFavorite($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }





    //retrieve users favorites list
    public function user_list($user_id){

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

        $favoriteModel = new FavoriteModel();
        $response = $favoriteModel->listUserFavorite($user_id);

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All user favorites";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }





    //remove favorite
    public function delete($user_id, $product_id){

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

        $favoriteModel = new FavoriteModel();

        //check if favorite exist
        $checkFavExist = $favoriteModel->checkFavorite($user_id, $product_id);

        if ($checkFavExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "This product does not exist in favorites"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        $response = $favoriteModel->removeUserFavorite($user_id, $product_id);

        $response['message'] = "Removed user favorite";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }


}
