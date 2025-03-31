<?php include '../backend/php/getCredentials.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>surveytes</title>
    <link rel="stylesheet" type="text/css" href="../../student/assets/css/studstyles.css">
</head>

<body>

<header id="stud-accounts">
    <nav class="navbar">
        <div class="logo">
            <a href="studHome.php">surveytes</a>
        </div>
        <div class="navbar-buttons">
            <a class="nav-btn" href="studHome.php">home</a>
            <a class="nav-btn" href="studAccounts.php">profile</a>
            <a class="logout-btn" href="#" id="logoutBtn">logout</a>
            <div class="overlay" id="logoutOverlay">
                <div class="overlay-content">
                    <h2>Are you sure you want to log out?</h2>
                    <div class="overlay-actions">
                        <button id="confirmLogout" class="confirm-btn">Yes</button>
                        <button id="cancelLogout" class="cancel-btn">No</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="account-" id="accountContainer">
        <div class="surveyh-container" style="margin-top: 15vh; display: block;">
            <?php if (isset($result)): ?>
                <h4><?php echo htmlspecialchars($result['firstname']) . ' ' . htmlspecialchars($result['lastname']); ?></h4>
                <h5> <?php echo htmlspecialchars($result['programName']); ?></h5>
            <?php else: ?>
                <p><?php echo isset($errorMessage) ? htmlspecialchars($errorMessage) : 'No student information available.'; ?></p>
            <?php endif; ?>
        </div>
        <div class="orb-container" id="orbContainer" style="height: 90vh; margin-top: -15%"></div>
    </div>
    
    <div class="moreinfo-cntr" id="moreInfoContainer">
        <div class="filter-container">
            <button id="responsesBtn">Responses</button>
            <button id="missedBtn">Missed</button>
        </div>
        <div class="response-container" id="responseContainer">
        </div>
        <div class="missed-container" id="missedContainer">
        </div>
    </div>
    <button class="top-btn" id="topBtn">↑</button>
</main>

<footer>
    <?php include('footer.php'); ?>
</footer>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-oT/2Rx 5EGzDFA8hIeMNdFGRwRFEEd5uyN13fZK8Kf2G/jnDQnRac7OY80CwDv0W4" crossorigin="anonymous"></script>
<script src="../../student/assets/js/script.js"></script>

</body>
</html>