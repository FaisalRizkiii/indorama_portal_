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
                        <a href="add_CatMenu.php" class="btn btn-success" style="margin-bottom: 15px   ;">
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
                                while ($row = $result->fetch_object()) {
                                    $no++;
                                    // Get Group members
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
                                        echo 
                                            '<a class="btn btn-primary btn-sm" style="margin: 3px;" href="edit_mappingmen.php?group_id=">Edit</a>
                                            <a class="btn btn-danger btn-sm" style="margin: 3px;" href="delete_group.php?group_id=">Delete</a>';
                                        echo '</td>';
                                    echo '</tr>';
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

<?php include('footer.php') ?>
