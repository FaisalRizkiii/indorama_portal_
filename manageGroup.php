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


<?php 
    // Database connection
    require_once('../indorama_portal_/lib/db_login.php');

    // Set the number of records per page
    $records_per_page = 5;

    // Get the current page number from the URL, default to 1
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    if ($current_page < 1) {
        $current_page = 1;
    }

    // Calculate the offset for the SQL query
    $offset = ($current_page - 1) * $records_per_page;

    // Get the total number of records
    $total_query = "SELECT COUNT(*) as total FROM `group`";
    $total_result = $db->query($total_query);
    $total_row = $total_result->fetch_object();
    $total_records = $total_row->total;

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Fetch records for the current page
    $query = "SELECT * FROM `group` LIMIT $records_per_page OFFSET $offset";
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
                    <?php include('navLogo.php') ?>
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
                                    $no =$offset;
                                    if ($result->num_rows > 0) { 
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
                                            if ($result2) {
                                                while ($member = $result2->fetch_object()) {
                                                    $members[] = htmlspecialchars($member->name); 
                                                }
                                            }

                                            $query3 = "
                                                    SELECT c.name
                                                    FROM `group` g
                                                    JOIN mapping_categorymenu m ON g.group_id = m.group_id
                                                    JOIN category_menu c ON m.id_categorymenu = c.id_categorymenu
                                                    WHERE g.group_id = {$row->group_id}";
                                            $result3 = $db->query($query3);
                                            $catmenus = [];
                                            if ($result3) {
                                                while ($catmenu = $result3->fetch_object()) {
                                                    $catmenus[] = htmlspecialchars($catmenu->name); 
                                                }
                                            }

                                            echo '<tr>';
                                            echo '<td>' . $no . '</td>';
                                            echo '<td>' . htmlspecialchars($row->group_name) . '</td>';
                                            echo '<td>' . implode(', ', $members) . '</td>';
                                            echo '<td>' . implode(', ', $catmenus) . '</td>';
                                            echo '<td>';
                                            echo 
                                                '<a class="btn btn-primary btn-sm" style="margin: 3px;" href="edit_group.php?group_id=' . htmlspecialchars($row->group_id) . '">Edit Group</a>
                                                <a class="btn btn-primary btn-sm" style="margin: 3px;" href="edit_mapping.php?group_id=' . htmlspecialchars($row->group_id) . '">Edit Category Menu</a>
                                                <a class="btn btn-danger btn-sm" style="margin: 3px;" href="delete_group.php?group_id=' . htmlspecialchars($row->group_id) . '">Delete Group</a>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php include('pagination.php') ?> 
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
