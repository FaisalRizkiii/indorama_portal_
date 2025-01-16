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
    $query = "SELECT * FROM menu";
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
                            <h3 style="font-weight: 600; font-size: 30px">Manage Menu</h3>
                        </div>
                        <a href="add_menu.php" class="btn btn-success" style="margin-bottom: 15px   ;">
                            <i class="fas fa-user-plus"></i> Add New Menu
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover" style="text-align: center;">
                            <thead>
                                <tr class="info">
                                    <th>No</th>
                                    <th>Menu Name</th>
                                    <th>Url</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 0;
                                    while ($row = $result->fetch_object()) {
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>'. $no . '</td>';
                                        echo '<td>'. $row->name . '</td>';
                                        echo '<td>'. $row->URL . '</td>';
                                        echo '<td>';
                                        echo 
                                            '
                                            <a class="btn btn-primary btn-sm" href="edit_menu.php?id_menu='.$row->id_menu.'">Edit</a>&nbsp;&nbsp;
                                            <a class="btn btn-danger btn-sm" href="delete_menu.php?id_menu='.$row->id_menu.'">Delete</a>
                                            ';
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
