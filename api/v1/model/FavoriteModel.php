<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class FavoriteModel extends Database{

    public function insertFavorite($data){

        $user_id = $data->user_id;//user_id or buyer's id
        $product_id = $data->product_id;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO buyer_favourite(buyer_id, product_id, created_date) VALUES ('$user_id', '$product_id','$date')");

    }

    public function checkFavorite($user_id, $product_id){
        return $this->count("SELECT COUNT(1) count FROM buyer_favourite WHERE buyer_id='$user_id' AND product_id='$product_id'");
    }


    public function listUserFavorite($user_id){
        return $this->list("SELECT b.name product_name, b.thumbnail, c.name category, b.unit_price, a.created_date FROM buyer_favourite a
        LEFT JOIN products b ON b.id=a.product_id
        LEFT JOIN category c ON b.category=c.id
        WHERE a.buyer_id='$user_id'");
    }



    public function removeUserFavorite($user_id, $product_id){
        return $this->query("DELETE FROM buyer_favourite WHERE buyer_id='$user_id' AND product_id='$product_id'");
    }


}