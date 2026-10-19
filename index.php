<?php require 'auth.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>ConsultEase</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>  
    <nav>
        <a href="index.php" class="brand">ConsultEase</a>
        <div class="links">
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
            <a href="add_request.php">New Request</a>   
            <a href="view_requests.php">View All</a>
            <a href="search_requests.php">Search</a>
        </div>
    </nav>

    <div class="container">
        <h2>Welcome to ConsultEase</h2>
        <p class="subtitle">Faculty-Student Consultation Scheduling — CRMC</p>

        <ul class="card-list">
            <li><a href="add_request.php">📝 Submit a Consultation Request</a></li>
            <li><a href="view_requests.php">📋 View All Requests</a></li>
            <li><a href="search_requests.php">🔍 Search Requests</a></li>
        </ul>
    </div>
</body>
</html> 