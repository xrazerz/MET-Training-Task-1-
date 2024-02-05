<?php
session_start();
$db = mysqli_connect('localhost', 'root', '', 'taskonemet');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $user['email'];
        header('Location: home.php');
        exit();
    } else {
        echo "Invalid email/password or password.";
}
}
?>