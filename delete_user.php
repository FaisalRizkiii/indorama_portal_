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

    $query2 = "DELETE FROM group_members WHERE id = ?";
    
    if ($stmt = $db->prepare($query2)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "User successfully deleted.";
        } else {
            $_SESSION['error'] = "Error deleting user: " . $db->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing delete statement: " . $db->error;
    }
    
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "User successfully deleted.";
        } else {
            $_SESSION['error'] = "Error deleting user: " . $db->error;
        }
        
        $stmt->close();
    } else {
        $_SESSION['error'] = "Error preparing delete statement: " . $db->error;
    }

    
    $db->close();
} else {
    $_SESSION['error'] = "No user ID specified for deletion.";
}

header("Location: manageUser.php");
exit();
?>
