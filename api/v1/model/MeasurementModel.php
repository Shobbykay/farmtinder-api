<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class MeasurementModel extends Database{

    public function insertMeasurement($data){

        $name = $data->name;
        $unit = $data->unit;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO measurement(name, unit, created_date) VALUES ('$name', '$unit', '$date')");

    }

    public function checkMeasurement($name){
        return $this->count("SELECT COUNT(1) count FROM measurement WHERE name='$name' OR id='$name'");
    }


    public function listMeasurement(){
        return $this->list("SELECT * FROM measurement");
    }


    public function singleMeasurement($id){
        return $this->list("SELECT * FROM measurement WHERE id='$id'");
    }

}
