<?php include('header.php')?>
<?php
    session_start();

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] != 'admin' ){
        header("Location: index.php");
        exit();
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
    $total_query = "SELECT COUNT(*) as total FROM user";
    $total_result = $db->query($total_query);
    $total_row = $total_result->fetch_object();
    $total_records = $total_row->total;

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Fetch records for the current page
    $query = "SELECT * FROM user ORDER BY name LIMIT $records_per_page OFFSET $offset";
    $result = $db->query($query);
    if (!$result) {
        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
    }
?>

<div class="container-fluid">
    <div class="row" style="display: flex; flex-wrap: nowrap;">
        <!-- Sidebar -->
        <?php include('sidebar.php') ?>
        <!-- Main -->
        <div class="col-md-12">
            <div class="container" >
                <div class="row">
                    <?php include('navLogo.php') ?>
                    <div class="col-md-12" >
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 30px">Manage Users</h3>
                        </div>
                        <a href="add_user.php" class="btn btn-success" style="margin-bottom: 15px   ;">
                            <i class="fas fa-user-plus"></i> Add New User
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover" style="text-align: center;">
                            <thead>
                                <tr class="info">
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = $offset;
                                    if ($result->num_rows > 0){  
                                        while ($row = $result->fetch_object()) {
                                            $no++;
                                            echo '<tr>';
                                                echo '<td>'. $no . '</td>';
                                                echo '<td>'. $row->id . '</td>';
                                                echo '<td>'. $row->name . '</td>';
                                                echo '<td>'. $row->email . '</td>';
                                                echo '<td>'. $row->role . '</td>';
                                                echo '<td>';
                                                echo 
                                                    '
                                                    <a class="btn btn-primary btn-sm" href="edit_user.php?id='.$row->id.'">Edit</a>&nbsp;&nbsp;
                                                    <a  class="delete-btn btn btn-danger btn-sm" href="delete_user.php?id='.$row->id.'"  onclick="return confirm(\'Are you sure you want to delete this user?\')"> Delete</a>
                                                    ';
                                                echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6" class="text-center">No data available</td></tr>';
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php include('pagination.php'); ?>
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