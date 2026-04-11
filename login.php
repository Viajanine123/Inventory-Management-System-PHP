<?php
$host = "localhost";
$user = "root";
$password = '';
$db_name = "inventorymanagement";

$con = mysqli_connect($host, $user, $password, $db_name);
if (mysqli_connect_errno()) {
    die("Failed to connect with MySQL: " . mysqli_connect_error());
}

// Check if form was submitted first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($con, $sql);
    $count = mysqli_num_rows($result);

    if ($count == 1) {
        require("./table.php");
    } else {
        echo "<h1>Login Failed. Invalid email or password.</h1>";
        echo "<a href='index.php'>Go Back</a>";
    }
} else {
    // Not submitted — redirect back to login page
    header("Location: index.php");
    exit;
}
?>