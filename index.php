<?php include('header.php') ?>

<?php
    session_start();

    // Check if user is not logged in
    if (!isset($_SESSION['username'])) {
        // Redirect to login page
        header("Location: login.php");
        exit();
    }
    ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-1" style="background-color: #0D2F76; width: 80px; height: 100vh; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); display: flex; flex-direction: column; justify-content: center; align-items: center;top: 0; left: 0; z-index: 999; padding-top: 20px;">
            <nav class="nav nav-sidebar" style="width: 100%; text-align: center;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <a href="dashboard.php" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 50px; transition: background-color 0.3s;">
                            <svg 
                                width="20" 
                                height="20" 
                                viewBox="0 0 25 24" 
                                fill="none" 
                                xmlns="http://www.w3.org/2000/svg">
                                <path 
                                    fill-rule="evenodd" 
                                    clip-rule="evenodd" 
                                    d="M11.5398 0.390386C11.793 0.140422 12.1364 0 12.4944 0C12.8524 0 13.1958 0.140422 13.449 0.390386L21.5499 8.39048L24.2502 11.0572C24.4962 11.3087 24.6322 11.6455 24.6292 11.9951C24.6261 12.3447 24.4841 12.6791 24.2338 12.9263C23.9834 13.1735 23.6448 13.3137 23.2908 13.3168C22.9368 13.3198 22.5957 13.1854 22.3411 12.9425L21.9455 12.5519V21.3333C21.9455 22.0406 21.661 22.7188 21.1546 23.2189C20.6482 23.719 19.9613 24 19.2452 24H15.1947C14.8366 24 14.4932 23.8595 14.24 23.6095C13.9868 23.3594 13.8445 23.0203 13.8445 22.6667V18.6666H11.1442V22.6667C11.1442 23.0203 11.002 23.3594 10.7488 23.6095C10.4956 23.8595 10.1522 24 9.79407 24H5.7436C5.02743 24 4.34059 23.719 3.83419 23.2189C3.32778 22.7188 3.04328 22.0406 3.04328 21.3333V12.5519L2.64769 12.9425C2.39304 13.1854 2.05199 13.3198 1.69798 13.3168C1.34398 13.3137 1.00534 13.1735 0.75501 12.9263C0.50468 12.6791 0.362685 12.3447 0.359609 11.9951C0.356533 11.6455 0.492621 11.3087 0.738563 11.0572L3.43888 8.39048L11.5398 0.390386Z" 
                                    fill="#FFF1F1"
                                />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 50px; transition: background-color 0.3s;">
                            <svg 
                                width="16" 
                                height="20" 
                                viewBox="0 0 20 24" 
                                fill="none" 
                                xmlns="http://www.w3.org/2000/svg">
                                <path 
                                    fill-rule="evenodd" 
                                    clip-rule="evenodd" 
                                    d="M10 0C8.23189 0 6.5362 0.632141 5.28595 1.75736C4.03571 2.88258 3.33333 4.4087 3.33333 6C3.33333 7.5913 4.03571 9.11742 5.28595 10.2426C6.5362 11.3679 8.23189 12 10 12C11.7681 12 13.4638 11.3679 14.714 10.2426C15.9643 9.11742 16.6667 7.5913 16.6667 6C16.6667 4.4087 15.9643 2.88258 14.714 1.75736C13.4638 0.632141 11.7681 0 10 0ZM6.66667 13.5C4.89856 13.5 3.20286 14.1321 1.95262 15.2574C0.702379 16.3826 0 17.9087 0 19.5V21C0 21.7957 0.351189 22.5587 0.976311 23.1213C1.60143 23.6839 2.44928 24 3.33333 24H16.6667C17.5507 24 18.3986 23.6839 19.0237 23.1213C19.6488 22.5587 20 21.7957 20 21V19.5C20 17.9087 19.2976 16.3826 18.0474 15.2574C16.7971 14.1321 15.1014 13.5 13.3333 13.5H6.66667Z" 
                                    fill="white"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="#" style="display: flex; justify-content: center; align-items: center; width: 100%; height: 50px; transition: background-color 0.3s;">
                            <svg 
                                width="25" 
                                height="25" 
                                viewBox="0 0 25 20" 
                                fill="none" 
                                xmlns="http://www.w3.org/2000/svg">
                                <path 
                                    fill-rule="evenodd" 
                                    clip-rule="evenodd" 
                                    d="M12.1348 2.50092C11.5771 2.50092 11.0248 2.61408 10.5095 2.83393C9.99421 3.05378 9.526 3.37602 9.13161 3.78226C8.73723 4.18849 8.42438 4.67077 8.21094 5.20154C7.9975 5.73231 7.88764 6.30119 7.88764 6.87569C7.88764 7.45019 7.9975 8.01907 8.21094 8.54984C8.42438 9.08061 8.73723 9.56288 9.13161 9.96912C9.526 10.3754 9.99421 10.6976 10.5095 10.9174C11.0248 11.1373 11.5771 11.2505 12.1348 11.2505C13.2613 11.2505 14.3415 10.7895 15.138 9.96912C15.9346 9.14869 16.382 8.03595 16.382 6.87569C16.382 5.71543 15.9346 4.60269 15.138 3.78226C14.3415 2.96183 13.2613 2.50092 12.1348 2.50092ZM10.3146 12.5004C9.02726 12.5004 7.79265 13.0271 6.88236 13.9648C5.97207 14.9024 5.46067 16.1741 5.46067 17.5001C5.46067 18.1631 5.71637 18.799 6.17152 19.2678C6.62666 19.7366 7.24397 20 7.88764 20H16.382C17.0257 20 17.643 19.7366 18.0981 19.2678C18.5533 18.799 18.809 18.1631 18.809 17.5001C18.809 16.1741 18.2976 14.9024 17.3873 13.9648C16.477 13.0271 15.2424 12.5004 13.9551 12.5004H10.3146ZM18.5906 8.6306C18.9763 7.11735 18.8504 5.51391 18.2335 4.08422C17.6166 2.65453 16.5454 1.48381 15.1964 0.764761C15.7105 0.402926 16.2955 0.161931 16.91 0.0588426C17.5245 -0.0442456 18.1536 -0.00690618 18.7525 0.168217C19.3515 0.343341 19.9057 0.651965 20.376 1.07223C20.8463 1.4925 21.2211 2.01414 21.474 2.6002C21.7268 3.18626 21.8515 3.82241 21.8391 4.4636C21.8267 5.10479 21.6776 5.73534 21.4024 6.3106C21.1272 6.88586 20.7325 7.39175 20.2464 7.79245C19.7603 8.19315 19.1946 8.47886 18.5893 8.62935L18.5906 8.6306ZM21.236 17.5001H21.8427C22.4864 17.5001 23.1037 17.2368 23.5588 16.7679C24.014 16.2991 24.2697 15.6633 24.2697 15.0003C24.2697 13.6742 23.7583 12.4025 22.848 11.4649C21.9377 10.5273 20.7031 10.0005 19.4157 10.0005H18.0809C17.9158 10.3335 17.7247 10.652 17.5093 10.953C18.6399 11.604 19.5813 12.554 20.2364 13.7049C20.8915 14.8559 21.2365 16.166 21.236 17.5001ZM2.42697 4.37582C2.42678 3.57935 2.63769 2.79794 3.03696 2.11586C3.43623 1.43377 4.00869 0.876909 4.69262 0.505313C5.37655 0.133718 6.146 -0.0385059 6.91796 0.0072152C7.68994 0.0529363 8.43514 0.314868 9.07321 0.764761C7.72291 1.48225 6.65069 2.65284 6.03377 4.08306C5.41685 5.51327 5.29217 7.1175 5.68031 8.6306C4.75307 8.4005 3.92797 7.8557 3.33784 7.08391C2.7477 6.31213 2.42686 5.35824 2.42697 4.37582ZM6.18876 10.0005H4.85393C3.56659 10.0005 2.33197 10.5273 1.42168 11.4649C0.511395 12.4025 0 13.6742 0 15.0003C0 15.6633 0.255697 16.2991 0.710842 16.7679C1.16599 17.2368 1.78329 17.5001 2.42697 17.5001H3.03371C3.03313 16.166 3.3782 14.8559 4.03329 13.7049C4.68839 12.554 5.62978 11.604 6.76031 10.953C6.54499 10.652 6.35384 10.3335 6.18876 10.0005Z" 
                                    fill="white"/>
                            </svg>
                        </a>
                    </li>
                </ul>
                <!-- Add this where you want the logout button to appear -->
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </nav>
        </div>

        <!-- Main -->
        <div class="col-md-11" style="height: 100vh;">
            <div class="container" >
                <div class="row">
                    <!-- Navigation bar-->
                    <div class="navbar col-md-12" style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 20px; margin-bottom: 30px;">
                        <!-- Logo Section -->
                        <a href="dashboard.php">
                            <img src="img/indorama_logo.jpg" style="width: 240px; height: auto;" alt="Indorama logo">
                        </a>
                    </div>
                    <div class="col-md-12" >
                        <div class="page-header">
                            <h3 style="font-weight: 600; font-size: 40px">Welcome 👋</h3>
                        </div>
                        <!-- Modul Row -->
                        <div class="row" style="height: 500px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; background-color: #F8F8F8; border-radius: 30px;">
                            <div style="margin: 20px;">
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent1" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent1" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent2" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent3" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent4" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent5" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent5" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent6" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent6" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" data-toggle="collapse" data-target="#panelContent7" style="cursor: pointer;">
                                            <img src="img/indorama_logo.jpg" style="width: 100%; height:auto;" alt="Logo">
                                            <p style="margin-top: 20px; text-align: center;">
                                                Judul Modul
                                            </p>
                                        </div>
                                        <div id="panelContent7" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="list-group">
                                                    <li class="list-group-item"><a href="#">Action 1</a></li>
                                                    <li class="list-group-item"><a href="#">Action 2</a></li>
                                                    <li class="list-group-item"><a href="#">Action 3</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<?php include('footer.php') ?>