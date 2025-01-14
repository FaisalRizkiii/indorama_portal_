<?php
// Start the session
session_start();

// Database connection
require_once('../indorama_portal_/lib/db_login.php');

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the delete query using prepared statement to prevent SQL injection
    $query = "DELETE FROM user WHERE id = ?";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Successful deletion
            $_SESSION['success'] = "User successfully deleted.";
        } else {
            // Failed to execute the query
            $_SESSION['error'] = "Error deleting user: " . $db->error;
        }
        
        $stmt->close();
    } else {
        // Failed to prepare the statement
        $_SESSION['error'] = "Error preparing delete statement: " . $db->error;
    }
    
    $db->close();
} else {
    // No ID provided
    $_SESSION['error'] = "No user ID specified for deletion.";
}

// Redirect back to the user list page
header("Location: manageUser.php");
exit();
?>
