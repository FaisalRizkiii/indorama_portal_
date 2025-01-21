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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name']);
        $url = trim($_POST['url']);
        $menus = isset($_POST['menus']) ? explode(',', $_POST['menus']) : [];

        if (empty($menus)) {
            $_SESSION['error'] = "Please select at least one menu.";
            header("Location: add_CatMenu.php");
            exit();
        }

        $query_check = "SELECT COUNT(*) FROM Category_Menu WHERE name = ?";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bind_param("s", $name);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            $_SESSION['error'] = "Category menu name already exists.";
            header("Location: add_CatMenu.php");
            exit();
        }

        $db->begin_transaction();
        try {
            $query = "INSERT INTO Category_Menu (name, image_url) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ss", $name, $url);
            $stmt->execute();
            $id_categorymenu = $stmt->insert_id;

            $query_mapping = "INSERT INTO mapping_menu (id_categorymenu, id_menu) VALUES (?, ?)";
            $stmt_mapping = $db->prepare($query_mapping);

            foreach ($menus as $id_menu) {
                $stmt_mapping->bind_param("ii", $id_categorymenu, $id_menu);
                $stmt_mapping->execute();
            }

            $db->commit();
            header("Location: manageCatMenu.php");
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: add_CatMenu.php");
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
                                Add Category Menu
                            </h3>
                            <form method="POST" id="categoryForm">
                                <div class="form-group">
                                    <label for="name">Category Menu Name:</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Menu Name" required>
                                </div>
                                <div class="form-group">
                                    <label for="url">Image URL:</label>
                                    <input type="text" id="url" name="url" class="form-control" placeholder="Enter Image URL" required>
                                </div>
                                <div class="form-group">
                                    <label for="menuSelect">Menu:</label>
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
        hiddenInput.name = 'menus';
        hiddenInput.value = Array.from(selectedItems).map(item => item.getAttribute('data-id')).join(',');

        this.appendChild(hiddenInput);
    });
</script>



<?php include('footer.php') ?>