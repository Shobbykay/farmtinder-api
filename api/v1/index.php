<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require __DIR__ . "/inc/bootstrap.php";

//Controllers
require PROJECT_ROOT_PATH . "/controller/api/UserController.php";
require PROJECT_ROOT_PATH . "/controller/api/CategoryController.php";
require PROJECT_ROOT_PATH . "/controller/api/MeasurementController.php";
require PROJECT_ROOT_PATH . "/controller/api/StoreController.php";
require PROJECT_ROOT_PATH . "/controller/api/ProductController.php";
require PROJECT_ROOT_PATH . "/controller/api/FavoriteController.php";
require PROJECT_ROOT_PATH . "/controller/api/ReviewsController.php";
require PROJECT_ROOT_PATH . "/controller/api/DeliveryController.php";
require PROJECT_ROOT_PATH . "/controller/api/CartController.php";
require PROJECT_ROOT_PATH . "/controller/api/OrdersController.php";


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

$resources = [
    "user",
    "category",
    "measurement",
    "store",
    "products",
    "favorite",
    "reviews",
    "delivery",
    "cart",
    "orders"
];


if ((isset($uri[2]) && !in_array($uri[5], $resources) ) || !isset($uri[3])) {

    header("HTTP/1.1 404 Not Found");
    $response = [
        "status" => "error",
        "message" => "Resource not found, refer to API doc"
    ];
    echo json_encode($response);
    exit();

}




//ROUTING
if ($uri[5] == 'user'){

    $actions = [
        "create",
        "list",
        "view",
        "loan_eligibility"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'loan_eligibility')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new UserController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }

    //user route
    $objFeedController = new UserController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();


} else if ($uri[5] == 'category'){

    $actions = [
        "create",
        "list",
        "view",
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new CategoryController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new CategoryController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'measurement'){

    $actions = [
        "create",
        "list",
        "view"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new MeasurementController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new MeasurementController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'store'){

    $actions = [
        "create",
        "list",
        "view",
        "product"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'product')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new StoreController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new StoreController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'products'){

    $actions = [
        "create",
        "list",
        "view"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new ProductController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new ProductController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'favorite'){

    $actions = [
        "add",
        "user_list",
        "delete"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'user_list') || ($uri[6] == 'delete')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if (($uri[6] == 'delete') && (!isset($uri[8]) || empty($uri[8]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if ($uri[6] == 'delete'){
            $objFeedController = new FavoriteController();
            $strMethodName = $uri[6];
            $objFeedController->{$strMethodName}($uri[7], $uri[8]);
            exit();
        }

        $objFeedController = new FavoriteController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new FavoriteController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'reviews'){

    $actions = [
        "create",
        "list",
        "delete",
        "rating_summary"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'list') || ($uri[6] == 'rating_summary') || ($uri[6] == 'delete')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if (($uri[6] == 'delete') && (!isset($uri[8]) || empty($uri[8]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if ($uri[6] == 'delete'){
            $objFeedController = new ReviewsController();
            $strMethodName = $uri[6];
            $objFeedController->{$strMethodName}($uri[7], $uri[8]);
            exit();
        }

        $objFeedController = new ReviewsController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new ReviewsController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'delivery'){

    $actions = [
        "create",
        "list",
        "view",
        "delete",
        "edit"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'view') || ($uri[6] == 'list') || ($uri[6] == 'edit') || ($uri[6] == 'delete')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if (($uri[6] == 'delete') && (!isset($uri[8]) || empty($uri[8]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if ($uri[6] == 'delete'){
            $objFeedController = new DeliveryController();
            $strMethodName = $uri[6];
            $objFeedController->{$strMethodName}($uri[7], $uri[8]);
            exit();
        }

        $objFeedController = new DeliveryController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new DeliveryController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'cart'){

    $actions = [
        "add",
        "list",
        "remove",
        "clear"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'list') || ($uri[6] == 'clear') || ($uri[6] == 'remove')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if (($uri[6] == 'remove') && (!isset($uri[8]) || empty($uri[8]))){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        if ($uri[6] == 'remove'){
            $objFeedController = new CartController();
            $strMethodName = $uri[6];
            $objFeedController->{$strMethodName}($uri[7], $uri[8]);
            exit();
        }

        $objFeedController = new CartController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new CartController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

} else if ($uri[5] == 'orders'){

    $actions = [
        "create",
        "complete",
        "cancel",
        "list",
        "view"
    ];

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || ($uri[6] == 'list') || ($uri[6] == 'view')){

        if (!isset($uri[7]) || empty($uri[7])){
            header("HTTP/1.1 404 Not Found");
            $response = [
                "status" => "error",
                "message" => "Resource not found, refer to API doc"
            ];
            echo json_encode($response);
            exit();
        }

        $objFeedController = new OrdersController();
        $strMethodName = $uri[6];
        $objFeedController->{$strMethodName}($uri[7]);
        exit();
    }

    if ((isset($uri[6]) && !in_array($uri[6], $actions)) || (isset($uri[7]))){
        header("HTTP/1.1 404 Not Found");
        $response = [
            "status" => "error",
            "message" => "Resource not found, refer to API doc"
        ];
        echo json_encode($response);
        exit();
    }


    $objFeedController = new OrdersController();
    $strMethodName = $uri[6];
    $objFeedController->{$strMethodName}();
    exit();

}