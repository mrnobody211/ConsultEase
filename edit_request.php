<?php require 'auth.php'; ?>
<?php
require_once 'db.php';

$database = new Database();
$db = $database->connect();
$row = null;    

if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM consultation_requests WHERE RequestID = :id");
    $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$row) {
    header("Location: view_requests.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Consultation Request</title>
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

    <div class="container">
        <h2>Edit Request #<?= $row['RequestID'] ?></h2>
        <p class="subtitle">Update the date, purpose, or status of this request.</p>

        <form method="POST" action="update_request.php">
            <input type="hidden" name="RequestID" value="<?= $row['RequestID'] ?>">

            <label>Consultation Date</label>
            <input type="date" name="ConsultationDate" value="<?= $row['ConsultationDate'] ?>" required>

            <label>Purpose</label>
            <textarea name="Purpose" rows="3" required><?= htmlspecialchars($row['Purpose']) ?></textarea>

            <label>Status</label>
            <select name="Status">
                <?php foreach (['Pending','Approved','Declined','Completed'] as $s): ?>
                    <option value="<?= $s ?>" <?= $row['Status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>