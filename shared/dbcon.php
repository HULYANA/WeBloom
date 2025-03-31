<?php
$dbhost = "mysql:host=127.0.0.1;dbname=webloom";
$dbconusername = "root";
$dbconpassword = "";    

try {
    $pdo = new PDO($dbhost, $dbconusername, $dbconpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
?>
