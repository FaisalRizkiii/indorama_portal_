<?php
    session_start();
    require_once('../indorama_portal_/lib/db_login.php');

    if (isset($_POST['submit'])) {
        $valid = TRUE;
        $error = '';

        $email = validate($_POST['email']);
        $password = validate($_POST['password']);

        // Validation
        if (empty($email)) {
            $valid = FALSE;
            $error = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $valid = FALSE;
            $error = 'Invalid email format';
        } elseif (empty($password)) {
            $valid = FALSE;
            $error = 'Password is required';
        }

        // Proceed if validation passes
        if ($valid) {
            $query = "SELECT * FROM user WHERE email=? AND password=?";
            $stmt = $db->prepare($query);

            // Use a more secure password hashing mechanism (e.g., password_hash)
            $hashed_password = md5($password); // Update this to use `password_hash` in the future
            $stmt->bind_param("ss", $email, $hashed_password);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result) {
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = 'Combination of username and password is not correct.';
                }
            } else {
                $error = "Database error: " . $db->error;
            }

            // Close database connections
            $stmt->close();
            $db->close();
        }

        // Redirect with error if validation fails or query fails
        if ($error) {
            header("Location: login.php?error=" . urlencode($error));
            exit;
        }
    } else {
        // Redirect to login if accessed directly
        header("Location: login.php");
        exit;
    }
?>
