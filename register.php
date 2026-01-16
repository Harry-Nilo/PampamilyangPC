<?php
include '../src/config/connect.php';
session_start(); // start once

if(isset($_POST['Sign_up'])){
    $first_name = $_POST['Fname'];
    $last_name = $_POST['Lname'];
    $contact_number = $_POST['PNumber'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $check_email = "SELECT * FROM customer WHERE email='$email'";
    $result = $connect->query($check_email);

    if($result->num_rows > 0){
        echo "<script>
            alert('Email already exists. Please use a different email.');
            window.history.back();
        </script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO customer (first_name, last_name, contact_number, email, password, verified) 
                         VALUES ('$first_name', '$last_name', '$contact_number', '$email', '$hashed_password', 0)";
        if($connect->query($insert_query) === TRUE){
            echo "<script>
                alert('Registration successful! You can now log in.');
                window.location.href = 'login-page.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Error: ".$connect->error."')</script>";
        }
    }
}

if (isset($_POST['Sign_in'])) {
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // Get the user's record by email
    $sql = "SELECT * FROM customer WHERE email='$email'";
    $result = $connect->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];

        // Verify the hashed password
        if (password_verify($password, $stored_hash)) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['customer_id'] = $row['customer_id'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>
                alert('Invalid email or password. Please try again.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Invalid email or password. Please try again.');
            window.history.back();
        </script>";
    }
}

?>