<?php
include '../../../shared/dbcon.php';

session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    try {

        $query = "
        SELECT DISTINCT q.title, q.questionnaireID, q.evalautionPeriod, q.publicationStatus, r.responseDate, r.responseID
        FROM responses r
        INNER JOIN questionnaires q ON r.questionnaireID = q.questionnaireID
        INNER JOIN students s ON r.studentID = s.studentID
        WHERE s.userID = ?
        ORDER BY r.responseDate DESC";


        $stmt = $pdo->prepare($query);
        $stmt->execute([$userID]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            echo "<div class='missed-container'>";
            foreach ($results as $row) {
                echo "<div class='response-item'>";
                
                echo "<div class='response-title'>";
                echo "<label><strong>Title:</strong></label><br>"; 
                echo "<span>" . htmlspecialchars($row['title']) . "</span>"; 
                echo "</div>";
                
                echo "<div class='response-date'>";
                echo "<label><strong>Response Date:</strong></label><br>"; 
                echo "<span>" . htmlspecialchars($row['responseDate']) . "</span>"; 
                echo "</div>";
                
                echo "<div class='response-period'>";
                echo "<a href='responses.php?responseID=" . $row['responseID'] . "&questionnaireID=" . $row['questionnaireID'] . "' class='view-button'>View</a>";
                echo "</div>";

                // echo "<div class='response-published-date'>";
                // echo "<div class='icon-container'>";
                // echo "<img class='download-icon' src='../../student/assets/imgs/dl-icon.png' alt='Download Icon'>";
                // echo "</div>";
                // echo "</div>";

                echo "</div>"; 
            }
            echo "</div>"; 
        } else {
            echo "<p>No questionnaires responded to yet.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error retrieving data: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>No user is logged in.</p>";
}
?>
