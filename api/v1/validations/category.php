<?php

class CategoryValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else if (!isset($obj->image) || empty($obj->image)){
            return 'One of more fields are missing';

        }  else if (!isset($obj->active_image) || empty($obj->active_image)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}