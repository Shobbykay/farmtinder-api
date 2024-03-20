<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class DeliveryModel extends Database{

    public function insertDelivery($data){

        $user_id = $data->user_id;//user_id or seller's id
        $delivery_title = $data->delivery_title;
        $phone_number = $data->phone_number;
        $street_address = addslashes($data->street_address);
        $city = $data->city;
        $state = $data->state;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO delivery(user_id, delivery_title, phone_number, street_address, city, state, created_date) VALUES ('$user_id','$delivery_title','$phone_number','$street_address','$city','$state','$date')");

    }

    public function checkDelivery($delivery_title, $user_id){
        return $this->count("SELECT COUNT(1) count FROM delivery WHERE delivery_title='$delivery_title' AND user_id='$user_id'");
    }

    // public function checkStoreId($store_id, $seller_id){
    //     return $this->count("SELECT COUNT(1) count FROM store WHERE id='$store_id' AND seller_id='$seller_id'");
    // }


    public function listUserDelivery($user_id){
        return $this->list("SELECT * FROM delivery WHERE user_id='$user_id'");
    }


    public function singleDelivery($id){
        return $this->list("SELECT * FROM delivery WHERE id='$id'");
    }


    public function checkUserDelivery($user_id, $delivery_id){
        return $this->count("SELECT COUNT(1) count FROM delivery WHERE user_id='$user_id' AND id='$delivery_id'");
    }


    
    public function removeUserDelivery($user_id, $delivery_id){
        return $this->query("DELETE FROM delivery WHERE user_id='$user_id' AND id='$delivery_id'");
    }

}