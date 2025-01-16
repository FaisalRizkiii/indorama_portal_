<?php include('header.php'); ?>
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

    require_once('../indorama_portal_/lib/db_login.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $db->real_escape_string($_POST['name']);
        $menus = explode(',', $db->real_escape_string($_POST['menus']));

        // Insert new category menu into the database
        $query = "INSERT INTO Category_Menu (name) VALUES (?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $id_categorymenu = $stmt->insert_id;  // Get the last inserted id

            $query_mapping = "INSERT INTO mapping_menu (id_categorymenu, id_menu) VALUES (?, ?)";
            $stmt_mapping = $db->prepare($query_mapping);

            // Handle multiple menu IDs
            foreach ($menus as $id_menu) {
                $stmt_mapping->bind_param("ii", $id_categorymenu, $id_menu);
                if (!$stmt_mapping->execute()) {
                    echo "<p>Error adding mapping: " . $stmt_mapping->error . "</p>";
                }
            }

            $stmt_mapping->close();
            header("Location: manageCatMenu.php");
            exit;
        } else {
            echo "<p>Error adding category menu: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $db->close();
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
                    <!-- Optional NavLogo -->
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
    document.getElementById('addMenuButton').addEventListener('click', function() {
        var select = document.getElementById('menuSelect');
        var selectedOption = select.options[select.selectedIndex];
        var menuList = document.getElementById('menuList');
        var li = document.createElement('li');
        li.textContent = selectedOption.text;
        li.setAttribute('data-id', selectedOption.value); // Store the menu ID in a data attribute

        // Append the list item to the list
        menuList.appendChild(li);

        // Optionally, you might want to remove the selected item from the dropdown
        select.remove(select.selectedIndex);
    });

    // Prepare data to be submitted
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