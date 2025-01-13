<?php
    session_start();
    require_once('../indorama_portal_/lib/db_login.php');

    // Only process if form was submitted
    if (isset($_POST['submit'])) {
        $valid = TRUE;
        $error = '';
        
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        if ($email == '') {
            $valid = FALSE;
            $error = 'Email is required';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = FALSE;
            $error = 'Invalid email format';
        } else if ($password == '') {
            $valid = FALSE;
            $error = 'Password is required';    
        }
        
        if ($valid) {
            $query = "SELECT * FROM user WHERE email=? AND password=?";
            $stmt = $db->prepare($query);
            $hashed_password = md5($password);
            $stmt->bind_param("ss", $email, $hashed_password);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result) {
                $error = "Database error: " . $db->error;
            } else {
                if ($result->num_rows > 0) {
                    $_SESSION['username'] = $email;
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Combination of username and password are not correct.';
                }
            }
            $stmt->close();
            $db->close();
        }
        
        // Only redirect if there's an error
        if ($error) {
            header("Location: login.php?error=" . urlencode($error));
            exit;
        }
    } else {
        // If someone accesses this file directly without submitting the form
        header("Location: login.php");
        exit;
    }
?>