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
    $email = $db->real_escape_string($_POST['email']);
    $password = $db->real_escape_string($_POST['password']);
    $role = $db->real_escape_string($_POST['role']);

    // Hash the password (use `password_hash` for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $query = "INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Redirect to the user list page after success
        header("Location: manageUser.php");
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
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 35px; text-align: center; margin-bottom: 20px; color: #333;">
                                Add User
                            </h3>
                            <form method="POST">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" id="role" name="role" class="form-control" placeholder="Choose Role" required>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" 
                                            style="background-color: #007bff; border-color: #007bff; margin-right: 10px;">Add User</button>
                                    <a href="manageUser.php" class="btn btn-primary" 
                                    style="background-color: #6c757d; border-color: #6c757d;">Cancel</a>
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
