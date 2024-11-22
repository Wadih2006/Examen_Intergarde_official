<?php
session_start();
include("../php/config.php");

// Controleer of de gebruiker ingelogd is
if (!isset($_SESSION['valid'])) {
    header("Location: ../login/index.php");
    exit();
}

// Verwerk AJAX-aanvraag om een nieuwe melding toe te voegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_melding') {
    // Sanitizeer gebruikersinvoer om SQL-injecties te voorkomen
    $onderwerp = mysqli_real_escape_string($con, $_POST['onderwerp']);
    $beschrijving = mysqli_real_escape_string($con, $_POST['beschrijving']);
    $datum = mysqli_real_escape_string($con, $_POST['datum']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $adressen_id = mysqli_real_escape_string($con, $_POST['adressen_id']); // verwacht een integer ID van het adres

    // Voeg de nieuwe melding toe aan de database
    $query = "INSERT INTO meldingen (onderwerp, beschrijving, datum, status, adressen_id) 
              VALUES ('$onderwerp', '$beschrijving', '$datum', '$status', '$adressen_id')";

    // Controleer of de query succesvol was
    if (mysqli_query($con, $query)) {
        echo json_encode(["success" => true, "message" => "Melding succesvol toegevoegd!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Fout bij het toevoegen van melding: " . mysqli_error($con)]);
    }
    exit();
}

// Haal bestaande meldingen op uit de database, inclusief adresinformatie
$result = mysqli_query($con, "SELECT meldingen.*, adressen.straatnaam, adressen.huisnummer, adressen.postcode, adressen.plaats
    FROM meldingen
    LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
    WHERE adressen.provincie_id = 1");

// Haal het totaal aantal meldingen op voor de provincie Noord-Brabant
$countResult = mysqli_query($con, "SELECT COUNT(*) as total FROM meldingen
    LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
    WHERE adressen.provincie_id = 1");

$countRow = mysqli_fetch_assoc($countResult);
$totalMeldingen = $countRow['total'];

// Haal personeelsinformatie op uit de database
$personeelResult = mysqli_query($con, "SELECT * FROM personeel");
if (!$personeelResult) {
    echo "Fout bij het ophalen van personeel: " . mysqli_error($con);
    exit();
}

// Controleer of het ophalen van meldingen succesvol was
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
        <!-- Totaal aantal meldingen tonen -->
        <p>Aantal meldingen: <strong><?php echo $totalMeldingen; ?></strong></p>
    </div>

    <div class="container-all">
        <div class="meldingen-box">
            <div class="content-left">
                <?php
                // Loop door alle opgehaalde meldingen en toon ze
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

                        // Verwijderknop per melding
                        echo "<div class='btn-delete-container'>";
                        echo "<a href='delete_melding.php?melding_id=" . $row['id'] . "' onclick=\"return confirm('Weet u zeker dat u deze melding wilt verwijderen?');\">";
                        echo "<button class='btn-delete'>Verwijderen</button>";
                        echo "</a>";
                        echo "</div>";
                        echo "<hr>";
                    echo "</div>";
                }
                ?>
                
                <!-- Knop om een nieuwe melding toe te voegen -->
                <div class="fixed-btn-container">
                    <button class="popup-btn" onclick="openPopup()">Voeg melding toe</button>
                </div>
            </div>

            <!-- Popup voor het toevoegen van een nieuwe melding -->
            <div id="popup" class="popup" style="display: none;">
                <div class="popup-content">
                    <span class="close-btn" onclick="closePopup()">&times;</span>
                    <form id="add-melding-form" onsubmit="addMelding(event)">
                        <label for="onderwerp">Onderwerp:</label>
                        <input type="text" id="onderwerp" name="onderwerp" required>
                        
                        <label for="beschrijving">Beschrijving:</label>
                        <textarea id="beschrijving" name="beschrijving" rows="4" required></textarea>
                        
                        <label for="datum">Datum:</label>
                        <input type="date" id="datum" name="datum" required>
                        
                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status" required>
                        
                        <label for="adressen_id">Adres ID:</label>
                        <input type="number" id="adressen_id" name="adressen_id" required>
                        
                        <button type="submit">Toevoegen</button>
                    </form>
                </div>
            </div>

            <div class="vertical-line"></div>

            <div class="right-section">
                <!-- Personeelsinformatie weergeven -->
                <h2>Personeels Overzicht</h2><br>
                <?php
                while ($row = mysqli_fetch_assoc($personeelResult)) {
                    echo "<div class='personeel-item'>
                            <strong>Naam:</strong> <p> {$row['naam']}<br>
                            <strong>Personeelsnummer:</strong> <p> {$row['personeelsnummer']}<br>
                            <strong>Status:</strong> <p> {$row['status']}
                        </div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<button class="sidebar-btn" onclick="window.location.href='../menu/menu.php';">Terug</button>

<script>
    // Open de popup
    function openPopup() {
        document.getElementById("popup").style.display = "flex";
    }

    // Sluit de popup
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

    // Verwerk het toevoegen van een nieuwe melding
    function addMelding(event) {
        event.preventDefault();

        const formData = new FormData(document.getElementById("add-melding-form"));
        formData.append('action', 'add_melding');

        fetch("", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                // Voeg de nieuwe melding toe aan de weergave zonder de pagina te herladen
                const meldingDiv = document.createElement("div");
                meldingDiv.classList.add("melding");
                meldingDiv.innerHTML = `
                    <p><strong>Onderwerp:</strong></p>
                    <p>${formData.get('onderwerp')}</p>
                    <p><strong>Beschrijving:</strong></p>
                    <p>${formData.get('beschrijving')}</p>
                    <p><strong>Datum:</strong></p>
                    <p>${formData.get('datum')}</p>
                    <p><strong>Status:</strong></p>
                    <p>${formData.get('status')}</p>
                    <p><strong>Adres ID:</strong></p>
                    <p>${formData.get('adressen_id')}</p>
                    <hr>
                `;
                document.querySelector(".content-left").appendChild(meldingDiv);

                closePopup();
                document.getElementById("add-melding-form").reset();
            } else {
                alert("Fout bij het toevoegen van melding: " + data.message);
            }
        })
        .catch(error => console.error("Fout:", error));
    }
</script>

</body>
</html>
