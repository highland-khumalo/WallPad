<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "config.php";

if (!isset($det)){
    $det = "./";
}
$connection = mysqli_connect($config['host'], $config['mysql_username'], $config['mysql_password'], $config['mysql_database']);
$conn = new mysqli($config['host'], $config['mysql_username'], $config['mysql_password'], $config['mysql_database']);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_COOKIE['theme'])) {
    $theme = "white";
} else {
    $theme = $_COOKIE['theme'];
}

$uri = $_SERVER['REQUEST_URI'];
$now = new DateTime();
$format = "h:i A j M";


function dd($value)
{

    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    die();
}

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

function redirect_js($url)
{
    $jsCode = '<script>';
    $jsCode .= 'setTimeout(function() { window.location.href = "' . $url . '"; }, 2000);';
    $jsCode .= '</script>';

    echo $jsCode;
}

function transq($arr) {
    $res = [];
    foreach ($arr as $s) {
        $res[$s] = "";
    }
    return $res;
}

function sanitize($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function sanitizeString($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function startsWith($str, $search)
{
    return substr($str, 0, strlen($search)) == $search;
}

function formatPhoneNumber($phoneNumber)
{
    if (startsWith($phoneNumber, "+27")) {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        $length = strlen($phoneNumber);
        if ($length == 10) {
            return '+27 ' . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2, 3) . ' ' . substr($phoneNumber, 5);
        } else {
            return $phoneNumber;
        }
    }
}

function generate_rm($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }
    return $code;
}

function render($template, $title="") {
    $title = $title;
    include "config.php";
    include "partials/" . $template . ".partials.php";
}


$columns = [
    ['name' => 'title', 'type' => 'VARCHAR(255) NOT NULL'],
    ['name' => 'category', 'type' => 'VARCHAR(50) NOT NULL'],
    ['name' => 'device', 'type' => 'VARCHAR(50) NOT NULL'],
    ['name' => 'description', 'type' => 'TEXT'],
    ['name' => 'date_uploaded', 'type' => 'DATETIME'],
    ['name' => 'filename', 'type' => 'VARCHAR(255) NOT NULL']
];



# database functions

