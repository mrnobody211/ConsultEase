<?php require 'auth.php'; ?>
<?php
require_once 'db.php';
require_once 'ConsultationRequest.php';

$database = new Database();
$db = $database->connect();
$request = new ConsultationRequest($db);

$records = $request->getConsultationRequests();
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Consultation Requests</title>
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
        <h2>All Consultation Requests</h2>
        <p class="subtitle">Every request submitted through ConsultEase.</p>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
            <p class="message success">Request submitted successfully.</p>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <p class="message success">Request updated successfully.</p>
        <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <p class="message success">Request deleted.</p>
        <?php endif; ?>

        <?php if (empty($records)): ?>
            <div class="empty-state">
                <p>No consultation requests yet.</p>
                <p><a href="add_request.php">Submit the first one →</a></p>
            </div>
        <?php else: ?>
        <table>
            <tr>
                <th>ID</th><th>Student</th><th>Faculty</th><th>Date</th>
                <th>Day / Time</th><th>Purpose</th><th>Status</th><th></th>
            </tr>
            <?php foreach ($records as $row): ?>
            <tr>
                <td><?= $row['RequestID'] ?></td>
                <td><?= htmlspecialchars($row['StudentName']) ?></td>
                <td><?= htmlspecialchars($row['FacultyName']) ?></td>
                <td><?= $row['ConsultationDate'] ?></td>
                <td><?= $row['DayOfWeek'] ?>, <?= substr($row['StartTime'],0,5) ?>–<?= substr($row['EndTime'],0,5) ?></td>
                <td><?= htmlspecialchars($row['Purpose']) ?></td>
                <td><span class="badge badge-<?= $row['Status'] ?>"><?= $row['Status'] ?></span></td>
                <td class="actions">
                    <a href="edit_request.php?id=<?= $row['RequestID'] ?>">Edit</a>
                    <a href="delete_request.php?id=<?= $row['RequestID'] ?>" class="delete" onclick="return confirm('Delete this request?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>