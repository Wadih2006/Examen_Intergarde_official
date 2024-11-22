<?php 
   // Start the session to manage user authentication
   session_start();

   // Include the configuration file that connects to the database
   include("../php/config.php");

   // Check if the user is logged in; if not, redirect to login page
   if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
       // Redirect user to the login page if they are not logged in
       header("Location: ../login/login.php");
       exit();
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set character encoding and viewport settings for mobile responsiveness -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intergarde</title>
    
    <!-- Link to external CSS stylesheets -->
    <link rel="stylesheet" href="../style/menu.css">
    <link rel="stylesheet" href="../style/header.css">
    
    <!-- Link to the website icon -->
    <link rel="Website icon" type="image/jpeg" href="../media/intergarde_icon.jpeg">
</head>
<body>

<header>
    <div class="header-container">
        <!-- Display the logo of the website -->
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">

        <div class="main-content">
            <!-- Greet the user with their username and display their email -->
            <p>Welkom, <b><?php echo $_SESSION['username']; ?></b></p>
            <p>Uw Email is <b><?php echo $_SESSION['valid']; ?></b>.</p>
        </div>

        <!-- Provide a logout button that logs the user out when clicked -->
        <a href="../login/logout.php"> 
            <button class="btn">Log Out</button>
        </a>
    </div>
</header>

<!-- Video background that plays automatically and is muted -->
<video id="background-video" class="background-video" autoplay muted>
    <!-- Default video to be played if others are unavailable -->
    <source src="default-video.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<!-- Overlay on top of the video background for styling -->
<div class="overlay"></div>

<!-- Container for region links that users can click to navigate -->
<div class="regio-box">
    <div class="regio">
        <!-- Region links that lead to specific pages for Noord-Brabant, Noord-Limburg, and Zuid-Limburg -->
        <a href="../meldingen/noord-brabant.php" id="noord-brabant" class="region-link">Noord-Brabant</a>
        <a href="../meldingen/noord-limburg.php" id="noord-limburg" class="region-link">Noord-Limburg</a>
        <a href="../meldingen/zuid-limburg.php" id="zuid-limburg" class="region-link">Zuid-Limburg</a>
    </div>
</div>

<script>
    // Get elements by their IDs for easy manipulation
    const noordBrabant = document.getElementById('noord-brabant');
    const noordLimburg = document.getElementById('noord-limburg');
    const zuidLimburg = document.getElementById('zuid-limburg');
    const videoElement = document.getElementById('background-video');
    
    // Default video to show when no region is selected
    const defaultVideo = 'default-video.mp4';

    // Function to change the background video when hovering over region links
    function changeBackgroundVideo(videoUrl) {
        videoElement.src = videoUrl;
        videoElement.play();
    }

    // Event listeners for mouseover and mouseout actions for each region
    noordBrabant.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-brabant.mp4'));
    noordLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/noord-limburg.mp4'));
    zuidLimburg.addEventListener('mouseover', () => changeBackgroundVideo('../media/zuid-limburg.mp4'));

    // Reset to default video when mouse leaves the region links
    noordBrabant.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));
    noordLimburg.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));
    zuidLimburg.addEventListener('mouseout', () => changeBackgroundVideo(defaultVideo));

    // When the video ends, pause it
    videoElement.addEventListener('ended', () => {
        videoElement.pause();
    });
</script>

</body>
</html>
