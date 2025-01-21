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

    $id_categorymenu = isset($_GET['id_categorymenu']) ? intval($_GET['id_categorymenu']) : 0;

    // Fetch category menu data
    $query = "SELECT * FROM category_menu WHERE id_categorymenu = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_categorymenu);
    $stmt->execute();
    $result = $stmt->get_result();
    $category_menu = $result->fetch_object();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $url = trim($_POST['url']);
        $menus = isset($_POST['menus']) ? explode(',', $_POST['menus']) : [];
        $menusToDelete = isset($_POST['menusToDelete']) ? explode(',', $_POST['menusToDelete']) : [];

        $db->begin_transaction();
        try {
            // UPDATE NAME, IMAGE URL 
            $query = "UPDATE category_menu SET name = ?, image_url = ? WHERE id_categorymenu = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssi", $name, $url, $id_categorymenu);
            $stmt->execute();
            
            // DELETE OLD MENU
            if (!empty($menusToDelete)) {
                // Delete selected menus
                $query_delete = "DELETE FROM mapping_menu WHERE id_categorymenu = ? AND id_menu = ?";
                $stmt_delete = $db->prepare($query_delete);
                foreach ($menusToDelete as $id_menu) {
                    $stmt_delete->bind_param("ii", $id_categorymenu, $id_menu);
                    $stmt_delete->execute();
                }
            }

            // INSERT NEW MENU
            if (!empty($menus)) {
                $query_mapping = "INSERT INTO mapping_menu (id_categorymenu, id_menu) VALUES (?, ?)";
                $stmt_mapping = $db->prepare($query_mapping);

                foreach ($menus as $id_menu) {
                    if (!empty($id_menu)) { // Ensure the id_menu is not empty
                        $stmt_mapping->bind_param("ii", $id_categorymenu, $id_menu);
                        $stmt_mapping->execute();
                    }
                }
            }

            $db->commit();
            header("Location: manageCatMenu.php");
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: manageCatMenu.php");
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
                    <div class="col-md-12" >
                        <div class="form-container" 
                            style="max-width: 500px; margin: 50px auto; background: #ffffff; border-radius: 8px; 
                                    box-shadow: 4px 4px 4px 4px rgba(0, 0, 0, 0.1); padding: 30px;">
                            <h3 style="font-weight: 600; font-size: 30px; text-align: center; margin-bottom: 20px; color: #333;">
                                Editing <?php echo htmlspecialchars($category_menu->name); ?>
                            </h3>
                            <form method="POST" id="categoryForm">
                            <div class="form-group">
                                    <label for="name">Name :</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category_menu->name); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="url">Image URL :</label>
                                    <input type="text" id="url" name="url" class="form-control" value="<?php echo htmlspecialchars($category_menu->image_url); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="menuDelete">Menu To Delete :</label>
                                    <select id="menuDelete" class="form-control">
                                        <?php 
                                            require_once('../indorama_portal_/lib/db_login.php');
                                            $query2 = " SELECT * 
                                                        FROM menu 
                                                        WHERE id_menu 
                                                        IN (
                                                                SELECT id_menu 
                                                                FROM mapping_menu 
                                                                WHERE id_categorymenu = $id_categorymenu
                                                            )";
                                            $result2 = $db->query($query2);
                                            if (!$result2) {
                                                die("Could not query the database: <br>" . $db->error . '<br>Query: ' . $query2);
                                            }
                                            if ($result2->num_rows > 0) {
                                                while ($menu = $result2->fetch_object()) {
                                                    echo '<option value="' . $menu->id_menu . '">' . $menu->name . '</option>';
                                                }
                                            } else {
                                                echo '<option disabled>No menus available</option>';
                                            }
                                        ?>
                                    </select>
                                    <button type="button" id="deleteMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                </div>
                                <div id="deleteMenus" class="form-group">
                                    <p>Selected Menus to Delete:</p>
                                    <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius:10px">
                                        <ul id="menuListDelete">
                                            <!-- List  -->
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="menuSelect">Menu To Add:</label>
                                    <select id="menuSelect" class="form-control">
                                        <?php 
                                            require_once('../indorama_portal_/lib/db_login.php');
                                            $query2 = "SELECT * 
                                                        FROM menu 
                                                        WHERE id_menu 
                                                        NOT IN (
                                                                SELECT id_menu 
                                                                FROM mapping_menu
                                                                )
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
                                    <button type="button" id="addMenuButton" class="btn btn-primary" style="margin-top: 10px;">Add</button>
                                </div>
                                <div id="selectedMenus" class="form-group">
                                    <p>Selected Menus:</p>
                                    <div class="container" style="width: 100%; height: 80px; overflow-y: auto; border: 1px solid #ccc; border-radius: 10px;">
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
        if (select.selectedIndex == -1) return;

        var selectedOption = select.options[select.selectedIndex];
        var menuList = document.getElementById('menuList');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
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
        hiddenInput.name = 'menus';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });
</script>

<script>
    document.getElementById('deleteMenuButton').addEventListener('click', function() {
        var select = document.getElementById('menuDelete');
        if (select.selectedIndex == -1) return;

        var selectedOption = select.options[select.selectedIndex];
        var menuList = document.getElementById('menuListDelete');
        var li = document.createElement('li');
        li.classList.add('menu-item');

        var span = document.createElement('span');
        span.textContent = selectedOption.text;
        span.classList.add('menu-text');

        var removeBtn = document.createElement('button');
        removeBtn.innerHTML = '✕';
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
        var selectedItems = document.querySelectorAll('#menuListDelete li');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'menusToDelete';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });

</script>



<?php include('footer.php') ?>