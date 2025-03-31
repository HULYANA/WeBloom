<?php
session_start();
include '../../shared/dbcon.php';  

if (!$pdo) {
    die("Database connection is not established.");
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>surveytes</title>
    <link rel="stylesheet" type="text/css" href="../../student/assets/css/studstyles.css">
</head>
<body>

    <header>
        <nav class="navbar" style="background-color: transparent; border-bottom: 1px solid black; font-style: normal; position: sticky;">
            <div class="logo" style="margin-left: 10%;">
                <a href="studAccounts.php" style="color: black; background-color: transparent;">&lt;</a>
                <a href="responses.php" style="color: black; background-color: transparent;">surveytes</a>
            </div>
            <div class="nav-text">
                <h2>Questionnaire ID: <?php echo isset($_GET['questionnaireID']) ? htmlspecialchars($_GET['questionnaireID']) : 'N/A'; ?></h2>
            </div>
        </nav>
    </header>

    <main>
        <div class="answer-container" id="answerContainer">
                <?php
                if (isset($_GET['questionnaireID'])) {
                    $questionnaireID = $_GET['questionnaireID'];

                    try {
                        $query = "
                        SELECT q.questionText, c.choice
                        FROM questions q
                        INNER JOIN questionnaires qs ON q.questionID = qs.questionID
                        LEFT JOIN choices c ON q.questionID = c.questionID
                        WHERE qs.questionnaireID = :questionnaireID;
                        ";

                        $stmt = $pdo->prepare($query);
                        $stmt->execute(['questionnaireID' => $questionnaireID]);

                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($results)) {
                            $questions = [];
                            foreach ($results as $row) {
                                $questions[$row['questionText']][] = $row['choice'];
                            }

                            foreach ($questions as $questionText => $choices) {
                                $randomChoice = $choices[array_rand($choices)]; 
                                
                                echo "<div class='question-container'>"; 
                                echo "<div class='question-row'>";
                                echo "<span>" . htmlspecialchars($questionText) . "</span>";
                                echo "</div>"; 
                                
                                echo "<div class='answer-row'>";
                                echo "<span>" . htmlspecialchars($randomChoice) . "</span>"; 
                                echo "</div>";  
                                
                                echo "</div>";  
                            }
                        } else {
                            echo "<p>No questions found for this questionnaire.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error retrieving data: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                } else {
                    echo "<p>No questionnaire ID provided.</p>";
                }
                ?>
            </div>
        </div>
    </main>

    <script src="../../student/assets/js/script.js"></script>

</body>
</html>
