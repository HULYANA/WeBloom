<?php
session_start();
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
                    <h2 id="logout-message">Are you sure you want to log out?</h2>
                    <div class="overlay-actions">
                        <button id="confirmLogout" class="confirm-btn">Yes</button>
                        <button id="cancelLogout" class="cancel-btn">No</button>  
                    </div>
                    <h2 class= "logout-warning" style="color:red;">WARNING: Answers won't be saved when you log out.</h2>
                </div>
            </div>
        </div>
    </nav>
</header>

<main>
    <div class="welcome-container">
        <h1>Hi, <?php echo htmlspecialchars(ucfirst($_SESSION['username'])); ?>!</h1>
        <p>Welcome back! Your <span class="highlight-bg">insights</span> are <span class="highlight">INVALUABLE</span> to us—share your thoughts and help shape a <span class="highlight"><i>better experience.</i></span></p>
    </div>
    
    <div class="survey-container" id="surveyContainer">
        <div class="surveyh-container">
            <h1>surveys</h1>
            <h2>Help us enhance your experience by sharing your feedback! Your insights are crucial for guiding our improvements and innovations.</h2>
        </div>
        <div class="search-bar-container">
            <div class="search-bar-container">
                <input type="text" id="searchInput" placeholder="Search surveys..." class="search-bar">
                <button type="button" class="search-btn" id="searchBtn">Search</button>
            </div>
        </div>
        <div class="survey-card-container" id="surveyCardContainer">
            <?php
            require_once '../../student/backend/php/getSurvey.php';
            ?>
        </div>
        <div class="orb-container" id="orbContainer"></div>
        <span class="page-counter">1 / <?php echo $totalPages; ?></span>
        <div class="cardnav-buttons">
            <button class="arrow left-arrow">Prev</button>
            <button class="arrow right-arrow">Next</button>
        </div>
    </div>

    <div class="feedback-cntr">
        <h1>Take a <span class="highlight"><i>moment to share</i></span> your <span class="highlight-bg">feedback</span> about your experience on our website.</h1>
        <hr>
        <form action="#" method="post" class="feedback-form">
            <textarea id="feedback" name="feedback" rows="5" placeholder="Share your thoughts here..."></textarea>
            <button type="submit" class="submit-btn">Submit Feedback</button>
        </form>
    </div>

    <button class="top-btn" id="topBtn">↑</button>
</main>

<footer>
    <?php include('footer.php'); ?>
</footer>

<script src="../../student/assets/js/script.js"></script>
</body>
</html>