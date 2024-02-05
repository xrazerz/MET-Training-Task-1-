<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (mysqli_query($con, $sql)) {
        $_SESSION['username']=$username;
        header('Location:home.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// mysqli_close($con);
?>
