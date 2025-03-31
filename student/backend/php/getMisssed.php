<?php
session_start();
include '../../../shared/dbcon.php';

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    try {
        $query = "
            SELECT DISTINCT q.questionnaireID, q.title, q.expirationDate
            FROM questionnaires q
            LEFT JOIN responses r ON q.questionnaireID = r.questionnaireID 
            AND r.studentID = (SELECT studentID FROM students WHERE userID = ?)
            WHERE q.publicationStatus = 'expired' 
            AND r.responseID IS NULL
            AND (q.programID = (SELECT programID FROM students WHERE userID = ?) OR q.programID = 001);";

        $stmt = $pdo->prepare($query);

        $stmt->execute([$userID, $userID]);

        $missed_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($missed_results)) {
            foreach ($missed_results as $row) {
                echo "<div class='response-item'>";
                echo "<div class='response-title'>";
                echo "<label><strong>Title:</strong></label><br>"; 
                echo "<span>" . htmlspecialchars($row['title']) . "</span>"; 
                echo "</div>";
                
                echo "<div class='response-date'>";
                echo "<label><strong>Expiry Date:</strong></label><br>"; 
                echo "<span>" . htmlspecialchars($row['expirationDate']) . "</span>"; 
                echo "</div>";

                echo "</div>"; 
            }
        } else {
            echo "<p>No missed questionnaires.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error retrieving missed data: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>No user is logged in.</p>";
}
?>
