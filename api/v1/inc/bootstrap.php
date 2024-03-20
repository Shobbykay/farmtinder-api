<?php

define("PROJECT_ROOT_PATH", __DIR__ . "/../");

// include main configuration file
require_once PROJECT_ROOT_PATH . "/inc/config.php";

// include the base controller file
require_once PROJECT_ROOT_PATH . "/controller/api/BaseController.php";

// include the use model file
require_once PROJECT_ROOT_PATH . "/model/UserModel.php";
require_once PROJECT_ROOT_PATH . "/model/CategoryModel.php";
require_once PROJECT_ROOT_PATH . "/model/MeasurementModel.php";
require_once PROJECT_ROOT_PATH . "/model/StoreModel.php";
require_once PROJECT_ROOT_PATH . "/model/ProductModel.php";
require_once PROJECT_ROOT_PATH . "/model/FavoriteModel.php";
require_once PROJECT_ROOT_PATH . "/model/ReviewsModel.php";
require_once PROJECT_ROOT_PATH . "/model/DeliveryModel.php";
require_once PROJECT_ROOT_PATH . "/model/CartModel.php";
require_once PROJECT_ROOT_PATH . "/model/OrderModel.php";


//validations
require_once PROJECT_ROOT_PATH . "/validations/user.php";
require_once PROJECT_ROOT_PATH . "/validations/category.php";
require_once PROJECT_ROOT_PATH . "/validations/measurement.php";
require_once PROJECT_ROOT_PATH . "/validations/store.php";
require_once PROJECT_ROOT_PATH . "/validations/product.php";
require_once PROJECT_ROOT_PATH . "/validations/favorite.php";
require_once PROJECT_ROOT_PATH . "/validations/reviews.php";
require_once PROJECT_ROOT_PATH . "/validations/delivery.php";
require_once PROJECT_ROOT_PATH . "/validations/cart.php";
require_once PROJECT_ROOT_PATH . "/validations/orders.php";
