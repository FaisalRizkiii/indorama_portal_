<?php 
    session_start();
    require_once('header.php');
    require_once('../indorama_portal_/lib/db_login.php');

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

    // Fetch category menu data
    $query = "SELECT * FROM `group` WHERE group_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_object();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $group_name = trim($_POST['group_name']);
        $users = isset($_POST['users']) ? explode(',', $_POST['users']) : [];
        $catmenus = isset($_POST['catmenus']) ? explode(',', $_POST['catmenus']) : [];
        $usersToDelete = isset($_POST['usersToDelete']) ? explode(',', $_POST['usersToDelete']) : [];
        $catmenusToDelete = isset($_POST['catmenusToDelete']) ? explode(',', $_POST['catmenusToDelete']) : [];

        $db->begin_transaction();
        try {
            // UPDATE NAME  
            $query = "UPDATE `group` SET group_name = ? WHERE group_id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("si", $group_name, $group_id);
            $stmt->execute();
            
            // DELETE OLD USER
            if (!empty($usersToDelete)) {
                // Delete selected menus
                $query_delete = "DELETE FROM group_members WHERE group_id = ? AND user_id = ?";
                $stmt_delete = $db->prepare($query_delete);
                foreach ($usersToDelete as $user_id) {
                    $stmt_delete->bind_param("ii", $group_id, $user_id);
                    $stmt_delete->execute();
                }
            }

            // DELETE OLD CATEGORY MENUS
            if (!empty($catmenusToDelete)) {
                // Delete selected menus
                $query_delete = "DELETE FROM mapping_categorymenu WHERE group_id = ? AND id_categorymenu = ?";
                $stmt_delete = $db->prepare($query_delete);
                foreach ($catmenusToDelete as $id_categorymenu) {
                    $stmt_delete->bind_param("ii", $group_id, $id_categorymenu);
                    $stmt_delete->execute();
                }
            }

            // INSERT NEW USER
            if (!empty($users)) {
                $query_mapping = "INSERT INTO group_members (group_id, user_id) VALUES (?, ?)";
                $stmt_mapping = $db->prepare($query_mapping);
            
                foreach ($users as $user_id) {
                    $stmt_mapping->bind_param("ii", $group_id, $user_id);
                    $stmt_mapping->execute();
                }
            }

            // INSERT NEW CATEGORY MENUS
            if (!empty($catmenus)) {
                $query_mapping = "INSERT INTO mapping_categorymenu (group_id, id_categorymenu) VALUES (?, ?)";
                $stmt_mapping = $db->prepare($query_mapping);
            
                foreach ($catmenus as $id_categorymenu) {
                    $stmt_mapping->bind_param("ii", $group_id, $id_categorymenu);
                    $stmt_mapping->execute();
                }
            }
        
            $db->commit();
            header("Location: manageGroup.php");
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: manageGroup.php");
            exit();
        } finally {
            $stmt->close();
            $stmt_mapping->close();
            $db->close();
        }
    }
?>

