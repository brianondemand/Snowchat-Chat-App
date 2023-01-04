<?php
session_start();
include_once "config.php";

// Escape user inputs for security
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// Check if input fields are empty
if(empty($email) || empty($password)){
    echo "All input fields are required!";
    exit();
}

// Check if email and password combination exists in database
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 1){
    // Fetch user data
    $user = mysqli_fetch_assoc($result);
    $user_password = $user['password'];

    // Check if password is correct
    if(password_verify($password, $user_password)){
        // Update user status to active
        $status = "Active now";
        $update_query = "UPDATE users SET status = '$status' WHERE unique_id = {$user['unique_id']}";
        $update_result = mysqli_query($conn, $update_query);
        if($update_result){
            // Set session variable and return success
            $_SESSION['unique_id'] = $user['unique_id'];
            echo "success";
        }else{
            // Return error message
            echo "Something went wrong. Please try again!";
        }
    }else{
        // Return error message
        echo "Email or password is incorrect!";
    }
}else{
    // Return error message
    echo "$email - This email does not exist!";
}

mysqli_close($conn);
?>
