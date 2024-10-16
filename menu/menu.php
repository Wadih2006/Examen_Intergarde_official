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
    <title>Intergarde</title>
    
    <link rel="stylesheet" href="../style/menu.css">
    <link rel="stylesheet" href="../style/header.css">
    <link rel="Website icon" type="image/jpeg" href="../media/intergarde_icon.jpeg">
</head>
<body>

<header>
    <div class="header-container">
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">

        <div class="main-content">
            <p>Welkom, <b><?php echo $_SESSION['username']; ?></b></p>
            <p>Uw Email is <b><?php echo $_SESSION['valid']; ?></b>.</p>
        </div>

        <a href="../php/logout.php"> 
            <button class="btn">Log Out</button> 
        </a>
    </div>
</header>

<video id="background-video" class="background-video" autoplay muted>
    <source src="default-video.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<div class="overlay"></div>

<div class="regio-box">
    <div class="regio">
        <a href="#" id="noord-brabant" class="region-link">Noord-Brabant</a>
        <a href="../meldingen/noord-limburg.php" id="noord-limburg" class="region-link">Noord-Limburg</a>
        <a href="../meldingen/zuid-limburg.php" id="zuid-limburg" class="region-link">Zuid-Limburg</a>
    </div>
</div>

<script>
    const noordBrabant = document.getElementById('noord-brabant');
    const noordLimburg = document.getElementById('noord-limburg');
    const zuidLimburg = document.getElementById('zuid-limburg');
    const videoElement = document.getElementById('background-video');
    const defaultVideo = 'default-video.mp4';

    function changeBackgroundVideo(videoUrl) {
        videoElement.src = videoUrl;
        videoElement.play();
    }

    noordBrabant.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-brabant.mp4'));
    noordLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-limburg.mp4'));
    zuidLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/zuid-limburg.mp4'));

    noordBrabant.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));
    noordLimburg.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));
    zuidLimburg.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));

    videoElement.addEventListener('ended', () => {
        videoElement.pause();
    });
</script>

</body>
</html>
