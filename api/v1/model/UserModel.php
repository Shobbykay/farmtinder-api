<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class UserModel extends Database{

    public function insertUser($data){

        $id = $data->id;
        $email = $data->email;
        $first_name = $data->first_name;
        $last_name = $data->last_name;
        $phone = $data->phone;
        $profile_pic = $data->profile_pic;
        $gender = $data->gender;
        $account_number = $data->account_number;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO users(id, email, first_name, last_name, phone, profile_pic, gender, account_number, created_date) VALUES ('$id', '$email','$first_name','$last_name','$phone','$profile_pic','$gender','$account_number','$date')");

    }

    public function checkUser($email){
        //email or user_id
        return $this->count("SELECT COUNT(1) count FROM users WHERE email='$email' OR id='$email'");
    }


    public function checkEligibility($email){
        //email or user_id
        $check_users_tb = $this->count("SELECT COUNT(1) count FROM users WHERE email='$email' OR id='$email'");

        $eligibility_status = '';
        $response = [
            "status" => "error",
        ];
        
        if ($check_users_tb > 0){
            //user exist
            //check if user performed any operation
            $check_users_store = $this->count("SELECT COUNT(1) count FROM store WHERE seller_id='$email'");

            if ($check_users_store > 0){
                //seller
                //check if he has products
                $check_users_products = $this->count("SELECT COUNT(1) count FROM products WHERE seller_id='$email'");

                if ($check_users_products > 0){
                    $response['status'] = "success";
                    $eligibility_status = "USER_IS_ELIGIBILE";
                } else{
                    $eligibility_status = "USER_HAS_STORE_WITH_NO_PRODUCTS";
                }

            } else{
                //buyer
                //check if user ever bought any product
                $check_users_order = $this->count("SELECT COUNT(1) count FROM orders WHERE user_id='$email'");

                if ($check_users_order > 0) {
                    $response['status'] = "success";
                    $eligibility_status = "USER_IS_ELIGIBILE";

                } else{
                    $eligibility_status = "USER_NEVER_BOUGHT_ANY_PRODUCT";

                }
            }

        } else{
            //user never attempted FarmTinder
            $eligibility_status = "USER_NEVER_USED_FARMTINDER";
        }

        $response['message'] = "View loan eligibility";
        $response['code'] = $eligibility_status;

        return $response;
    }


    public function listUsers(){
        return $this->list("SELECT * FROM users");
    }


    public function singleUser($id){
        return $this->list("SELECT * FROM users WHERE id='$id'");
    }

}
