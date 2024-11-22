<?php
session_start();
include("../php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: ../login/index.php");
    exit();
}

if (isset($_GET['melding_id'])) {
    $melding_id = $_GET['melding_id'];

    // Verwijder de melding uit de database
    $deleteResult = mysqli_query($con, "DELETE FROM meldingen WHERE id = $melding_id");

    if ($deleteResult) {
        header("Location: ../menu/menu.php");
    } else {
        echo "Fout bij het verwijderen van de melding: " . mysqli_error($con);
    }
} else {
    echo "Geen melding geselecteerd om te verwijderen.";
}
?>


