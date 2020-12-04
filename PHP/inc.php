<?php

declare(strict_types = 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: text/html; charset=UTF-8');

require_once "./constants.php";
require_once "./Models/history.php";
require_once "./Models/log.php";
require_once "./Models/log_type.php";
require_once "./Models/product.php";
require_once "./Models/product_group.php";
require_once "./Models/rent.php";
require_once "./Models/user.php";
require_once "./Models/user_group.php";
require_once "./Models/user_session.php";
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
const KEY_MANAGE = "key";
const KEY_TITLE = "title";
const KEY_MESSAGE = "message";
const KEY_SWITCH_MASTER = "system_on";
const KEY_SWITCH_LOGIN = "system_login";
const KEY_SWITCH_RENT = "system_rent";
const KEY_SWITCH_MESSAGE = "system_message";

const PARAMETER_UNDEFINED = 0;
const PARAMETER_STRING = 1;
const PARAMETER_NUMERIC = 2;

// TODO variables name to model keys
// TODO function and variables to static

function printOutput($result, $error, $params = null) {
    $output = array();
    $output[KEY_RESULT] = $result;
    if (isset($error)) {
        $output[KEY_ERROR] = $error;
    } else {
        $output[KEY_ERROR] = "";
    }
    if ($params) {
        array_merge($output, $params);
    }
    $outputJson = json_encode($output);
    echo urldecode($outputJson);
}

function validateParameter($parameterName, $isNecessary, $parameterType, $default = null) {
    if (!isset($_REQUEST[$parameterName])) {
        if ($isNecessary) {
            printOutput(-1, $parameterName." IS EMPTY");
            exit();
        }
        return $default;
    }
    $parameter = $_REQUEST[$parameterName];
    if ($parameterType == PARAMETER_STRING && !is_string($parameter)) {
        printOutput(-1, $parameterName." MUST BE STRING");
        exit();
    }
    if ($parameterType == PARAMETER_NUMERIC && !is_numeric($parameter)) {
        printOutput(-1, $parameterName." MUST BE NUMERIC");
        exit();
    }
    return $parameter;
}

function generateSession() {
    # generate session key
    $sessionCharacters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($sessionCharacters);
    $sessionLength = 12;
    $sessionKey = "";
    for ($i = 0; $i < $sessionLength; $i++) {
        $sessionKey .= $sessionCharacters[rand(0, $charactersLength - 1)];
    }
    return $sessionKey;
}

?>