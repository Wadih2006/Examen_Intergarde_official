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

<!-- CSS classes -->
    <div class="page-container">
        <div class="left-section"></div>
        <div class="right-section">
            <div class="container">
                <div class="box form-box">
                    <?php 

                    //importeert config.php in deze code
                    include("../php/config.php");
                    if(isset($_POST['submit'])){

                        //gegevens worden opgehaald als je op submit knop klikt
                        $email = mysqli_real_escape_string($con, $_POST['email']);
                        $password = mysqli_real_escape_string($con, $_POST['password']);
                        
                        //gegevens worden gecontroleerd als ze overeen komen in database
                        $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Wrong Username or Password");
                        
                        //gegevens worden opgeslagen in een row
                        $row = mysqli_fetch_assoc($result);

                        //als alles gecontroleerd is krijgt alles een sessie en word je door verwezen naar menu.php
                        if(is_array($row) && !empty($row)){
                            $_SESSION['valid'] = $row['Email'];
                            $_SESSION['username'] = $row['Username'];
                            $_SESSION['id'] = $row['Id'];
                            $_SESSION['loggedin'] = true;
                            header("Location: ../menu/menu.php");
                            exit();

                            //als geen account in de database overeen komt komt er een melding
                            //"Wrong Username or Password"
                        }else{
                            echo "<div class='message'>
                            <p>Wrong Username or Password</p>
                            </div> <br>";
                            echo "<a href='login.php'><button class='btn'>Go Back</button>";
                        }
                    } else {
                    ?>

                    <!-- dit is de login form -->
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
