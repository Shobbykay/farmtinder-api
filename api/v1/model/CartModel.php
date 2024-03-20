<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CartModel extends Database{

    public function insertCart($data){

        $user_id = $data->user_id;
        $product_id = $data->product_id;
        $qty = $data->qty;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO cart(user_id, product_id, qty, created_date) VALUES ('$user_id','$product_id','$qty','$date')");

    }

    public function checkUserCart($user_id){
        //user_id
        return $this->count("SELECT COUNT(1) count FROM cart WHERE user_id='$user_id'");
    }


    //update cart
    public function updateCart($data){
        $user_id = $data->user_id;
        $product_id = $data->product_id;
        $qty = $data->qty;
        $date = Date('Y-m-d h:i:s');

        return $this->query("UPDATE cart SET qty='$qty',updated_date='$date' WHERE user_id='$user_id' AND product_id='$product_id'");
    }


    //delete cart
    public function clearCart($user_id){
        return $this->query("DELETE FROM cart WHERE user_id='$user_id'");
    }


    //clear cart
    public function removeProductCart($user_id, $product_id){
        return $this->query("DELETE FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
    }


    //view user cart
    public function viewUserCart($user_id){
        return $this->list("SELECT b.name product_name, b.thumbnail, b.unit_price, a.qty, a.created_date, a.updated_date FROM cart a
        LEFT JOIN products b ON a.product_id=b.id
        WHERE a.user_id='$user_id'");
    }

    public function checkCart($user_id, $product_id){
        return $this->count("SELECT COUNT(1) count FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
    }

    public function checkAllCart($user_id, $product_ids){
        // echo "SELECT COUNT(1) count FROM cart WHERE user_id='$user_id' AND product_id IN ($product_ids)";
        return $this->count("SELECT COUNT(1) count FROM cart WHERE user_id='$user_id' AND product_id IN ($product_ids)");
    }

}
