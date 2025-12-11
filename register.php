<?php

$errors = [];
$data = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // PHP Validation
    if (empty($fullname)) $errors['fullname'] = "Full name is required.";
    
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (strlen($password) < 8) $errors['password'] = "Password must be at least 8 characters.";

    if ($password !== $confirmPassword) $errors['confirmPassword'] = "Passwords do not match.";

    // If no errors, save data
    if (empty($errors)) {
        $userData = [
            "fullname" => $fullname,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT)
        ];

        // Load existing users
        $file = "users.json";
        $users = json_decode(file_get_contents($file), true);

        $users[] = $userData;

        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
        
        echo "<script>alert('Registration Successful!'); window.location='index.html';</script>";
        exit();
    }
}

// Send errors back if needed
echo "<pre>";
print_r($errors);
echo "</pre>";
echo "<a href='index.html'>Go Back</a>";
?>