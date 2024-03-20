<?php

require_once PROJECT_ROOT_PATH . "/model/database.php";

class OrderModel extends Database{

    public function insertOrder($data, $order_id){

        $user_id = $data->user_id;
        $cart = $data->cart;
        $delivery_id = $data->delivery_id;
        
        return $this->query("INSERT INTO orders(order_id, user_id, product_id, qty) SELECT '$order_id', user_id, product_id, qty FROM cart WHERE product_id IN ('$cart')");

    }

    public function insertOrderSummary($data, $order_id, $subtotal, $vat, $total){

        $user_id = $data->user_id;
        $cart = $data->cart;
        $delivery_id = $data->delivery_id;
        $date = Date('Y-m-d h:i:s');
        
        return $this->insert("INSERT INTO order_summary(order_id, user_id, delivery_id, subtotal, vat, total, created_date) VALUES ('$order_id','$user_id','$delivery_id','$subtotal','$vat','$total','$date')");

    }

    public function getSubTotal($user_id){
        return $this->count("SELECT SUM((b.unit_price * a.qty)) count FROM cart a LEFT JOIN products b ON a.product_id=b.id WHERE a.user_id='$user_id'");
    }


    //complete order
    public function completeOrder($data){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        return $this->query("UPDATE order_summary SET status='COMPLETED' WHERE user_id='$user_id' AND order_id='$order_id'");
    }


    //cancel order
    public function cancelOrder($data){
        $user_id = $data->user_id;
        $order_id = $data->order_id;

        return $this->query("UPDATE order_summary SET status='CANCELED' WHERE user_id='$user_id' AND order_id='$order_id'");
    }


    //clear cart
    public function removeProductCart($user_id, $product_id){
        return $this->query("DELETE FROM cart WHERE user_id='$user_id' AND product_id IN ($product_id)");
    }


    // //view user orders
    public function viewUserOrders($user_id){
        return $this->list("SELECT * FROM order_summary WHERE user_id='$user_id'");
    }

    //view single order
    public function viewSingleOrder($order_id){
        return $this->list("SELECT * FROM order_summary WHERE order_id='$order_id'");
    }

    //view order products
    public function viewOrderProducts($order_id){
        return $this->list("SELECT a.order_id, a.product_id, b.name, b.thumbnail, c.name category, b.unit_price, a.qty, d.name measurement FROM orders a LEFT JOIN products b ON b.id=a.product_id LEFT JOIN category c ON b.category=c.id LEFT JOIN measurement d ON b.measurement=d.id WHERE order_id='$order_id'");

        //REVAMPED QUERY
        //SELECT a.id, a.name, a.thumbnail, b.name category, a.unit_price, d.qty, c.name measurement FROM products a LEFT JOIN category b ON a.category=b.id LEFT JOIN measurement c ON a.measurement=c.id LEFT JOIN orders d ON d.order_id='$order_id' AND d.product_id=a.id WHERE a.id IN (SELECT product_id FROM orders WHERE order_id='$order_id')
    }

    public function checkOrder($order_id){
        return $this->count("SELECT COUNT(1) count FROM orders WHERE order_id='$order_id'");
    }

    public function checkOrderStatus($order_id){
        return $this->count("SELECT COUNT(1) count FROM order_summary WHERE order_id='$order_id' AND status='COMPLETED'");
    }

}
