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
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="info">
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // Database connection
                                    require_once('../indorama_portal_/lib/db_login.php');

                                    $query = " SELECT * FROM user";

                                    $result = $db->query($query);
                                    if (!$result) {
                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
                                    }

                                    while ($row = $result->fetch_object()) {
                                        echo '<tr>';
                                        echo '<td>'. $row->id . '</td>';
                                        echo '<td>'. $row->name . '</td>';
                                        echo '<td>'. $row->email . '</td>';
                                        echo '<td>'. $row->password . '</td>';
                                        echo '<td>'. $row->role . '</td>';
                                        echo '<td></td>';
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