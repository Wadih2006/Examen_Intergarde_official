<?php 
   session_start();

   include("../php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: ../login/index.php");
    exit();
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zuid-Limburg</title>

    <link rel="stylesheet" href="../style/meldingen.css">
    <link rel="stylesheet" href="../style/header.css">

</head>
<body>

<header>
    <div class="header-container">
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">

        <a href="../php/logout.php"> 
            <button class="btn">Log Out</button> 
        </a>
    </div>
</header>

    <div class="wrapper">
        <div class="container-all">

            <div class="sidebar">
                <h1>Zuid-Limburg</h1>
                <p>Maastricht</p>
                <p>Heerlen</p>
                <p>Overig</p>

                <button class="sidebar-btn" onclick="window.location.href='../menu/menu.php';">Terug</button>

            </div>

            <div class="meldingen-box">

                <div class="content-left">
                    <h2>Linker Informatie</h2>
                    <p>Informatie aan de linkerkant.</p>
                    <p>Meer informatie aan de linkerkant.</p>
                </div>

                    <div class="vertical-line"></div>

                <div class="content-right">
                    <h2>Rechter Informatie</h2>
                    <p>Informatie aan de rechterkant.</p>
                    <p>Meer informatie aan de rechterkant.</p>
                </div>
                
            </div>



        </div>
    </div>

</body>
</html>