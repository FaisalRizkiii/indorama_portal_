<?php include('header.php')?>
<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] != 'admin') {
        header("Location: index.php");
        exit();
    }

    // Database connection
    require_once('../indorama_portal_/lib/db_login.php');

    // Fetch records for the current page
    $query = "SELECT * FROM `Group`";
    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
    }
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include('sidebar.php') ?>
        <!-- Main -->
        <div class="col-md-11" style="height: 100vh;">
            <div class="container" >
                <div class="row">
                    <div class="col-md-12" >
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 30px">Manage Groups</h3>
                        </div>
                        <a href="add_group.php" class="btn btn-success" style="margin-bottom: 15px;">
                            <i class="fas fa-user-plus"></i> Add New Group
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover" style="text-align: center;">
                            <thead>
                                <tr class="info">
                                    <th>No</th>
                                    <th>Group Name</th>
                                    <th>Group Members</th>
                                    <th>Category Menu</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $no = 0;
                                if ($result->num_rows > 0){ 
                                    while ($row = $result->fetch_object()) {
                                        $no++;
                                        // Get Group members
                                        $query2 = "SELECT u.name 
                                                    FROM User u 
                                                    JOIN group_members gm 
                                                    ON u.id = gm.user_id 
                                                    WHERE gm.group_id = {$row->group_id}";
                                        $result2 = $db->query($query2);
                                        $members = [];
                                        while ($member = $result2->fetch_object()) {
                                            $members[] = htmlspecialchars($member->name); 
                                        }
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        echo '<td>' . htmlspecialchars($row->group_name) . '</td>';
                                        echo '<td>' . implode(', ', $members) . '</td>';
                                        echo '<td>Transaction, Transaction, Transaction, Transaction</td>';
                                        echo '<td>';
                                        echo 
                                            '<a class="btn btn-primary btn-sm " style="margin: 3px;" href="edit_group.php?group_id=' . htmlspecialchars($row->group_id) . '">Edit Group</a>
                                            <a class="btn btn-primary btn-sm" style="margin: 3px;" href="edit_mappingmen.php?group_id=' . htmlspecialchars($row->group_id) . '">Edit Menu</a>
                                            <a class="btn btn-danger btn-sm" style="margin: 3px;" href="delete_group.php?group_id=' . htmlspecialchars($row->group_id) . '">Delete Group</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $result->free();
    $db->close();
?>

<?php include('footer.php') ?>
