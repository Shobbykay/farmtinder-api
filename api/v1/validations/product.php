<?php

class ProductValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->seller_id) || empty($obj->seller_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->store_id) || empty($obj->store_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->category) || empty($obj->category)){
            return 'One of more fields are missing';

        } else if (!isset($obj->qty) || empty($obj->qty)){
            return 'One of more fields are missing';

        } else if (!isset($obj->unit_price) || empty($obj->unit_price)){
            return 'One of more fields are missing';

        } else if (!isset($obj->measurement) || empty($obj->measurement)){
            return 'One of more fields are missing';

        } else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else if (!isset($obj->images) || empty($obj->images)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}