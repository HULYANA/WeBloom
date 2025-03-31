<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $pwd = trim($_POST["password"]);
    echo phpversion();

    error_log("Entered Username: " . $username);
    error_log("Entered Password: " . $pwd);

    try {
        require_once "../shared/dbcon.php";

        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            error_log("Password from DB: " . $user['pass']);
            
            if ($pwd && $pwd === $user['pass']) {
                $_SESSION['username'] = $user['username'];
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['role'] = $user['role'];

                echo "<script>
                        sessionStorage.setItem('username', '" . $_SESSION['username'] . "');
                        sessionStorage.setItem('userID', '" . $_SESSION['userID'] . "');
                        sessionStorage.setItem('role', '" . $_SESSION['role'] . "');
                      </script>";

                if ($user['role'] === 'Admin') {
                    echo "<script>
                            window.location.href = '../admin/view/Admin_home.php';
                          </script>";
                    die();
                }

                $studentQuery = "SELECT * FROM students WHERE userID = ?";
                $studentStmt = $pdo->prepare($studentQuery);
                $studentStmt->execute([$user['userID']]);
                $student = $studentStmt->fetch(PDO::FETCH_ASSOC);

                if ($student) {
                    $_SESSION['studentID'] = $student['studentID'];
                    $_SESSION['programID'] = $student['programID'];

                    echo "<script>
                            window.location.href = '../student/view/studHome.php';
                          </script>";
                    die();
                } else {
                    echo "<script>
                            alert('No program assigned for this student.');
                            window.location.href = '../Login.php';
                          </script>";
                    die();
                }
            } else {
                header("Location: ../Login.php?error=invalidCredentials");
                die();
            }
        } else {
            header("Location: ../Login.php?error=invalidCredentials");
            die();
        }
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../Login.php");
    die();
}
?>
