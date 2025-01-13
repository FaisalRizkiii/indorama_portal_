<?php include('header.php') ?>

<?php
    session_start();

    // Check if the user is logged in by checking the session
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    $id = $_SESSION['id'];

    // Database connection
    require_once('../indorama_portal_/lib/db_login.php');

    // Fetch the user's name from the database using the id
    $query = "SELECT name FROM user WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
    } else {
        // If no user found or error occurred, you can set a default value
        $name = 'Guest';
    }

    // Close the statement and database connection
    $stmt->close();
    $db->close();
    
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
                            <h3 style="font-weight: 600; font-size: 40px">Welcome <?php echo htmlspecialchars($name) ?> 👋</h3>
                        </div>
                        <!-- Modul Row -->
                        <div class="row" style="height: 500px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; background-color: #F8F8F8; border-radius: 30px;">
                            <div style="margin: 20px;">
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent1" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent2" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent3" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent4" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent5" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent6" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent7" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php include('footer.php') ?>