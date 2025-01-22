<?php 
    include('header.php');
    session_start();

    // Redirect to login if not logged in
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    // Database connection
    require_once('../indorama_portal_/lib/db_login.php');

    $id = $_SESSION['id'];
    $id_categorymenu = isset($_GET['id_categorymenu']) ? intval($_GET['id_categorymenu']) : 0;

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

    // Fetch user's name
    $query = "SELECT * FROM category_menu WHERE id_categorymenu = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id_categorymenu);
    $stmt->execute();
    $result = $stmt->get_result();  

    if ($result && $result->num_rows > 0) {
        $category_menu = $result->fetch_assoc();
        $category_menu_name = htmlspecialchars($category_menu['name']);
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
                            <h3 style="font-weight: 600; font-size: 40px">Menu <?php echo $category_menu_name ?> </h3>
                        </div>
                        <div class="row" style="height: 415px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; background-color: #F8F8F8; border-radius: 10px;">
                            <div style="margin: 20px;">
                                <?php 
                                    require_once('../indorama_portal_/lib/db_login.php');
                                        
                                    $query3 = " SELECT m.id_menu, m.name, m.url
                                                FROM category_menu cm
                                                    JOIN mapping_menu mm
                                                        ON cm.id_categorymenu = mm.id_categorymenu
                                                    JOIN menu m
                                                        ON mm.id_menu = m.id_menu
                                                WHERE cm.id_categorymenu = {$id_categorymenu}
                                                ";

                                    $result3 = $db->query($query3);
                                    if (!$result3) {
                                        die("Could not query the database: <br />" . $db->error . '<br>Query: ' . $query3);
                                    }
                                    
                                    if ($result3->num_rows > 0){ 
                                        while ($row = $result3->fetch_object()) {
                                        echo '<div class="col-md-4">';
                                            echo '<div class="panel panel-default" style="border-radius: 30px">';
                                                echo '<a href="'.($row->url).'">';
                                                    echo '<div class="panel-heading" style="cursor: pointer;"> ';
                                                        echo '<img src="img/indorama_logo.jpg" style="width: 100%; height:100px;"alt="Logo">';
                                                        echo '<p style="margin-top: 20px; text-align: center;">'.($row->name).'</p>';
                                                    echo '</div>';
                                                echo '</a>';
                                            echo '</div>';
                                        echo '</div>';
                                        } 
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No data available</td></tr>';
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