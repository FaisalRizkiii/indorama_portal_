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

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include('sidebar.php') ?>
        <!-- Main -->
        <div class="col-md-11" style="height: 100vh;">
            <div class="container" >
                <div class="row">
                    <!-- NavLogo -->
                    <?php include('navLogo.php') ?>
                    <div class="col-md-12" >
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 35px">Manage Users</h3>
                        </div>
                        <a href="add_user.php" class="btn btn-success" style="margin-bottom: 10px   ;">
                            <i class="fas fa-user-plus"></i> Add New User
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="info">
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // Database connection
                                    require_once('../indorama_portal_/lib/db_login.php');

                                    $query = "SELECT * FROM user";

                                    $result = $db->query($query);
                                    if (!$result) {
                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
                                    }

                                    while ($row = $result->fetch_object()) {
                                        echo '<tr>';
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

                                    $result->free();
                                    $db->close();
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
