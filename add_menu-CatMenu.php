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

    // Get Menu ID from query string
    $id = isset($_GET['id_categorymenu']) ? intval($_GET['id_categorymenu']) : 0;


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect form data
        $id_menu = $db->real_escape_string($_POST['id_menu']);

        // Insert new user into the database
        $query = "INSERT INTO mapping_menu (id_categorymenu, id_menu) VALUES (?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $id, $id_menu);

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
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 35px; text-align: center; margin-bottom: 20px; color: #333;">
                                Add Menu
                            </h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="id_menu">Menu :</label>
                                    <select id="id_menu" name="id_menu" class="form-control" required>
                                    <?php 
                                        // Database connection
                                        require_once('../indorama_portal_/lib/db_login.php');

                                        // Fetch records for the current page
                                        $query2 = "SELECT * FROM menu";
                                        $result2 = $db->query($query2); // Ensure this variable matches the one in the while loop
                                        if (!$result2) {
                                            die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                        }

                                        while ($menu = $result2->fetch_object()) {
                                            echo '<option value="'. $menu->id_menu .'">'. $menu->name . '</option>';
                                        }
                                    ?>
                                    </select>
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