// Function to check if a MySQL database exists
function is_database($databaseName)
{
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SHOW DATABASES LIKE '$databaseName'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

// Function to create a new MySQL database
function create_database($databaseName)
{
    global $connection;

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (is_database($databaseName)) {
        return false;
    }

    $sql = "CREATE DATABASE $databaseName";
    if (mysqli_query($connection, $sql)) {
        return true;
    } else {
        echo "Error creating database: " . mysqli_error($connection);
        return false;
    }
}

function delete_database($databaseName)
{
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "DROP DATABASE $databaseName";

    if (mysqli_query($connection, $sql)) {
        return true;
    } else {
        echo "Error deleting database: " . mysqli_error($connection);
        return false;
    }

    mysqli_close($connection);
}

// Function to check if a MySQL table exists
function is_table($tableName)
{
    global $connection;

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SHOW TABLES LIKE '$tableName'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

/* Function to create a new MySQL table

$columns = [
    ['name' => 'name', 'type' => 'VARCHAR(30) NOT NULL'],
    ['name' => 'email', 'type' => 'VARCHAR(50) NOT NULL']
];
create_table($databaseName, $tableName, $columns);
*/

function create_table($tableName, $columns)
{
    global $connection;

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (is_table($tableName)) {
        return false;
    }

    $columnsSQL = '';
    foreach ($columns as $column) {
        $columnName = $column['name'];
        $columnType = $column['type'];
        $columnsSQL .= "$columnName $columnType, ";
    }
    $columnsSQL = rtrim($columnsSQL, ', ');

    $sql = "CREATE TABLE $tableName (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        $columnsSQL
    )";

    if (mysqli_query($connection, $sql)) {
        return true;
    } else {
        echo "Error creating table: " . mysqli_error($connection);
        return false;
    }
}

/* Function to insert a new entry into a MySQL table
$entryData = [
    'name' => 'john',
    'email' => 'whatwhat@gmail.com'
];
new_entry($databaseName, $tableName, $entryData);
*/
function new_entry($tableName, $entryData)
{
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $columns = implode(', ', array_keys($entryData));
    $values = "'" . implode("', '", $entryData) . "'";

    $sql = "INSERT INTO $tableName ($columns)
            VALUES ($values)";

    if (mysqli_query($connection, $sql)) {
        return true;
    } else {
        echo "Error creating new entry: " . mysqli_error($connection);
        return false;
    }
}

/* Function to edit an entry in a MySQL table
$databaseName = 'my_database';
$tableName = 'users';
$criteria = ['id' => 2];
$data = ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com'];
*/
function edit_entry($tableName, $criteria, $data)
{
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $updateData = '';
    foreach ($data as $column => $value) {
        $sanitizedValue = mysqli_real_escape_string($connection, $value);
        $updateData .= "$column = '$sanitizedValue', ";
    }
    $updateData = rtrim($updateData, ', ');

    $conditions = '';
    foreach ($criteria as $column => $value) {
        $sanitizedValue = mysqli_real_escape_string($connection, $value);
        $conditions .= "$column = '$sanitizedValue' AND ";
    }
    $conditions = rtrim($conditions, ' AND ');

    $sql = "UPDATE $tableName SET $updateData WHERE $conditions";

    if (mysqli_query($connection, $sql)) {
        return true;
    } else {
        echo "Error updating entry: " . mysqli_error($connection);
        return false;
    }
}

/* Function to check if an entry exists in a MySQL table
$databaseName = 'my_database';
$tableName = 'users';
$criteria = ['id' => 2];
*/
function is_entry($tableName, $criteria)
{
    global $connection;
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $conditions = '';
    foreach ($criteria as $column => $value) {
        $sanitizedValue = mysqli_real_escape_string($connection, $value);
        $conditions .= "$column = '$sanitizedValue' AND ";
    }
    $conditions = rtrim($conditions, ' AND ');

    $sql = "SELECT COUNT(*) as count FROM $tableName WHERE $conditions";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['count'] > 0;
}

/* Function to retrieve an entry from a MySQL table
$databaseName = 'my_database';
$tableName = 'users';
$criteria = ['id' => 2];

output:
Array (
    [id] => 2
    [name] => Jane Doe
    [email] => janedoe@example.com
)
*/
function get_entry($tableName, $criteria)
{
    global $connection;

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $conditions = '';
    foreach ($criteria as $column => $value) {
        $sanitizedValue = mysqli_real_escape_string($connection, $value);
        $conditions .= "$column = '$sanitizedValue' AND ";
    }
    $conditions = rtrim($conditions, ' AND ');

    $sql = "SELECT * FROM $tableName WHERE $conditions";
    $result = mysqli_query($connection, $sql);

    $entry = mysqli_fetch_assoc($result);

    return $entry;
}

function get_top_users() {
    global $connection;

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM users ORDER BY CAST(ta_points AS UNSIGNED) DESC LIMIT 10";
    $result = mysqli_query($connection, $sql);

    $top_users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $top_users[] = $row;
    }

    return $top_users;
}


# cache funcions
/*
function cache($var, $value)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    put_to_database("cache", $var, $value);
    return TRUE;
}

function cachedif($var, $Cvalue)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    if (get_from_database("cache", $var) === $Cvalue) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function cacheold($var)
{
    create_database("users/" . $_COOKIE['phone_number'] . "/cache");
    if (get_from_database("cache", $var)) {
        return get_from_database("cache", $var);
    }
}


function log_($massege)
{
    $old = file_get_contents("log.txt");
    file_put_contents("log.txt", $old . date('Y-m-d H:i:s') . " => $massege\n");
}

function log_clear()
{
    file_put_contents("log.txt", "");
}
*/