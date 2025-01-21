<?php 
    session_start();
    include('header.php');
    require_once('../indorama_portal_/lib/db_login.php');

    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] != 'admin') {
        header("Location: index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $group_name = trim($db->real_escape_string($_POST['group_name']));
        $cat_menus = isset($_POST['cat_menus']) ? explode(',', $db->real_escape_string($_POST['cat_menus'])) : [];
        $group_members = isset($_POST['group_members']) ? explode(',', $db->real_escape_string($_POST['group_members'])) : [];
        

        // Begin transaction
        $db->begin_transaction();
        try {
            $query = "INSERT INTO `group` (group_name) VALUES (?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $group_name);
            $stmt->execute();
            $group_Id = $stmt->insert_id;

            if (!empty($group_members)) {
                $query_mapping = "INSERT INTO group_members (group_id, user_id) VALUES (?, ?)";
                $stmt_mapping = $db->prepare($query_mapping);

                foreach ($group_members as $user_id) {
                    $stmt_mapping->bind_param("ii", $group_Id, $user_id);
                    $stmt_mapping->execute();
                }
            } 
            
            if (!empty($cat_menus)) {
                $query_mapping = "INSERT INTO mapping_categorymenu (group_id, id_categorymenu) VALUES (?, ?)";
                $stmt_mapping = $db->prepare($query_mapping);

                foreach ($cat_menus as $id_categorymenu) {
                    $stmt_mapping->bind_param("ii", $group_Id, $id_categorymenu);
                    $stmt_mapping->execute();
                }
            }   

            $db->commit();
            header("Location: manageGroup.php");
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $_SESSION['error'] = "error :" . $e->getMessage();
            header("Location: add_Group.php");
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
        <?php include('sidebar.php'); ?>

        <!-- Main Content Area -->
        <div class="col-md-12">
            <div class="container">
                <div class="row">
                    <?php include('navLogo.php'); ?>
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 35px; text-align: center; margin-bottom: 20px; color: #333;">
                                Add Group
                            </h3>
                            <form method="POST" id="categoryForm">
                                <div class="form-group">
                                    <label for="group_name">Group Name:</label>
                                    <input type="text" id="group_name" name="group_name" class="form-control" placeholder="Enter Group Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="group_member">Add User :</label>
                                    <select id="group_member" class="form-control">
                                        <?php 
                                            require_once('../indorama_portal_/lib/db_login.php');
                                            $query = "SELECT id, name FROM user WHERE role = 'user'";
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
                                    <button type="button" id="addUser" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                </div>
                                <div id="selectedUser" class="form-group">
                                    <p>Selected User:</p>
                                    <div class="container" style="width: 100%; height: 120px; overflow-y: auto; border: 1px solid #ccc;">
                                        <ul id="member_grouplist">
                                            <!-- List -->
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="menuSelect">Select Category Menu :</label>
                                    <select id="menuSelect" class="form-control">
                                        <?php 
                                            require_once('../indorama_portal_/lib/db_login.php');
                                            $query2 = "SELECT * 
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
                                                while ($menu = $result2->fetch_object()) {
                                                    echo '<option value="'. $menu->id_categorymenu .'">'. $menu->name . '</option>';
                                                }
                                            } else {
                                                echo '<option disabled>No Category Menus Left</option>';
                                            }
                                        ?>
                                    </select>
                                    <button type="button" id="addMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                </div>
                                <div id="selectedMenus" class="form-group">
                                    <p>Selected Category Menus:</p>
                                    <div class="container" style="width: 100%; height: 120px; overflow-y: auto; border: 1px solid #ccc;">
                                        <ul id="menuList">
                                            <!-- List -->
                                        </ul>
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
    window.onload = function() {
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_SESSION['error']; ?>',
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    }

    document.getElementById('addMenuButton').addEventListener('click', function() {
        var select = document.getElementById('menuSelect');
        if (select.selectedIndex == -1) return; // Nothing selected

        var selectedOption = select.options[select.selectedIndex];
        var menuList = document.getElementById('menuList');
        var li = document.createElement('li');
        li.classList.add('menu-item'); // Add class for styling

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕'; // Using a simple multiplication sign as a remove icon
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function() {
            menuList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        menuList.appendChild(li);

        select.remove(select.selectedIndex);
    });

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        var selectedItems = document.querySelectorAll('#menuList li');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'cat_menus';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });
</script>

<script>
    window.onload = function() {
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo $_SESSION['error']; ?>',
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    }

    document.getElementById('addUser').addEventListener('click', function() {
        var select = document.getElementById('group_member');
        if (select.selectedIndex == -1) return; // Nothing selected

        var selectedOption = select.options[select.selectedIndex];
        var userList = document.getElementById('member_grouplist');
        var li = document.createElement('li');
        li.classList.add('menu-item'); // Reuse existing class for consistency

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕'; // Using a simple multiplication sign as a remove icon
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function() {
            userList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        userList.appendChild(li);

        select.remove(select.selectedIndex);
    });

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        var selectedItems = document.querySelectorAll('#member_grouplist li');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'group_members';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });
</script>

<?php include('footer.php') ?>