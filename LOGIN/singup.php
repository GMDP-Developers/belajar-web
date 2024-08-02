<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    echo "<h1>FORM SIGNUP</h1>";
    echo "Username: " . $username . "<br>";
    echo "Password: " . $password;
}
?>