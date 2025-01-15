<?php
// Start the session
session_start();

// Database connection
require_once('../indorama_portal_/lib/db_login.php');

// Check if id_menu parameter exists
if (isset($_GET['id_menu'])) {
    $id_menu = $_GET['id_menu'];
    
    // Prepare the delete query using prepared statement to prevent SQL injection
    $query = "DELETE FROM menu WHERE id_menu = ?";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("i", $id_menu);
        
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
    // No id_menu provied
    $_SESSION['error'] = "No user id_menu specified for deletion.";
}

// Redirect back to the user list page
header("Location: manageMenu.php");
exit();
?>
