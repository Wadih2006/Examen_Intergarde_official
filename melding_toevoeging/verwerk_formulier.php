<?php
// Start de sessie bovenaan de pagina
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meldingen toevoegen</title>
    
</head>
<?php
session_start();
include 'db_connect.php'; // Verbind met de database
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meldingen toevoegen</title>
    <style>
        /* je CSS code hier */
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoeren van Gegevens</h1>
        <!-- Formulier -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="title">Titel:</label>
                <input type="text" id="title" name="title" placeholder="Voer de titel in" required>
            </div>

            <div class="form-group">
                <label for="description">Beschrijving:</label>
                <textarea id="description" name="description" placeholder="Voer de beschrijving in" required></textarea>
            </div>

            <div class="form-group">
                <label for="regio">Regio:</label>
                <select id="regio" name="regio" required>
                    <option value="Zuid-Limburg">Zuid-Limburg</option>
                    <option value="Noord-Limburg">Noord-Limburg</option>
                    <option value="Brabant">Brabant</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="actief">Actief</option>
                    <option value="inactief">Inactief</option>
                    <option value="in_behandeling">In Behandeling</option>
                </select>
            </div>

            <input type="submit" value="Verstuur">
        </form>

        <!-- Verwerking van het formulier -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Verkrijg de waarden uit het formulier
                $title = htmlspecialchars($_POST['title']);
                $description = htmlspecialchars($_POST['description']);
                $regio = htmlspecialchars($_POST['regio']);
                $status = htmlspecialchars($_POST['status']);

                // Bereid de SQL-query voor
                $sql = "INSERT INTO meldingen (title, description, regio, status) VALUES (:title, :description, :regio, :status)";
                $stmt = $conn->prepare($sql);

                // Bind parameters en voer uit
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':regio', $regio);
                $stmt->bindParam(':status', $status);

                if ($stmt->execute()) {
                    echo "Melding succesvol opgeslagen!";
                } else {
                    echo "Er is een fout opgetreden.";
                }
            } catch (PDOException $e) {
                echo "SQL-fout: " . $e->getMessage();
            }
        }
        ?>

        <!-- Alle meldingen -->
        <div class="meldingen">
            <h2>Alle Meldingen:</h2>

            <?php
            // Haal alle meldingen uit de database
            $sql = "SELECT * FROM meldingen ORDER BY created_at DESC";
            $stmt = $conn->query($sql);
            $meldingen = $stmt->fetchAll();

            if (count($meldingen) > 0) {
                foreach ($meldingen as $melding) {
                    echo "<div class='melding'>";
                    echo "<p><strong>Titel:</strong> " . $melding['title'] . "</p>";
                    echo "<p><strong>Beschrijving:</strong> " . $melding['description'] . "</p>";
                    echo "<p><strong>Regio:</strong> " . $melding['regio'] . "</p>";
                    echo "<p><strong>Status:</strong> " . $melding['status'] . "</p>";
                    echo "<p><strong>Ingediend op:</strong> " . $melding['created_at'] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Geen meldingen beschikbaar.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>