<?php
   session_start();
   include("../php/config.php");
 
   if(!isset($_SESSION['valid'])){
       header("Location: ../login/index.php");
       exit();
   }
 
   $result = mysqli_query($con, "SELECT meldingen.*, adressen.straatnaam, adressen.huisnummer, adressen.postcode, adressen.plaats
        FROM meldingen
        LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
        WHERE adressen.provincie_id = 1");
 
    $countResult = mysqli_query($con, "SELECT COUNT(*) as total FROM meldingen
                                    LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
                                    WHERE adressen.provincie_id = 1");
 
    $countRow = mysqli_fetch_assoc($countResult);
    $totalMeldingen = $countRow['total'];
 
   if (!$result) {
       echo "Fout bij het ophalen van meldingen: " . mysqli_error($con);
       exit();
   }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noord-Brabant</title>
 
    <link rel="stylesheet" href="../style/meldingen.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
 
<header>
    <div class="header-container">
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">
 
        <div class="regio-titel">
            <h1>Noord-Brabant</h1>
        </div>
 
        <a href="../php/logout.php">
            <button class="btn">Log Out</button>
        </a>
    </div>
</header>
 
<div class="wrapper">
 
    <div class="melding-count">
        <p>Aantal meldingen: <strong><?php echo $totalMeldingen; ?></strong></p>
    </div>
 
    <div class="container-all">
 
        <div class="meldingen-box">
 
            <div class="content-left">
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='melding'>";
                    echo "<p><strong>Onderwerp:</strong></p>";
                    echo "<p>" . $row['onderwerp'] . "</p>";
                    echo "<p><strong>Beschrijving:</strong></p>";
                    echo "<p>" . $row['beschrijving'] . "</p>";
                    echo "<p><strong>Datum:</strong></p>";
                    echo "<p>" . $row['datum'] . "</p>";
                    echo "<p><strong>Status:</strong></p>";
                    echo "<p>" . $row['status'] . "</p>";
                    echo "<p><strong>Adres:</strong></p>";
                    echo "<p>" . $row['straatnaam'] . " " . $row['huisnummer'] . ", " . $row['postcode'] . " " . $row['plaats'] . "</p>";
                    
                    // Nieuwe div voor de verwijderknop om uitlijning mogelijk te maken
                    echo "<div class='btn-delete-container'>";
                    echo "<a href='delete_melding.php?melding_id=" . $row['id'] . "' onclick=\"return confirm('Weet u zeker dat u deze melding wilt verwijderen?');\">";
                    echo "<button class='btn-delete'>Verwijderen</button>";
                    echo "</a>";
                    echo "</div>";
                    
                    echo "<hr>";
                    echo "</div>";
                }
                ?>
 
                <!-- Vaste knop rechtsonder in content-left -->
                <div class="fixed-btn-container">
                    <button class="popup-btn" onclick="openPopup()">Meer info</button>
                </div>
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
 
<button class="sidebar-btn" onclick="window.location.href='../menu/menu.php';">Terug</button>
 
<!-- Popup HTML -->
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <h2>Meer informatie</h2>
        <p>Hier kunt u gedetailleerde informatie toevoegen over de melding of andere relevante inhoud.</p>
    </div>
</div>
 
<script>
    function openPopup() {
        document.getElementById("popup").style.display = "flex";
    }
 
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }
 
    window.onclick = function(event) {
        var popup = document.getElementById("popup");
        if (event.target === popup) {
            closePopup();
        }
    }
</script>
 
</body>
</html>
 
 

 
 