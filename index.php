<?php 
    include('header.php');
    session_start();

    // Redirect to login if not logged in
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    // Database connection
    require_once('../indorama_portal_/lib/db_login.php');

    // Fetch user's name
    $query = "SELECT * FROM user WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $name = htmlspecialchars($user['name']);
    }
?>

<div class="container-fluid">
    <div class="row" style="display: flex; flex-wrap: nowrap;">
        <?php include('sidebar.php'); ?>
        <div class="col-md-12">
            <div class="container">
                <div class="row">
                    <?php include('navLogo.php'); ?>
                    <div class="col-md-12">
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 40px">Welcome <?php echo $name ?> 👋</h3>
                        </div>
                        <div class="row" style="height: 415px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; background-color: #F8F8F8; border-radius: 30px;">
                            <div style="margin: 20px;">
                                <?php 
                                    if ($role === "admin") {
                                        require_once('../indorama_portal_/lib/db_login.php');
                                        
                                        $query2 = " SELECT name , id_categorymenu , image_url
                                                    FROM category_menu
                                                    ";

                                        $result2 = $db->query($query2);
                                        if (!$result2) {
                                            die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                        }
                                        
                                        if ($result2->num_rows > 0){ 
                                            while ($row = $result2->fetch_object()) {
                                                echo '<div class="col-md-4">';
                                                    echo '<div class="panel panel-default">';
                                                        echo '<a href="menu_dashboard.php?id_categorymenu='.($row->id_categorymenu).'">';
                                                            echo '<div class="panel-heading" style="cursor: pointer;"> ';
                                                                echo '<img src="'. ($row->image_url) .'" style="width: 100%; height:100px;"alt="Logo">';
                                                                echo '<p style="margin-top: 20px; text-align: center;">'.($row->name) . '</p>';
                                                            echo '</div>';
                                                        echo '</a>';
                                                    echo '</div>';
                                                echo '</div>';
                                            } 
                                        } else {
                                            echo '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                                        }
                                    } else {
                                        require_once('../indorama_portal_/lib/db_login.php');

                                        $query2 = " SELECT cm.name , cm.id_categorymenu , cm.image_url
                                                    FROM user u
                                                        JOIN group_members gm
                                                            ON u.id = gm.user_id
                                                        JOIN `group` g
                                                            ON gm.group_id = g.group_id
                                                        JOIN mapping_categorymenu mcm
                                                            ON g.group_id = mcm.group_id
                                                        JOIN category_menu cm
                                                            ON mcm.id_categorymenu = cm.id_categorymenu
                                                    WHERE id = {$id}
                                                    ";

                                        $result2 = $db->query($query2);
                                        if (!$result2) {
                                            die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query2);
                                        }

                                        if ($result2->num_rows > 0){ 
                                            while ($row = $result2->fetch_object()) {
                                                echo '<div class="col-md-4">';
                                                    echo '<div class="panel panel-default">';
                                                        echo '<a href="menu_dashboard.php?id_categorymenu='.($row->id_categorymenu).'">';
                                                            echo '<div class="panel-heading" data-toggle="collapse" data-target="#panelContent'.($row->id_categorymenu) .'" style="cursor: pointer;"> ';
                                                                echo '<img src="'. ($row->image_url) .'" style="width: 100%; height:100px;"alt="Logo">';
                                                                echo '<p style="margin-top: 20px; text-align: center;">'.($row->name) . '</p>';
                                                            echo '</div>';
                                                        echo '</a>';
                                                    echo '</div>';
                                                echo '</div>';
                                            } 
                                        } else {
                                            echo '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<?php 
    include('footer.php');
?>
