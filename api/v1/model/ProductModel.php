<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class ProductModel extends Database{

    public function insertProduct($data){
        //set a thumbnail image
        $thumb = explode(",", $data->images);
        $thumbnail = $thumb[0];

        $seller_id = $data->seller_id;
        $store_id = $data->store_id;
        $name = $data->name;
        $category = $data->category;
        $qty = $data->qty;
        $unit_price = $data->unit_price;
        $measurement = $data->measurement;
        $description = addslashes($data->description);
        $images = $data->images;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO products(name, category, thumbnail, description, unit_price, measurement, images, seller_id, store_id, status, created_date) VALUES ('$name','$category','$thumbnail','$description','$unit_price','$measurement','$images','$seller_id','$store_id','ACTIVE','$date')");

    }


    public function listStoreProducts($store_id){
        return $this->list("SELECT * FROM products WHERE store_id='$store_id' AND status='ACTIVE'");
    }


    public function listProducts(){
        return $this->list("SELECT * FROM products");
    }


    public function singleProduct($id){
        return $this->list("SELECT * FROM products WHERE id='$id'");
    }


    public function checkProductInStore($store_id, $product_name){
        return $this->count("SELECT COUNT(1) count FROM products WHERE store_id='$store_id' AND name='$product_name'");
    }


    public function checkProduct($product_id){
        return $this->count("SELECT COUNT(1) count FROM products WHERE id='$product_id'");
    }

}