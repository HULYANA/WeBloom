<?php
session_start();
header('Content-Type: application/json');

require_once '../../../shared/dbcon.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['questionnaireID']) || !isset($data['answers'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

$questionnaireID = intval($data['questionnaireID']);
$studentID = isset($_SESSION['userID']) ? intval($_SESSION['userID']) : null;
$answers = $data['answers'];

if (!$studentID) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$stmt = $pdo->prepare("SELECT studentID FROM students WHERE userID = ?");
$stmt->execute([$studentID]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo json_encode(['success' => false, 'message' => 'Student not found in the database']);
    exit();
}

$studentID = $student['studentID'];

try {
    $responseDate = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO responses (studentID, questionnaireID, responseDate) VALUES (?, ?, ?)");
    $stmt->execute([$studentID, $questionnaireID, $responseDate]);

    $responseID = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO responseAnswer (responseID, answer) VALUES (?, ?)");

    foreach ($answers as $answer) {
        if (isset($answer['answer']) && !empty($answer['answer'])) {
            $choice = $answer['answer'] ? $answer['answer'] : null;

            try {
                $stmt->execute([$responseID, $choice]);
            } catch (PDOException $e) {
                error_log("Error inserting response answer: " . $e->getMessage());
            }
        } else {
            error_log("Missing answer for responseID: $responseID. Data: " . print_r($answer, true));
        }
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}