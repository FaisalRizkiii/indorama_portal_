<?php 
include('header.php');
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

require_once('../indorama_portal_/lib/db_login.php');

$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max($current_page, 1);

$offset = ($current_page - 1) * $records_per_page;

$total_query = "SELECT COUNT(*) as total FROM menu";
$total_result = $db->query($total_query);
$total_row = $total_result->fetch_object();
$total_records = $total_row->total;
$total_pages = ceil($total_records / $records_per_page);

$query = "SELECT * FROM menu LIMIT ? OFFSET ?";
$stmt = $db->prepare($query);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

include('sidebar.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-11" style="height: 100vh;">
            <div class="container">
                <?php include('navLogo.php'); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 30px;">Manage Menu</h3>
                        </div>
                        <a href="add_menu.php" class="btn btn-success" style="margin-bottom: 15px;">
                            <i class="fas fa-user-plus"></i> Add New Menu
                        </a>
                        <table id="userTable" class="table table-bordered table-striped table-hover" style="text-align: center;">
                            <thead>
                                <tr class="info">
                                    <th>No</th>
                                    <th>Menu Name</th>
                                    <th>URL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = $offset;
                                while ($row = $result->fetch_assoc()) {
                                    $no++;
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>";
                                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['URL']) . "</td>";
                                    echo "<td>
                                            <a class='btn btn-primary btn-sm' href='edit_menu.php?id_menu={$row['id_menu']}'>Edit</a>&nbsp;&nbsp;
                                            <a class='btn btn-danger btn-sm' href='delete_menu.php?id_menu={$row['id_menu']}'>Delete</a>
                                        </td>";
                                    echo "</tr>";
                                }
                                if ($result->num_rows == 0) {
                                    echo '<tr><td colspan="4" class="text-center">No data available</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php include('pagination.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$result->free();
$db->close();
include('footer.php');
?>
