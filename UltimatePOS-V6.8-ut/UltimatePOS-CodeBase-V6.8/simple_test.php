<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=ultimatepos', 'root', '');
    echo "Direct database connection successful!";
} catch (PDOException $e) {
    echo "Direct connection failed: " . $e->getMessage();
}