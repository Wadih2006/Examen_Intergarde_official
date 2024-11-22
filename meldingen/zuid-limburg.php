<?php
session_start();
include("../php/config.php");

// Controleer of de gebruiker is ingelogd; anders omleiden naar de login-pagina
if (!isset($_SESSION['valid'])) {
    header("Location: ../login/index.php");
    exit();
}

// Verwerk een AJAX-aanvraag om een nieuwe melding toe te voegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_melding') {
    // Valideer en ontsnap de ingevoerde gegevens om SQL-injecties te voorkomen
    $onderwerp = mysqli_real_escape_string($con, $_POST['onderwerp']);
    $beschrijving = mysqli_real_escape_string($con, $_POST['beschrijving']);
    $datum = mysqli_real_escape_string($con, $_POST['datum']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $adressen_id = mysqli_real_escape_string($con, $_POST['adressen_id']); // Verwacht een numeriek adres-ID

    // Voeg de nieuwe melding toe aan de database
    $query = "INSERT INTO meldingen (onderwerp, beschrijving, datum, status, adressen_id) 
              VALUES ('$onderwerp', '$beschrijving', '$datum', '$status', '$adressen_id')";

    if (mysqli_query($con, $query)) {
        // Stuur een JSON-respons terug als de operatie succesvol was
        echo json_encode(["success" => true, "message" => "Melding succesvol toegevoegd!"]);
    } else {
        // Stuur een foutbericht als de query faalt
        echo json_encode(["success" => false, "message" => "Fout bij het toevoegen van melding: " . mysqli_error($con)]);
    }
    exit();
}

// Haal meldingen op die gekoppeld zijn aan adressen binnen provincie_id = 3
$result = mysqli_query($con, "SELECT meldingen.*, adressen.straatnaam, adressen.huisnummer, adressen.postcode, adressen.plaats
    FROM meldingen
    LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
    WHERE adressen.provincie_id = 3");

// Haal het totaal aantal meldingen op
$countResult = mysqli_query($con, "SELECT COUNT(*) as total FROM meldingen
    LEFT JOIN adressen ON meldingen.adressen_id = adressen.id
    WHERE adressen.provincie_id = 3");

// Sla het totaal aantal meldingen op in een variabele
$countRow = mysqli_fetch_assoc($countResult);
$totalMeldingen = $countRow['total'];

// Haal personeelsinformatie op
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
    <title>Zuid-Limburg</title>

    <!-- Stijlen voor de meldingenpagina -->
    <link rel="stylesheet" href="../style/meldingen.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>

<header>
    <div class="header-container">
        <img id="logo" src="../media/Intergarde-Logo.png" alt="Logo">
        <div class="regio-titel">
            <h1>Zuid-Limburg</h1>
        </div>
        <!-- Log-out knop -->
        <a href="../php/logout.php">
            <button class="btn">Log Out</button>
        </a>
    </div>
</header>

<div class="wrapper">
    <div class="melding-count">
        <!-- Toon het totale aantal meldingen -->
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

                        // Knop voor verwijderen van melding
                        echo "<div class='btn-delete-container'>";
                        echo "<a href='delete_melding.php?melding_id=" . $row['id'] . "' onclick=\"return confirm('Weet u zeker dat u deze melding wilt verwijderen?');\">";
                        echo "<button class='btn-delete'>Verwijderen</button>";
                        echo "</a>";
                        echo "</div>";
                        
                        echo "<hr>";
                        echo "</div>";
                }
                ?>
                <div class="fixed-btn-container">
                    <!-- Knop om het formulier voor een nieuwe melding te openen -->
                    <button class="popup-btn" onclick="openPopup()">Voeg melding toe</button>
                </div>
            </div>

            <!-- Pop-up formulier voor nieuwe meldingen -->
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
                <!-- Toon personeelsinformatie -->
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

<!-- Knop om terug te gaan naar het menu -->
<button class="sidebar-btn" onclick="window.location.href='../menu/menu.php';">Terug</button>

<script>
    // Functie om het pop-upvenster te openen
    function openPopup() {
        document.getElementById("popup").style.display = "flex";
    }

    // Functie om het pop-upvenster te sluiten
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

    // Functie om een nieuwe melding toe te voegen via AJAX
    function addMelding(event) {
        event.preventDefault(); // Voorkom herladen van de pagina

        const formData = new FormData(document.getElementById("add-melding-form"));
        formData.append('action', 'add_melding'); // Voeg een actieparameter toe

        // Verstuur de gegevens naar de server
        fetch("", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                // Dynamisch de nieuwe melding toevoegen aan de lijst
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

                closePopup(); // Sluit het pop-upvenster
                document.getElementById("add-melding-form").reset(); // Reset het formulier
            } else {
                alert("Fout bij het toevoegen van melding: " + data.message);
            }
        })
        .catch(error => console.error("Fout:", error));
    }
</script>

</body>
</html>
