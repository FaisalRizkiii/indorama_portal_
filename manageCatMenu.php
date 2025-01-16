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
    $query = "SELECT * FROM category_menu";
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $no = 0;
                                if ($result->num_rows > 0){ 
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
                                            echo '<td>';
                                            echo '
                                                <a class="btn btn-primary btn-sm" href="edit_catmenu.php?id_categorymenu='.$row->id_categorymenu.'">Edit</a>&nbsp;&nbsp;
                                                <a class="btn btn-danger btn-sm" href="delete_catmenu.php?id_categorymenu='.$row->id_categorymenu.'" onclick="return confirm(\'Are you sure you want to delete this category?\')">Delete</a>';
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
