<?php

class CartController extends BaseController{

    //add new product to cart
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
                "message" => "User does not exist, cannot add to cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //check if product exist
        $productModel = new ProductModel();
        $checkExist = $productModel->checkProduct($data->product_id);

        if ($checkExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Product does not exist, cannot add to cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new CartValidation();
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


            $cartModel = new CartModel();
            $arrSubs = '';

            //check data (for update)
            $checkCartExist = $cartModel->checkCart($data->user_id, $data->product_id);
            if ($checkCartExist < 1){
                //new data
                //insert data
                $arrSubs = $cartModel->insertCart($data);

            } else{
                //update data
                $arrSubs = $cartModel->updateCart($data);
                $arrSubs['message'] = 'Product updated in cart successfully';

            }

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }





    //retrieve user cart
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
                "message" => "User does not exist, cannot add to cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        $cartModel = new CartModel();
        $response = $cartModel->viewUserCart($user_id);

        $response['message'] = "View user cart";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }











    //remove product from user cart
    public function remove($user_id, $product_id){

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
                "message" => "User does not exist, cannot add to cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        $cartModel = new CartModel();

        //check data (for update)
        $checkCartExist = $cartModel->checkCart($user_id, $product_id);
        if ($checkCartExist < 1){
            $response = [
                "status" => "error",
                "message" => "Product does not exist in user cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //remove a product item
        $response = $cartModel->removeProductCart($user_id, $product_id);


        $response['message'] = "Product removed from cart";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
















    //remove product from user cart
    public function clear($user_id){

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
                "message" => "User does not exist, cannot clear cart"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        $cartModel = new CartModel();

        //check data (for update)
        $checkCartExist = $cartModel->checkUserCart($user_id);
        if ($checkCartExist < 1){
            $response = [
                "status" => "error",
                "message" => "Cart is empty for user"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //clear cart
        $response = $cartModel->clearCart($user_id);


        $response['message'] = "Cart has been cleared successfully";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
}
