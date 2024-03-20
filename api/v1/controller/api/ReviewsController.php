<?php

class ReviewsController extends BaseController{

    //create new review
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
                "message" => "User does not exist, cannot add review to product"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }



        //check if the product exist
        $productModel = new ProductModel();
        $checkExist = $productModel->checkProduct($data->product_id);

        if ($checkExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "Product does not exist"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        } else{

            //validations
            $vali = new ReviewsValidation();
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

            
        

            if (is_string($data->rating) || ($data->rating < 1 || $data->rating > 5)){
                //data does not exist
                $response = [
                    "status" => "error",
                    "message" => "Invalid Rating, 1-5 Star rating only"
                ];
    
                $responseData = json_encode($response);
    
                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
    
            }


            
            $reviewsModel = new ReviewsModel();

            //check for duplicate
            $msg = addslashes($data->message);
            $checkRevExist = $reviewsModel->checkReview($data->user_id, $data->product_id, $msg);

            if ($checkRevExist > 0){
                //data does not exist
                $response = [
                    "status" => "error",
                    "message" => "Review already added to this product"
                ];

                $responseData = json_encode($response);

                $this->sendOutput(
                    $responseData,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );

            }


            //insert data
            $arrSubs = $reviewsModel->insertReview($data);

            $responseData = json_encode($arrSubs);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 Created')
            );

            

        }
        
    }





    //retrieve product review list
    public function list($product_id){

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

        $reviewModel = new ReviewsModel();
        $response = $reviewModel->listProductReviews($product_id);

        if (is_string($response['data'])){
            $response['data']=[];
        }

        $response['message'] = "All product reviews";
        $response['total'] = count($response['data']);
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }






    //retrieve product review summary
    public function rating_summary($product_id){

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


        if (!is_numeric($product_id)){
            $response = [
                "status" => "error",
                "message" => "Product detail is not valid"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

            exit();
        }



        //check product
        $productModel = new ProductModel();
        $checkProductExist = $productModel->checkProduct($product_id);
        echo $product_id;

        if ($checkProductExist < 1){
            //data does not exist
            $response = [
                "status" => "error",
                "message" => "Product does not exist, rating not found"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

            exit();

        }




        $reviewModel = new ReviewsModel();
        $response_ = $reviewModel->listRating($product_id);
        $overall_rating_q = $reviewModel->listOverallRating($product_id);

        //rating
        $star_1 = '0';
        $star_2 = '0';
        $star_3 = '0';
        $star_4 = '0';
        $star_5 = '0';

        if (is_string($response_['data'])){
            $response_['data']=[];
        } else{
            //returns an array
            for($i=0; $i<count($response_['data']); $i++){
                if ($response_['data'][$i]['rating'] == 1){
                    $star_1 = $response_['data'][$i]['count'];
                } else if ($response_['data'][$i]['rating'] == 2){
                    $star_2 = $response_['data'][$i]['count'];
                } else if ($response_['data'][$i]['rating'] == 3){
                    $star_3 = $response_['data'][$i]['count'];
                } else if ($response_['data'][$i]['rating'] == 4){
                    $star_4 = $response_['data'][$i]['count'];
                } else if ($response_['data'][$i]['rating'] == 5){
                    $star_5 = $response_['data'][$i]['count'];
                }
            }
        }

        $overall_rating = $overall_rating_q['data'][0]['r_sum'] / $overall_rating_q['data'][0]['r_count'];

        $response['message'] = "All product reviews summary";
        $response['data'] = [
            "overall_rating" => $overall_rating,
            "rating" => [
                "star_1" => $star_1,
                "star_2" => $star_2,
                "star_3" => $star_3,
                "star_4" => $star_4,
                "star_5" => $star_5,
            ]
        ];
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }







    //remove reviews
    public function delete($review_id, $user_id){

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
                "message" => "User does not exist, cannot delete review"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }




        $reviewModel = new ReviewsModel();

        //check if favorite exist
        $checkRevExist = $reviewModel->checkUserReview($user_id, $review_id);

        if ($checkRevExist < 1){
            //data already exist
            $response = [
                "status" => "error",
                "message" => "This user cannot delete this review"
            ];

            $responseData = json_encode($response);

            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );

        }

        $response = $reviewModel->removeUserReview($user_id, $review_id);

        $response['message'] = "Removed user review";
        $responseData = json_encode($response);

        $this->sendOutput(
            $responseData,
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
        
    }
}
