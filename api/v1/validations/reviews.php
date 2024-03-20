<?php

class ReviewsValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->product_id) || empty($obj->product_id)){
            return 'One of more fields are missing';

        } else if (!isset($obj->rating) || empty($obj->rating)){
            return 'One of more fields are missing';

        } else if (!isset($obj->message) || empty($obj->message)){
            return 'One of more fields are missing';

        } else if (!isset($obj->user_id) || empty($obj->user_id)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}
