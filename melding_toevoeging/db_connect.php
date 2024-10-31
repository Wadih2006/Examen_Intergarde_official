<?php

$host = 'localhost';
$dbname = 'intergarde';
$username = 'root';
$password = 'root';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database verbonden!";
} catch (PDOException $e) {
    echo "Verbinding mislukt: " . $e->getMessage();
}

?>