<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class CategoryModel extends Database{

    public function insertCategory($data){

        $name = $data->name;
        $description = addslashes($data->description);
        $image = $data->image;
        $active_image = $data->active_image;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO category(name, description, image, active_image, created_date) VALUES ('$name', '$description', '$image', '$active_image', '$date')");

    }

    public function checkCategory($name){
        return $this->count("SELECT COUNT(1) count FROM category WHERE name='$name' OR id='$name'");
    }


    public function listCategory(){
        return $this->list("SELECT * FROM category");
    }


    public function singleCategory($id){
        return $this->list("SELECT * FROM category WHERE id='$id'");
    }

}
