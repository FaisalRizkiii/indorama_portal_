<?php
// Start the session
session_start();

// Database connection
require_once('../indorama_portal_/lib/db_login.php');

// Check if id_categorymenu parameter exists
if (isset($_GET['id_categorymenu'])) {
    $id_categorymenu = $_GET['id_categorymenu'];
    
    // Prepare the delete query using prepared statement to prevent SQL injection
    $query = "DELETE FROM mapping_menu WHERE id_categorymenu = ?";
    
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param("i", $id_categorymenu);
        
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

    // Prepare the delete query using prepared statement to prevent SQL injection
    $query2 = "DELETE FROM category_menu WHERE id_categorymenu = ?";

    if ($stmt = $db->prepare($query2)) {
        $stmt->bind_param("i", $id_categorymenu);
        
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
    // No id_categorymenu provied
    $_SESSION['error'] = "No categorymenu specified for deletion.";
}

// Redirect back to the user list page
header("Location: manageCatMenu.php");
exit();
?>
