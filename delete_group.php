<?php
// Start the session
session_start();

// Database connection
require_once('../indorama_portal_/lib/db_login.php');

// Check if group_id parameter exists
if (isset($_GET['group_id'])) {
    $group_id = $_GET['group_id'];

    // Start transaction
    $db->begin_transaction();

    try {
        $query1 = "DELETE FROM mapping_categorymenu WHERE group_id = ?";
        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param("i", $group_id);
        $stmt1->execute();
        $stmt1->close();

        $query1 = "DELETE FROM group_members WHERE group_id = ?";
        $stmt1 = $db->prepare($query1);
        $stmt1->bind_param("i", $group_id);
        $stmt1->execute();
        $stmt1->close();

        // Then delete from category_menu
        $query2 = "DELETE FROM `group` WHERE group_id = ?";
        $stmt2 = $db->prepare($query2);
        $stmt2->bind_param("i", $group_id);
        $stmt2->execute();
        $stmt2->close();

        // If everything is fine, commit transaction
        $db->commit();
        $_SESSION['success'] = "Group and associated mappings successfully deleted.";
    } catch (Exception $e) {
        // An error occurred, rollback any changes
        $db->rollback();
        $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
    }

    $db->close();

} else {
    // No group_id provided
    $_SESSION['error'] = "No group specified for deletion.";
}

// Redirect back to the user list page
header("Location: managegroup.php");
exit();
?>