<div class="container-fluid">
    <div class="row" style="display: flex; flex-wrap: nowrap;">
        <!-- Optional Sidebar -->
        <?php include('sidebar.php') ?>

        <!-- Main Content Area -->
        <div class="col-md-11">
            <div class="container">
                <div class="row">
                    <?php include('navLogo.php'); ?>
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 30px; text-align: center; margin-bottom: 20px; color: #333;">
                                Editing <?php echo htmlspecialchars($group->group_name); ?>
                            </h3>
                            <form method="POST" id="categoryForm">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group ">
                                            <label for="group_name">Name :</label>
                                            <input type="text" id="group_name" name="group_name" class="form-control" value="<?php echo htmlspecialchars($group->group_name); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="userDelete">Member To Delete :</label>
                                            <select id="userDelete" class="form-control">
                                                <?php 
                                                    require_once('../indorama_portal_/lib/db_login.php');
                                                    $query2 = "SELECT * 
                                                                FROM user
                                                                WHERE id 
                                                                IN (
                                                                    SELECT user_id
                                                                    FROM group_members
                                                                    WHERE group_id = $group_id
                                                                    )
                                                                ";
                                                    $result2 = $db->query($query2);
                                                    if (!$result2) {
                                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                                    }
                                                    if ($result2->num_rows > 0) {
                                                        while ($users = $result2->fetch_object()) {
                                                            echo '<option value="'. $users->id .'">'. $users->name . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option disabled>No menus available</option>';
                                                    }
                                                    ?>
                                            </select>
                                            <button type="button" id="deleteUserButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                        </div>
                                        <div id="deleteMenus" class="form-group">
                                            <p>Selected Member to Delete:</p>
                                            <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius:10px">
                                                <ul id="userListDelete">
                                                    <!-- List  -->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="userSelect">Member To Add:</label>
                                            <select id="userSelect" class="form-control">
                                                <?php 
                                                    require_once('../indorama_portal_/lib/db_login.php');
                                                    $query = "SELECT id, name 
                                                                FROM user 
                                                                WHERE role = 'user' 
                                                                AND id NOT IN ( 
                                                                            SELECT user_id
                                                                            FROM group_members
                                                                            WHERE group_id = $group_id
                                                                            ) 
                                                                ";
                                                    $result = $db->query($query);
                                                    if (!$result) {
                                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query);
                                                    }
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_object()) {
                                                            echo '<option value="'. $row->id .'">'. $row->name . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option disabled>No Users available</option>';
                                                    }
                                                    ?>
                                            </select>
                                            <button type="button" id="addUserButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                        </div>
                                        <div id="selectedMenus" class="form-group">
                                            <p>Selected Member to Add:</p>
                                            <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius: 10px;">
                                                <ul id="userList">
                                                    <!-- List -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"> 
                                        <div class="form-group">
                                            <label for="catMenuDelete">Category Menu To Delete :</label>
                                            <select id="catMenuDelete" class="form-control">
                                                <?php 
                                                    require_once('../indorama_portal_/lib/db_login.php');
                                                    $query2 = " SELECT * 
                                                                FROM category_menu
                                                                WHERE id_categorymenu 
                                                                IN (
                                                                    SELECT id_categorymenu
                                                                    FROM mapping_categorymenu
                                                                    WHERE group_id = $group_id
                                                                    )
                                                                ";
                                                    $result2 = $db->query($query2);
                                                    if (!$result2) {
                                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                                    }
                                                    if ($result2->num_rows > 0) {
                                                        while ($catmenu = $result2->fetch_object()) {
                                                            echo '<option value="'. $catmenu->id_categorymenu .'">'. $catmenu->name . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option disabled>No menus available</option>';
                                                    }
                                                    ?>
                                            </select>
                                            <button type="button" id="deleteCatMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                        </div>
                                        <div id="deleteCatMenu" class="form-group">
                                            <p>Selected Member to Delete:</p>
                                            <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius:10px">
                                                <ul id="catMenuListDelete">
                                                    <!-- List  -->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="catMenuAdd">Category Menu To Add :</label>
                                            <select id="catMenuAdd" class="form-control">
                                                <?php 
                                                    require_once('../indorama_portal_/lib/db_login.php');
                                                    $query2 = " SELECT * 
                                                                FROM category_menu
                                                                WHERE id_categorymenu 
                                                                NOT IN (
                                                                    SELECT id_categorymenu
                                                                    FROM mapping_categorymenu
                                                                    )
                                                                ";
                                                    $result2 = $db->query($query2);
                                                    if (!$result2) {
                                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                                    }
                                                    if ($result2->num_rows > 0) {
                                                        while ($catmenu = $result2->fetch_object()) {
                                                            echo '<option value="'. $catmenu->id_categorymenu .'">'. $catmenu->name . '</option>';
                                                        }
                                                    } else {
                                                        echo '<option disabled>No menus available</option>';
                                                    }
                                                    ?>
                                            </select>
                                            <button type="button" id="addCatMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                        </div>
                                        <div id="addCatMenu" class="form-group">
                                            <p>Selected Member to Delete:</p>
                                            <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius:10px">
                                                <ul id="catMenuListAdd">
                                                    <!-- List  -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Submit Category Menu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        // Checking for errors on page load and displaying them if any
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_SESSION['error']; ?>',
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    };

    // Add Menu Functionality
    document.getElementById('addUserButton').addEventListener('click', function () {
        var select = document.getElementById('userSelect');
        if (select.selectedIndex === -1) {
            alert("Please select a menu to add.");
            return;
        }

        var selectedOption = select.options[select.selectedIndex];
        var userList = document.getElementById('userList');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function () {
            userList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        userList.appendChild(li);
        select.remove(select.selectedIndex);
    });

    // Delete Menu Functionality
    document.getElementById('deleteUserButton').addEventListener('click', function () {
        var select = document.getElementById('userDelete');
        if (select.selectedIndex === -1) {
            alert("Please select a menu to delete.");
            return;
        }

        var selectedOption = select.options[select.selectedIndex];
        var userList = document.getElementById('userListDelete');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function () {
            userList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        userList.appendChild(li);
        select.remove(select.selectedIndex);
    });

    // Delete Category Menu Functionality
    document.getElementById('deleteCatMenuButton').addEventListener('click', function () {
        var select = document.getElementById('catMenuDelete');
        if (select.selectedIndex === -1) {
            alert("Please select a menu to delete.");
            return;
        }

        var selectedOption = select.options[select.selectedIndex];
        var userList = document.getElementById('catMenuListDelete');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function () {
            userList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        userList.appendChild(li);
        select.remove(select.selectedIndex);
    });

    // Delete Category Menu Functionality
    document.getElementById('addCatMenuButton').addEventListener('click', function () {
        var select = document.getElementById('catMenuAdd');
        if (select.selectedIndex === -1) {
            alert("Please select a menu to delete.");
            return;
        }

        var selectedOption = select.options[select.selectedIndex];
        var userList = document.getElementById('catMenuListAdd');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function () {
            userList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        userList.appendChild(li);
        select.remove(select.selectedIndex);
    });

    // Unified Form Submit Handling for Insert and Delete
    document.getElementById('categoryForm').addEventListener('submit', function (e) {
        // Clear any old hidden inputs to prevent duplicates
        Array.from(this.querySelectorAll('input[type=hidden]')).forEach(input => input.remove());

        // Handle Insert Users
        var selectedInsertItems = document.querySelectorAll('#userList li');
        if (selectedInsertItems.length > 0) {
            var inputtedInsert = document.createElement('input');
            inputtedInsert.type = 'hidden';
            inputtedInsert.name = 'users';
            inputtedInsert.value = Array.from(selectedInsertItems).map(item => item.getAttribute('data-id')).join(',');
            this.appendChild(inputtedInsert);
        }

        // Handle Insert Category Menus
        var selectedInsertItems = document.querySelectorAll('#catMenuListAdd li');
        if (selectedInsertItems.length > 0) {
            var inputtedInsert = document.createElement('input');
            inputtedInsert.type = 'hidden';
            inputtedInsert.name = 'users';
            inputtedInsert.value = Array.from(selectedInsertItems).map(item => item.getAttribute('data-id')).join(',');
            this.appendChild(inputtedInsert);
        }

        // Handle Delete Users
        var selectedDeleteItems = document.querySelectorAll('#userListDelete li');
        if (selectedDeleteItems.length > 0) {
            var inputtedDelete = document.createElement('input');
            inputtedDelete.type = 'hidden';
            inputtedDelete.name = 'usersToDelete';
            inputtedDelete.value = Array.from(selectedDeleteItems).map(item => item.getAttribute('data-id')).join(',');
            this.appendChild(inputtedDelete);
        }

        // Handle Delete Category Menus
        var selectedDeleteItems = document.querySelectorAll('#catMenuListDelete li');
        if (selectedDeleteItems.length > 0) {
            var inputtedDelete = document.createElement('input');
            inputtedDelete.type = 'hidden';
            inputtedDelete.name = 'catmenusToDelete';
            inputtedDelete.value = Array.from(selectedDeleteItems).map(item => item.getAttribute('data-id')).join(',');
            this.appendChild(inputtedDelete);
        }
    
    });
</script>




<?php include('footer.php') ?>