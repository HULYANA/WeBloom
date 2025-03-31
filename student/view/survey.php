<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey</title>
    <link rel="stylesheet" type="text/css" href="../../student/assets/css/survey-style.css?v=1.1">
</head>
<body>
    
    <div class="header-container">
        <button class="back-to-home" onclick="returnHome()"><</button>
        <span id="logo">surveytes</span>
        <button id="submit-btn" style="display:none;" onclick="submitSurvey()">Submit</button>
    </div>

    <div class="container">
        <div id="progress-container">
            <div id="progress-bar"></div>
            <span id="progress-percentage"></span>

        </div>
        
        <?php
        require_once '../../shared/dbcon.php';

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo "<p>Error: No questionnaire ID provided.</p>";
            exit;
        }

        $questionnaireId = intval($_GET['id']);
        
        try {
            $query = "SELECT title FROM questionnaires WHERE questionnaireID = :questionnaireId LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':questionnaireId', $questionnaireId, PDO::PARAM_INT);
            $stmt->execute();
            $title = $stmt->fetchColumn();

            if (!$title) {
                echo "<p>Error: Questionnaire not found.</p>";
                exit;
            }
            echo "<h1>{$title}</h1>";

            $query = "
                SELECT q.questionID, q.questionText, c.choice
                FROM questions q
                LEFT JOIN choices c ON q.questionID = c.questionID
                INNER JOIN questionnaires qn ON qn.questionID = q.questionID
                WHERE qn.questionnaireID = :questionnaireId
            ";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':questionnaireId', $questionnaireId, PDO::PARAM_INT);
            $stmt->execute();

            $questions = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $questionID = $row['questionID'];
                if (!isset($questions[$questionID])) {
                    $questions[$questionID] = [
                        'questionText' => htmlspecialchars($row['questionText']),
                        'choices' => []
                    ];
                }
                if ($row['choice']) {
                    $questions[$questionID]['choices'][] = htmlspecialchars($row['choice']);
                }
            }

            echo "<div id='question-container'>";
            echo "<br>";
            foreach ($questions as $questionID => $question) {
                echo "<div class='question' data-question-id='{$questionID}'>";
                echo "<h4 class='question-text'>{$question['questionText']}</h4>";
                echo "<ul id='unlisted-order'>";
                foreach ($question['choices'] as $choice) {
                    echo "
                        <li class='choice'>
                            <label>
                                <input type='radio' name='question-{$questionID}' />
                                <span class='bullet'></span>
                                <span class='choice-text'>{$choice}</span>
                            </label>
                        </li>";
                }
                echo "</ul></div>";
            }
            echo "</div>";
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
        ?>
        
        <div id="pagination-controls">
            <button onclick="prevPage()" id="prev-btn" disabled><</button>
            <button onclick="nextPage()" id="next-btn">></button>
        </div>
    </div>

    <script>
        const questions = <?php echo json_encode(array_values($questions)); ?>;
    </script>
     <script>
        const questionnaireID = <?php echo $questionnaireId; ?>;
        const studentID = <?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : 'null'; ?>;
    </script>
    
    <script src="../../student/assets/js/survey-script.js"></script>
</body>
</html>
