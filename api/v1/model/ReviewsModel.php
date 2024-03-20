<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class ReviewsModel extends Database{

    public function insertReview($data){

        $user_id = $data->user_id;//user_id or buyer's id
        $product_id = $data->product_id;
        $rating = $data->rating;
        $message = addslashes($data->message);
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO reviews(user_id, product_id, rating, message, created_date) VALUES ('$user_id', '$product_id', '$rating', '$message', '$date')");

    }

    public function checkReview($user_id, $product_id, $message){
        return $this->count("SELECT COUNT(1) count FROM reviews WHERE user_id='$user_id' AND product_id='$product_id' AND message='$message'");
    }


    public function listProductReviews($product_id){
        return $this->list("SELECT a.id, CONCAT(b.first_name, ' ', b.last_name) customer_name, b.profile_pic, a.rating, a.message, a.created_date FROM reviews a LEFT JOIN users b ON a.user_id=b.id WHERE a.product_id='$product_id'");
    }


    public function listOverallRating($product_id){
        return $this->list("SELECT SUM(rating) r_sum, COUNT(1) r_count FROM reviews WHERE product_id='$product_id'");
    }



    public function listRating($product_id){
        return $this->list("SELECT rating, COUNT(1) count FROM `reviews` WHERE product_id='$product_id' GROUP BY rating");
    }



    public function checkUserReview($user_id, $review_id){
        return $this->count("SELECT COUNT(1) count FROM reviews WHERE user_id='$user_id' AND id='$review_id'");
    }



    public function removeUserReview($user_id, $review_id){
        return $this->query("DELETE FROM reviews WHERE user_id='$user_id' AND id='$review_id'");
    }


}