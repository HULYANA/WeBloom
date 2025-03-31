<?php
session_start();
require_once '../../shared/dbcon.php';

$result = null; 

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    try {
        $query = "
        SELECT u.firstname, u.lastname, u.gender, u.email, 
               u.userID, s.studentID, p.programName, q.evalautionPeriod
        FROM users u
        LEFT JOIN students s ON u.userID = s.userID
        LEFT JOIN programs p ON s.programID = p.programID
        LEFT JOIN questionnaires q ON p.programID = q.programID
        WHERE u.userID = ?
        LIMIT 1";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$userID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorMessage = "Error retrieving data: " . htmlspecialchars($e->getMessage());
    }
} else {
    $errorMessage = "No user is logged in.";
}
?>