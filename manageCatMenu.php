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
    $total_query = "SELECT COUNT(*) as total FROM category_menu";
    $total_result = $db->query($total_query);
    $total_row = $total_result->fetch_object();
    $total_records = $total_row->total;

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $records_per_page);

    // Fetch records for the current page
    $query = "SELECT * FROM category_menu LIMIT $records_per_page OFFSET $offset";
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
                            <h3 style="font-weight: 600; font-size: 30px">Manage Category Menu</h3>
                        </div>
                        <a href="add_catmenu.php" class="btn btn-success" style="margin-bottom: 15px   ;">
                            <i class="fas fa-user-plus"></i> Add New Category Menu
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover" style="text-align: center;">
                            <thead>
                                <tr class="info">
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Menu</th>
                                    <th>Image URL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if ($result->num_rows > 0){ 
                                    $no = $offset;
                                    while ($row = $result->fetch_object()) {
                                        $no++;
                                        $query2 = "SELECT m.name 
                                                    FROM menu m
                                                    JOIN mapping_menu mm 
                                                    ON m.id_menu = mm.id_menu 
                                                    WHERE mm.id_categorymenu = {$row->id_categorymenu}";
                                        $result2 = $db->query($query2);
                                        $menus = [];
                                        while ($menu = $result2->fetch_object()) {
                                            $menus[] = htmlspecialchars($menu->name); 
                                        }

                                        echo '<tr>';
                                            echo '<td>' . $no . '</td>';
                                            echo '<td>' . htmlspecialchars($row->name) . '</td>';
                                            echo '<td>' . implode(', ', $menus) . '</td>';
                                            echo '<td>' . $row->image_url .'</td>';
                                            echo '<td>';
                                            echo '
                                                <a class="btn btn-primary btn-sm" href="edit_catmenu.php?id_categorymenu='.$row->id_categorymenu.'">Edit</a>&nbsp;&nbsp;
                                                <a class="btn btn-danger btn-sm" href="delete_catmenu.php?id_categorymenu='.$row->id_categorymenu.'" onclick="return confirm(\'Are you sure you want to delete this category?\')">Delete</a>';
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
