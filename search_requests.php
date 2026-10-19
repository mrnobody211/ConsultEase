<?php require 'auth.php'; ?>
<?php
require_once 'db.php';
require_once 'ConsultationRequest.php';

$database = new Database();
$db = $database->connect();
$request = new ConsultationRequest($db);

$results = [];
$searched = false;
if (isset($_GET['keyword']) && trim($_GET['keyword']) !== '') {
    $results = $request->searchConsultationRequests($_GET['keyword']);
    $searched = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Consultation Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="brand">ConsultEase</a>
        <div class="links">
            <a href="add_request.php">New Request</a>
            <a href="view_requests.php">View All</a>
            <a href="search_requests.php">Search</a>
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        </div>
    </nav>

    <div class="container wide">
        <h2>Search Requests</h2>
        <p class="subtitle">Search by student name, faculty name, purpose, status, or date.</p>

        <form method="GET">
            <input type="text" name="keyword" placeholder="e.g. Kevin, capstone, Pending..."
                   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <button type="submit">Search</button>
        </form>

        <?php if ($searched): ?>
            <?php if (empty($results)): ?>
                <div class="empty-state"><p>No matching requests found.</p></div>
            <?php else: ?>
            <table>
                <tr><th>Student</th><th>Faculty</th><th>Date</th><th>Purpose</th><th>Status</th></tr>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['StudentName']) ?></td>
                    <td><?= htmlspecialchars($row['FacultyName']) ?></td>
                    <td><?= $row['ConsultationDate'] ?></td>
                    <td><?= htmlspecialchars($row['Purpose']) ?></td>
                    <td><span class="badge badge-<?= $row['Status'] ?>"><?= $row['Status'] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>