<?php

class StoreValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->contact_phone) || empty($obj->contact_phone)){
            return 'One of more fields are missing';

        } else if (!isset($obj->store_tag) || empty($obj->store_tag)){
            return 'One of more fields are missing';

        } else if (!isset($obj->address) || empty($obj->address)){
            return 'One of more fields are missing';

        } else if (!isset($obj->description) || empty($obj->description)){
            return 'One of more fields are missing';

        } else if (!isset($obj->store_logo) || empty($obj->store_logo)){
            return 'One of more fields are missing';

        } else if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}
