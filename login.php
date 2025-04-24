<?php include('header.php') ?>

<?php
    session_start();

    if (isset($_SESSION['id'])) {
        header("Location: index.php");
        exit();
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4" style="background: linear-gradient(135deg, #0D2F76, #1E497D); height: 100vh;">
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; background: linear-gradient(135deg, #0D2F76, #1E497D); color: white;">
                <h3 style="font-weight: 640; font-size: 30px; margin-bottom: 20px; text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);">
                    LOGIN
                </h3>
                <form action="LoginController.php" method="post" autocomplete="on" style="background: rgba(255, 255, 255, 0.2); padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); width: 100%; max-width: 400px; display: flex; flex-direction: column; gap: 15px;">
                    <!-- Email -->
                    <label for="email" style="font-size: 16px; font-weight: 500;">
                        Email:
                    </label>
                    <input type="text" name="email" id="email" style="color: black; padding: 10px; border: none; border-radius: 5px; outline: none; font-size: 14px; background: rgba(255, 255, 255, 0.8);" >
                    <!-- Password -->
                    <label for="password" style="font-size: 16px; font-weight: 500;">
                        Password:
                    </label>
                    <input type="password" name="password" id="password" style="color: black; padding: 10px; border: none; border-radius: 5px; outline: none; font-size: 14px; background: rgba(255, 255, 255, 0.8);" >
                    <button type="submit" name="submit" style="padding: 10px; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; color: white; background: #1E497D; cursor: pointer; transition: background 0.3s;" onmouseover="this.style.background='#0D2F76';" onmouseout="this.style.background='#1E497D';">
                        Login
                    </button>

                    <?php
                        if (isset($_GET['error'])) {
                            echo 
                            '<div class="bg-danger" style="height:100%; width:100%; padding:10px; border-radius: 10px; color: red; text-align: center; margin-bottom: 10px;">' . 
                                htmlspecialchars($_GET['error']) . 
                            '</div>';
                        }
                    ?>
                </form>
            </div>
        </div>
        <div class="col-md-8" style="display: flex; padding: 0; height: 100vh;">
            <img src="img/indorama_login.jpeg" alt="" style="width: 100%; height: 100%;">
        </div>
    </div>
</div>

<?php include('footer.php') ?>

