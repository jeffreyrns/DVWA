<?php
define('DVWA_WEB_PAGE_TO_ROOT', '../../');
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

/*
On impossible only the admin is allowed to retrieve the data.
*/

if (dvwaSecurityLevelGet() == "impossible" && dvwaCurrentUser() != "admin") {
    echo json_encode(array("result" => "fail", "error" => "Access denied"));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    $result = array(
        "result" => "fail",
        "error" => "Only POST requests are accepted"
    );
    echo json_encode($result);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json);

if (json_last_error() !== JSON_ERROR_NONE) {
    $result = array(
        "result" => "fail",
        "error" => 'Invalid format, expecting JSON with "id", "first_name", and "surname" fields'
    );
    echo json_encode($result);
    exit;
}

if (!isset($data->id) || !isset($data->first_name) || !isset($data->surname)) {
    $result = array(
        "result" => "fail",
        "error" => 'Missing required fields. Expected fields: id, first_name, surname'
    );
    echo json_encode($result);
    exit;
}

// Sanitize inputs
$id = intval($data->id);
$first_name = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data->first_name);
$surname = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $data->surname);

$query = "UPDATE users SET first_name = '$first_name', last_name = '$surname' WHERE user_id = $id";

if (mysqli_query($GLOBALS["___mysqli_ston"], $query)) {
    echo json_encode(array("result" => "ok"));
} else {
    $error = mysqli_error($GLOBALS["___mysqli_ston"]);
    echo json_encode(array("result" => "fail", "error" => "Database error: $error"));
}

exit;
?>
