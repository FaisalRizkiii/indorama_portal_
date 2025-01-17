<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indorama Portal</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <!-- Font Style --> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }

        a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        #userTable th {
            text-align: center;
        }

        .menu-item  {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f8f8f8;
            border-radius: 4px;
        }

        .cat_menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px;
            margin-bottom: 5px;
            background-color: #f8f8f8;
            border-radius: 4px;            
        }

        .menu-text {
            flex-grow: 1;
        }

        .cat_menu-text {
            flex-grow: 1;
        }

        .remove-button {
            padding: 2px 6px;
            color: white;
            background-color: #ff6666;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-button:hover {
            background-color: #ff3333;
        }

        #menuList #cat_menuList {
            list-style: none;
            padding: 0;
        }  

        #cat_menuList {
            list-style: none;
            padding: 0;
        }
    </style>

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    