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

        // Begin transaction
        $db->begin_transaction();
        try {
            $query = "INSERT INTO `group` (group_name) VALUES (?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("s", $group_name);
            $stmt->execute();
            $group_Id = $stmt->insert_id;

            $query_mapping = "INSERT INTO mapping_categorymenu (group_id, id_categorymenu) VALUES (?, ?)";
            $stmt_mapping = $db->prepare($query_mapping);

            foreach ($cat_menus as $id_categorymenu) {
                $stmt_mapping->bind_param("ii", $group_Id, $id_categorymenu);
                $stmt_mapping->execute();
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
    <div class="row">
        <!-- Optional Sidebar -->
        <?php include('sidebar.php'); ?>

        <!-- Main Content Area -->
        <div class="col-md-11" style="height: 100vh;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 35px; text-align: center; margin-bottom: 20px; color: #333;">
                                Add Category Menu
                            </h3>
                            <form method="POST" id="categoryForm">
                                <div class="form-group">
                                    <label for="name">Category Menu Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Menu Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="cat_menuSelect">Menu:</label>
                                    <select id="cat_menuSelect" class="form-control">
                                        <?php 
                                            require_once('../indorama_portal_/lib/db_login.php');
                                            $query2 = "SELECT * 
                                                        FROM category_menu 
                                                        WHERE id_categorymenu 
                                                        ";
                                            $result2 = $db->query($query2);
                                            if (!$result2) {
                                                die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                            }
                                            if ($result2->num_rows > 0) {
                                                while ($menu = $result2->fetch_object()) {
                                                    echo '<option value="'. $menu->id_menu .'">'. $menu->name . '</option>';
                                                }
                                            } else {
                                                echo '<option disabled>No menus available</option>';
                                            }
                                        ?>
                                    </select>
                                    <button type="button" id="addCatMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                </div>
                                <div id="selectedMenus" class="form-group">
                                    <p>Selected Menus:</p>
                                    <div class="container" style="width: 100%; height: 120px; overflow-y: auto; border: 1px solid #ccc;">
                                        <ul id="cat_menuList">
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

    document.getElementById('addCatMenuButton').addEventListener('click', function() {
        var select = document.getElementById('cat_menuSelect');
        if (select.selectedIndex == -1) return; // Nothing selected

        var selectedOption = select.options[select.selectedIndex];
        var cat_menuList = document.getElementById('cat_menuList');
        var li = document.createElement('li');
        li.classList.add('cat_menu-item'); // Add class for styling

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('cat_menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
        removeBtn.classList.add('remove-button');
        removeBtn.onclick = function() {
            cat_menuList.removeChild(li);
            select.add(new Option(selectedOption.text, selectedOption.value));
        };

        li.appendChild(span);
        li.appendChild(removeBtn);
        li.setAttribute('data-id', selectedOption.value);

        cat_menuList.appendChild(li);

        select.remove(select.selectedIndex);
    });

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        var selectedItems = document.querySelectorAll('#cat_menuList li');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'cat_menus';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });
</script>



<?php include('footer.php') ?>