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
    $id = isset($_GET['id_menu']) ? intval($_GET['id_menu']) : 0;

    // Fetch Menu data
    $query = "SELECT * FROM menu WHERE id_menu = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Menu not found!");
    }

    $Menu = $result->fetch_object();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update Menu data
        $name = $db->real_escape_string($_POST['name']);
        $URL = $db->real_escape_string($_POST['URL']);


        $updateQuery = "UPDATE menu SET name = ?, URL = ? WHERE id_menu = ?";
        $stmt = $db->prepare($updateQuery);
        $stmt->bind_param("ssi", $name, $URL, $id);

        if ($stmt->execute()) {
            header("Location: manageMenu.php");
            exit;
        } else {
            echo "<p>Error updating Menu: " . $stmt->error . "</p>";
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
                            <h3 style="font-weight: 600; font-size: 30px; text-align: center; margin-bottom: 30px; color: #333;">
                                Editing Menu <?php echo htmlspecialchars($Menu->name); ?>
                            </h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="name">Menu Name :</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($Menu->name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="URL">URL :</label>
                                    <input type="text" id="URL" name="URL" class="form-control" value="<?php echo htmlspecialchars($Menu->URL); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="manageMenu.php" class="btn btn-primary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>
