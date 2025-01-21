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
            $stmt->bind_param("ssssi", $name, $email, md5($password), $role, $id);
        }

        if ($stmt->execute()) {
            // Redirect back to the list page after a successful update
            header("Location: manageUser.php");
            exit;
        } else {
            echo "<p>Error updating user: " . $stmt->error . "</p>";
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
        <div class="col-md-11">
            <div class="container" >
                <div class="row">
                <?php include('navLogo.php'); ?>
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 30px; text-align: center; margin-bottom: 30px; color: #333;">
                                Editing <?php echo htmlspecialchars($user->name); ?>
                            </h3>
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
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="manageUser.php" class="btn btn-primary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('footer.php') ?>
