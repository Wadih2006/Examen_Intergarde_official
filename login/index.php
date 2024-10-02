<?php 
   session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/login.css">
    <title>Login</title>
</head>
<body>
    <div class="page-container">
        <div class="left-section"></div> <!-- Achtergrondafbeelding aan de linkerkant -->
        <div class="right-section"> <!-- Login formulier aan de rechterkant -->
            <div class="container">
                <div class="box form-box">
                    <?php 
                    include("../php/config.php");
                    if(isset($_POST['submit'])){
                        $email = mysqli_real_escape_string($con,$_POST['email']);
                        $password = mysqli_real_escape_string($con,$_POST['password']);

                        $result = mysqli_query($con,"SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
                        $row = mysqli_fetch_assoc($result);

                        if(is_array($row) && !empty($row)){
                            $_SESSION['valid'] = $row['Email'];
                            $_SESSION['username'] = $row['Username'];
                            $_SESSION['id'] = $row['Id'];
                        }else{
                            echo "<div class='message'>
                            <p>Wrong Username or Password</p>
                            </div> <br>";
                            echo "<a href='index.php'><button class='btn'>Go Back</button>";
                        }

                        if(isset($_SESSION['valid'])){
                            header("Location: ../menu/menu.php");     // hier locatie naar menu
                        }
                    }else{
                    ?>
                    <header>Login</header>
                    <form action="" method="post">
                        <div class="field input">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" autocomplete="off" required>
                        </div>

                        <div class="field input">
                            <label for="password">Wachtwoord</label>
                            <input type="password" name="password" id="password" autocomplete="off" required>
                        </div>

                        <div class="field">
                            <input type="submit" class="btn" name="submit" value="Login" required>
                        </div>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>