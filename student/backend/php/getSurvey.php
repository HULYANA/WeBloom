<?php
include '../../shared/dbcon.php';
try {
    if (!isset($_SESSION['programID'])) {
        echo "<p>Please log in to view surveys.</p>";
        exit; 
    }

    $programID = $_SESSION['programID'];  
    $countQuery = "SELECT COUNT(DISTINCT title) as total 
        FROM questionnaires 
        WHERE (programID = :programID OR programID = 001) 
        AND evalautionPeriod = 'AY 2023-2024 First Semester' 
        AND publicationStatus = 'Published'";

    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute(['programID' => $programID]);
    $totalQuestionnaires = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];  
    $cardsPerPage = 4; 
    $totalPages = ceil($totalQuestionnaires / $cardsPerPage);
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;  
    $offset = ($currentPage - 1) * $cardsPerPage;  
    
    $query = "SELECT DISTINCT questionnaireID, title, MAX(datePublished) as datePublished 
    FROM questionnaires 
    WHERE (programID = :programID OR programID = 001) 
    AND evalautionPeriod = 'AY 2023-2024 First Semester' 
    AND publicationStatus = 'Published' 
    GROUP BY questionnaireID, title";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);  
    $stmt->execute(); 
    $cardIndex = 0; 
    
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questionnaireId = isset($row['questionnaireID']) ? htmlspecialchars($row['questionnaireID']) : ''; 
            $title = isset($row['title']) ? htmlspecialchars($row['title']) : ''; 
            $datePublished = isset($row['datePublished']) ? htmlspecialchars($row['datePublished']) : ''; 
            
            $pageNumber = floor($cardIndex / $cardsPerPage) + 1;
            echo "<div class='survey-card' data-title='{$title}' data-date='{$datePublished}' data-page='{$pageNumber}'>";
            echo "<a href='../../student/view/survey.php?id={$questionnaireId}'>"; 
            echo "<h3>{$title}</h3>";
            echo "<p style='color: gray; position: absolute; bottom: 20px; left: 20px;'><small>Published on: {$datePublished}</small></p>";
            echo "<img src='../../student/assets/imgs/arrow.png' alt='Arrow' class='card-arrow'>";
            echo "</a></div>";
            $cardIndex++;  
        }
    } else {
        echo "<p>No surveys available.</p>";
    }           
    echo "<script>var totalPages = {$totalPages};</script>";
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
