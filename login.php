<?php
session_start();
$host = "localhost";
$user = "root";
$password = '';
$db_name = "inventorymanagement";

$con = mysqli_connect($host, $user, $password, $db_name);
if (mysqli_connect_errno()) {
    die("Failed to connect with MySQL: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $sql    = "SELECT * FROM user WHERE email = '$email' AND password = '$pass'";
    $result = mysqli_query($con, $sql);
    $count  = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['inventory_logged_in'] = true;
        header("Location: table.php");
        exit;
    } else {
        header("Location: index.php?error=1");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
