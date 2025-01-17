<?php 
    $db_host = 'localhost:3308';
    $db_database = 'indorama_portal';
    $db_username = 'root';
    $db_password = '';

    $db = new mysqli( $db_host, 
                    $db_username, 
                    $db_password, 
                    $db_database );
    if ($db->connect_errno) {
        die("Could not connect to the database: <br />" . $db->connect_error);
    }

    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }
?>