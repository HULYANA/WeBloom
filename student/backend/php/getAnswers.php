<?php
session_start();
include '../../../shared/dbcon.php'; 

if (isset($_GET['questionnaireID'])) {
    $questionnaireID = $_GET['questionnaireID'];

    try {
        $query = "
        SELECT q.questionText
        FROM questions q
        INNER JOIN questionnaires qs ON q.questionID = qs.questionID
        WHERE qs.questionnaireID = :questionnaireID
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['questionnaireID' => $questionnaireID]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($results)) {
            echo json_encode($results); 
        } else {
            echo json_encode(['message' => 'No questions found for this questionnaire.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error retrieving data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No questionnaire ID provided.']);
}
?>
