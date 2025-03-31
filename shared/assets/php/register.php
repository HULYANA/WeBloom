<?php
session_start();

require_once '../../dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $studentID = $_POST['studentID'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $gender = $_POST['gender'];
    $programID = $_POST['programID'];
    $role = $_POST['role'];

    try {
        $usernameCheckQuery = "SELECT * FROM users WHERE username = ?";
        $usernameCheckStmt = $pdo->prepare($usernameCheckQuery);
        $usernameCheckStmt->execute([$username]);
        $existingUser = $usernameCheckStmt->fetch(PDO::FETCH_ASSOC);

        $emailCheckQuery = "SELECT * FROM users WHERE email = ?";
        $emailCheckStmt = $pdo->prepare($emailCheckQuery);
        $emailCheckStmt->execute([$email]);
        $existingEmail = $emailCheckStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['errorMessage'] = ["Username already exists. Please choose a different username."];
            header("Location: ../../../Login.php?showModal=true");
            exit();
        }
        
        if ($existingEmail) {
            $_SESSION['errorMessage'] = ["Email already exists. Please choose a different email."];
            header("Location: ../../../Login.php?showModal=true");
            exit();
        }

        $userQuery = "INSERT INTO users (userID, username, pass, email, lastname, firstname, gender, role) 
                      VALUES (:userID, :username, :password, :email, :lastname, :firstname, :gender, :role)";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->execute([
            ':userID' => $userID,
            ':username' => $username,
            ':password' => $password,
            ':email' => $email,
            ':lastname' => $lastname,
            ':firstname' => $firstname,
            ':gender' => $gender,
            ':role' => $role
        ]);

        $studentQuery = "INSERT INTO students (studentID, userID, programID) 
                         VALUES (:studentID, :userID, :programID)";
        $studentStmt = $pdo->prepare($studentQuery);
        $studentStmt->execute([
            ':studentID' => $studentID,
            ':userID' => $userID,
            ':programID' => $programID
        ]);

        header("Location: ../../../Login.php?success=registered");
        exit();
    } catch (PDOException $e) {
        $_SESSION['errorMessage'] = ["Error: " . $e->getMessage()];
        header("Location: ../../../Login.php?showModal=true");
        exit();
    }
}
?>
