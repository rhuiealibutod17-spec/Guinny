<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm'] ?? '');

    if (empty($fullname) || empty($email) || empty($password) || empty($confirm)) {
        die("All fields required.");
    }
    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $checkEmail->close();
        die("Email already exists.");
    }
    $checkEmail->close();

    // Prepare and execute insert statement
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Sign Up Successful! Welcome to Brewed by Rhuie ☕'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Error: Unable to register. Please try again.'); window.history.back();</script>";
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>