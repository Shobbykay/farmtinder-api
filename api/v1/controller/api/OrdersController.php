<?php

class OrdersController extends BaseController{

    //add new order
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
                "message" => "User does not exist, cannot create order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        //check delivery
        $deliveryModel = new DeliveryModel();
        $checkDelExist = $deliveryModel->checkUserDelivery($data->user_id, $data->delivery_id);

        if ($checkDelExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "This delivery does not exist, cannot create order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //check if cart exist
        $cartModel = new CartModel();
        //check for empty cart
        if (empty($data->cart)){
            $response = [
                "status" => "error",
                "message" => "Cart caanot be empty, unable to create order"
            ];
    
            $responseData = json_encode($response);
    
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }

        $checkExist = $cartModel->checkAllCart($data->user_id, $data->cart);
        $products = explode(",", $data->cart);

        if ($checkExist != count($products)){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Cart items does not match"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new OrdersValidation();
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


            $orderModel = new OrderModel();
            $order_id = time() . mt_rand() .mt_rand(1000,9999);

            //get cart sub-total
            $subTotal = $orderModel->getSubTotal($data->user_id);
            $vat = 0.05 * $subTotal;
            $total = $subTotal + $vat;

            //inserts
            $arrSubs = $orderModel->insertOrder($data, $order_id);
            $ins = $orderModel->insertOrderSummary($data, $order_id, $subTotal, $vat, $total);

            //delete from cart table
            $arrD = $orderModel->removeProductCart($data->user_id, $data->cart);

            
            
            $arrSubs['message'] = 'Order created successfully';


            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

        }
        
    }





    //complete order
    public function complete(){

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
                "message" => "User does not exist, cannot complete order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //check if order exist
        $orderModel = new OrderModel();
        $checkOrderExist = $orderModel->checkOrder($data->order_id);

        if ($checkOrderExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Order does not exist, cannot complete order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        //validations
        $vali = new OrdersValidation();
        $val = $vali->validateOrderComplete($data);

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

        // //inserts
        $arrSubs = $orderModel->completeOrder($data);
        $arrSubs['message'] = 'Order completed successfully';

        $responseData = json_encode($arrSubs);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 Created')
        );
        
    }




    //cancel order
    public function cancel(){

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
                "message" => "User does not exist, cannot complete order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //check if order exist
        $orderModel = new OrderModel();
        $checkOrderExist = $orderModel->checkOrder($data->order_id);

        if ($checkOrderExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Order does not exist, cannot complete order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        //validations
        $vali = new OrdersValidation();
        $val = $vali->validateOrderComplete($data);

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


        
        //check if order is completed
        $checkComExist = $orderModel->checkOrderStatus($data->order_id);

        if ($checkComExist > 0){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Cannot cancel a completed order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        // //inserts
        $arrSubs = $orderModel->cancelOrder($data);
        $arrSubs['message'] = 'Order canceled successfully';

        $responseData = json_encode($arrSubs);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 Created')
        );
        
    }





    //retrieve user orders
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
                "message" => "User does not exist, cannot retrieve orders"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        }

        $orderModel = new OrderModel();
        $response = $orderModel->viewUserOrders($user_id);

        $response['message'] = "View user orders";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }





    //retrieve user orders
    public function view($order_id){

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

        //check if order exist
        $orderModel = new OrderModel();
        $checkOrderExist = $orderModel->checkOrder($order_id);

        if ($checkOrderExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Order does not exist, cannot retrieve order"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }


        //models
        $orderModel = new OrderModel();
        $deliveryModel = new DeliveryModel();
        $userModel = new UserModel();

        //build response
        $response = [];
        $response['status'] = "status";
        $response['message'] = "View single order";
        $response['data'] = [];

        //retrieve data
        $summary = $orderModel->viewSingleOrder($order_id);
        $delivery_id = $summary['data'][0]['delivery_id'];
        $delivery = $deliveryModel->singleDelivery($delivery_id);
        $user_id = $summary['data'][0]['user_id'];
        $user = $userModel->singleUser($user_id);
        $product = $orderModel->viewOrderProducts($order_id);
        

        //set response
        $response['data']['customer'] = $user['data'][0];
        $response['data']['products'] = $product['data'];
        $response['data']['summary'] = $summary['data'][0];
        $response['data']['delivery'] = $delivery['data'][0];

        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }


}
