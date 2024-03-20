<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class StoreModel extends Database{

    public function insertStore($data){

        $id = $data->user_id;//user_id or seller's id
        $name = $data->name;
        $phone = $data->contact_phone;
        $store_tag = $data->store_tag;
        $address = addslashes($data->address);
        $description = addslashes($data->description);
        $logo = $data->store_logo;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO store(seller_id, name, contact_phone, tag, address, description, logo, created_date) VALUES ('$id', '$name','$phone','$store_tag','$address','$description','$logo','$date')");

    }

    public function checkStore($store_name, $seller_id){
        return $this->count("SELECT COUNT(1) count FROM store WHERE name='$store_name' AND seller_id='$seller_id'");
    }

    public function checkStoreId($store_id, $seller_id){
        return $this->count("SELECT COUNT(1) count FROM store WHERE id='$store_id' AND seller_id='$seller_id'");
    }


    public function listStores(){
        return $this->list("SELECT * FROM store");
    }


    public function singleStore($id){
        return $this->list("SELECT * FROM store WHERE id='$id'");
    }


    public function viewStoreProduct($store_id){
        return $this->list("SELECT * FROM products WHERE store_id='$store_id'");
    }

}