<?php 
   session_start();

   include("../php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: ../login/index.php");
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
    <link rel="Website icon" type="jpg" href="../media/intergarde_icon.jpeg">

</head>
<body>

<header>
    <div class="header-container">
        <!-- Logo -->
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">

        <!-- Welcome Message and Email -->
        <div class="main-content">
            <p>Welkom, <b><?php echo $_SESSION['username']; ?></b></p>
            <p>Uw Email is <b><?php echo $_SESSION['valid']; ?></b>.</p>
        </div>

        <!-- Links to Change Profile and Logout -->

            <a href="../php/logout.php"> 
                <button class="btn">Log Out</button> 
            </a>
        </div>
    </div>
</header>


    <!-- Achtergrondvideo, zonder loop attribuut -->
    <video id="background-video" class="background-video" autoplay muted>
        <source src="" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="overlay"></div>

    <div class="regio-box">
        <div class="regio">
            <a href="#" id="noord-brabant" class="region-link">Noord-Brabant</a>
            <a href="#" id="noord-limburg" class="region-link">Noord-Limburg</a>
            <a href="#" id="zuid-limburg" class="region-link">Zuid-Limburg</a>
        </div>
    </div>



<script>
    const noordBrabant = document.getElementById('noord-brabant');
    const noordLimburg = document.getElementById('noord-limburg');
    const zuidLimburg = document.getElementById('zuid-limburg');
    const videoElement = document.getElementById('background-video');

    // Functie om de video te veranderen
    function changeBackgroundVideo(videoUrl) {
        videoElement.src = videoUrl;
        videoElement.play(); // Speel de video af
    }

    // Video veranderen bij hover
    noordBrabant.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-brabant.mp4'));
    noordLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-limburg.mp4'));
    zuidLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/zuid-limburg.mp4'));

    // Terug naar de standaard video bij het verlaten van de link
    noordBrabant.addEventListener('mouseout', () => changeBackgroundVideo('default-video.mp4'));
    noordLimburg.addEventListener('mouseout', () => changeBackgroundVideo('default-video.mp4'));
    zuidLimburg.addEventListener('mouseout', () => changeBackgroundVideo('default-video.mp4'));

    // Stop de video na één keer afspelen
    videoElement.addEventListener('ended', () => {
        videoElement.pause(); // Zorg ervoor dat de video stopt
    });

</script>
</body>
</html>
