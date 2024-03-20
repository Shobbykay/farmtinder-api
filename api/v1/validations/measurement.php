<?php

class MeasurementValidation{

    public function validate($obj){
        //receive data

        if (!isset($obj->name) || empty($obj->name)){
            return 'One of more fields are missing';

        } else if (!isset($obj->unit) || empty($obj->unit)){
            return 'One of more fields are missing';

        } else{
            return '';
            
        }
    }

}