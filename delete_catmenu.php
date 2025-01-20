<?php
// Start the session
session_start();

// Database connection
require_once('../indorama_portal_/lib/db_login.php');

// Check if id_categorymenu parameter exists
if (isset($_GET['id_categorymenu'])) {
    $id_categorymenu = $_GET['id_categorymenu'];

    // Start transaction
    $db->begin_transaction();

    try {
        $query1 = "DELETE FROM mapping_categorymenu WHERE id_categorymenu = ?";
        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param("i", $id_categorymenu);
        $stmt1->execute();
        $stmt1->close();

        $query1 = "DELETE FROM mapping_menu WHERE id_categorymenu = ?";
        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param("i", $id_categorymenu);
        $stmt1->execute();
        $stmt1->close();

        // Then delete from category_menu
        $query2 = "DELETE FROM category_menu WHERE id_categorymenu = ?";
        $stmt2 = $db->prepare($query2);
        $stmt2->bind_param("i", $id_categorymenu);
        $stmt2->execute();
        $stmt2->close();

        // If everything is fine, commit transaction
        $db->commit();
        $_SESSION['success'] = "Category and associated mappings successfully deleted.";
    } catch (Exception $e) {
        // An error occurred, rollback any changes
        $db->rollback();
        $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
    }

    $db->close();

} else {
    // No id_categorymenu provided
    $_SESSION['error'] = "No category menu specified for deletion.";
}

// Redirect back to the user list page
header("Location: manageCatMenu.php");
exit();
?>
