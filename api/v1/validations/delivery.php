<?php

class DeliveryValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->delivery_title) || empty($obj->delivery_title)){
            return 'One of more fields are missing';

        } else if (!isset($obj->phone_number) || empty($obj->phone_number)){
            return 'One of more fields are missing';

        } else if (!isset($obj->street_address) || empty($obj->street_address)){
            return 'One of more fields are missing';

        } else if (!isset($obj->city) || empty($obj->city)){
            return 'One of more fields are missing';

        } else if (!isset($obj->state) || empty($obj->state)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}
