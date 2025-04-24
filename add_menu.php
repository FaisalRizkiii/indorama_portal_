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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect form data
        $name = $db->real_escape_string($_POST['name']);
        $URL = $db->real_escape_string($_POST['URL']);


        // Insert new user into the database
        $query = "INSERT INTO menu (name, URL) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ss", $name, $URL);

        if ($stmt->execute()) {
            header("Location: manageMenu.php");
            exit;
        } else {
            echo "<p>Error adding user: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $db->close();
?>


<div class="container-fluid">
    <div class="row" style="display: flex; flex-wrap: nowrap;">
        <!-- Sidebar -->
        <?php include('sidebar.php') ?>
        <!-- Main -->
        <div class="col-md-12">
            <div class="container" >
                <div class="row">
                    <?php include('navLogo.php'); ?>
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 35px; text-align: center; margin-bottom: 20px; color: #333;">
                                Add Menu
                            </h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="name">Menu Name :</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Menu Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="URL">URL</label>
                                    <input type="text" id="URL" name="URL" class="form-control" placeholder="Enter URL" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" style="background-color: #007bff; border-color: #007bff; margin-right: 10px;">
                                        Add Menu
                                    </button>
                                    <a href="manageMenu.php" class="btn btn-primary" style="background-color: #6c757d; border-color: #6c757d;">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>