<?php
session_start();
include 'connect.php';

// Make sure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login-page.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// SECTION 1: UPDATE PROFILE

if (isset($_POST['FirstName']) && isset($_POST['LastName'])) {

    $first_name = trim($_POST['FirstName'] ?? '');
    $last_name = trim($_POST['LastName'] ?? '');
    $contact_number = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $postal_code = trim($_POST['postal_code'] ?? '');

    // Basic validation
    if ($first_name === '' || $last_name === '') {
        echo "<script>alert('First name and last name cannot be empty.'); window.history.back();</script>";
        exit();
    }

    // Validate contact number format (must be 11 digits starting with 09)
    if ($contact_number !== '') {
        if (!preg_match('/^09\d{9}$/', $contact_number)) {
            echo "<script>alert('Invalid contact number format. It should start with 09 and be 11 digits long.'); window.history.back();</script>";
            exit();
        }
    }

        if ($contact_number === '') {
        if (!preg_match('/^09\d{9}$/', $contact_number)) {
            echo "<script>alert('Contact number cannot be empty'); window.history.back();</script>";
            exit();
        }
    }


    if($postal_code !== '') {
        if (!preg_match('/^\d{4}$/', $postal_code)) {
            echo "<script>alert('Invalid postal code format. It should be 4 digits long.'); window.history.back();</script>";
            exit();
        }
    }

    $update_query = "UPDATE customer 
                     SET first_name='$first_name', last_name='$last_name', contact_number='$contact_number', address='$address', postal_code='$postal_code'
                     WHERE customer_id='$customer_id'";

    if ($connect->query($update_query) === TRUE) {
        echo "<script>
                alert('Profile updated successfully!');
                window.location.href='dashboard.php';
              </script>";
    } else {
        echo "<script>
                alert('Something went wrong while updating your profile.');
                window.history.back();
              </script>";
    }
}

//  SECTION 2: CHANGE PASSWORD

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Check if all fields are filled
    if ($current_password === '' || $new_password === '' || $confirm_password === '') {
        echo "<script>alert('Please fill in all password fields.'); window.history.back();</script>";
        exit();
    }

    // Get stored password
    $query = "SELECT password FROM customer WHERE customer_id='$customer_id'";
    $result = $connect->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row['password'];

        // Verify current password
        if (!password_verify($current_password, $stored_hash)) {
            echo "<script>
                    alert('Current password is incorrect.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Confirm new password match
        if ($new_password !== $confirm_password) {
            echo "<script>
                    alert('New passwords do not match.');
                    window.history.back();
                  </script>";
            exit();
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update new password
        $update_pass = "UPDATE customer SET password='$hashed_password' WHERE customer_id='$customer_id'";
        if ($connect->query($update_pass) === TRUE) {
            echo "<script>
                    alert('Password changed successfully!');
                    window.location.href='dashboard.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error updating password: " . $connect->error . "');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>alert('User not found.'); window.history.back();</script>";
    }
}

$connect->close();
?>
