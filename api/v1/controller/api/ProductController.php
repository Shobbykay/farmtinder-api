<?php

class ProductController extends BaseController{

    //create new product
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



        //check if measurement exist
        $measurementModel = new MeasurementModel();
        $checkMeasurementExist = $measurementModel->checkMeasurement($data->measurement);

        if ($checkMeasurementExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Measurement does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }






        //check if category exist
        $categoryModel = new CategoryModel();
        $checkCategoryExist = $categoryModel->checkCategory($data->category);

        if ($checkCategoryExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Category does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }




        

        //check if user exist
        $userModel = new UserModel();
        $checkUserExist = $userModel->checkUser($data->seller_id);

        if ($checkUserExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "User does not exist, cannot add product to store"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }



        //check if the store belongs to the user/seller
        $storeModel = new StoreModel();
        $checkExist = $storeModel->checkStoreId($data->store_id, $data->seller_id);

        if ($checkExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Store does not exist for this seller"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new ProductValidation();
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

            
            $productModel = new ProductModel();

            //check if product already exist in the same store
            $checkProductExist = $productModel->checkProductInStore($data->store_id, $data->name);

            if ($checkProductExist > 0){
                //data does not exist
                $response = [
                    "status" => "error",
                    "message" => "Product already exist in this store"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            }


            //insert data
            $arrSubs = $productModel->insertProduct($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

            

        }
        
    }



    //retrieve products list
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

        $productModel = new ProductModel();
        $response = $productModel->listProducts();

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All products";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }



    //retrieve single products
    public function view($product_id){

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

        $productModel = new ProductModel();
        $response = $productModel->singleProduct($product_id);

        $response['message'] = "View single product";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
}
