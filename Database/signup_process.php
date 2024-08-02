<?php
// Database connection
include 'dbconn.php';

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Basic validation (add more as needed)
    if (!empty($username) && !empty($password)) {
        // Hash the password

        // Prepare an SQL statement
        $stmt = $conn->prepare('INSERT INTO login (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $password);

        // Execute the statement
        if ($stmt->execute()) {
            echo 'Signup successful!';
        } else {
            echo 'Error: ' . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo 'All fields are required!';
    }
}

// Close the connection
$conn->close();
?>