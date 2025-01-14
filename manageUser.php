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
    $records_per_page = 10;

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
    $query = "SELECT * FROM user LIMIT $records_per_page OFFSET $offset";
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
                                $no = $offset; // Initialize the counter starting from the offset
                                while ($row = $result->fetch_object()) {
                                    $no++; // Increment the counter
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
                                        <a class="btn btn-danger btn-sm" href="delete_user.php?id='.$row->id.'">Delete</a>
                                        ';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                        <div>
                            <?php
                            // Generate pagination links
                            if ($total_pages > 1) {
                                echo '<nav>';
                                echo '<ul class="pagination">';
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    if ($i == $current_page) {
                                        echo '<li class="page-item active"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
                                    }
                                }
                                echo '</ul>';
                                echo '</nav>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    // Free result and close the database connection
    $result->free();
    $total_result->free();
    $db->close();
?>

<?php include('footer.php') ?>
