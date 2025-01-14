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

    // Get user ID from query string
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Fetch user data
    $query = "SELECT * FROM user WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("User not found!");
    }

    $user = $result->fetch_object();
    $stmt->close();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update user data
        $name = $db->real_escape_string($_POST['name']);
        $email = $db->real_escape_string($_POST['email']);
        $password = $db->real_escape_string($_POST['password']);
        $role = $db->real_escape_string($_POST['role']);

        // If password is empty, keep the existing password
        if (empty($password)) {
            $updateQuery = "UPDATE user SET name = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $db->prepare($updateQuery);
            $stmt->bind_param("sssi", $name, $email, $role, $id);
        } else {
            // Update with a new password
            $updateQuery = "UPDATE user SET name = ?, email = ?, password = ?, role = ? WHERE id = ?";
            $stmt = $db->prepare($updateQuery);
            $stmt->bind_param("ssssi", $name, $email, $password, $role, $id);
        }

        if ($stmt->execute()) {
            // Redirect back to the list page after a successful update
            header("Location: index.php");
            exit;
        } else {
            echo "<p>Error updating user: " . $stmt->error . "</p>";
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
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 35px">Editing <?php echo htmlspecialchars($user->name); ?></h3>
                        </div>
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user->name); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user->email); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($user->role); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>
