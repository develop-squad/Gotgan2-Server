<?php

declare(strict_types = 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: text/html; charset=UTF-8');

require_once "./constants.php";
require_once "./Utils/singleton.php";
require_once "./Managers/database_manager.php";
require_once "./Managers/history_manager.php";
require_once "./Managers/log_manager.php";
require_once "./Managers/product_manager.php";
require_once "./Managers/rent_manager.php";
require_once "./Managers/service_manager.php";
require_once "./Managers/system_manager.php";
require_once "./Managers/user_manager.php";

const KEY_RESULT = "result";
const KEY_ERROR = "error";

// TODO variables name to model keys
// TODO function and variables to static

function printOutput($result, $error, $params = null) {
    $output = array();
    $output[KEY_RESULT] = $result;
    $output[KEY_ERROR] = $error;
    if ($params) {
        array_merge($output, $params);
    }
    $outputJson = json_encode($output);
    echo urldecode($outputJson);
}

?>