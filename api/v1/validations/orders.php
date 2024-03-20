<?php

class OrdersValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->delivery_id) || empty($obj->delivery_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->cart) || empty($obj->cart)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

    public function validateOrderComplete($obj){
        //receive data

        if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->order_id) || empty($obj->order_id)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}