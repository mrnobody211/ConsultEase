<?php require 'auth.php'; ?>

<?php
require_once 'db.php';
require_once 'ConsultationRequest.php';

$database = new Database();
$db = $database->connect();
$request = new ConsultationRequest($db);

$students = $db->query("SELECT s.StudentID, u.FullName FROM students s JOIN users u ON s.UserID = u.UserID")->fetchAll(PDO::FETCH_ASSOC);

$slots = $db->query("SELECT sl.SlotID, u.FullName AS FacultyName, sl.DayOfWeek, sl.StartTime, sl.EndTime
                      FROM consultation_slots sl
                      JOIN faculty f ON sl.FacultyID = f.FacultyID
                      JOIN users u ON f.UserID = u.UserID
                      WHERE sl.Status = 'Open'")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request->StudentID = $_POST['StudentID'];
    $request->SlotID = $_POST['SlotID'];
    $request->ConsultationDate = $_POST['ConsultationDate'];
    $request->Purpose = $_POST['Purpose'];
    $request->Status = 'Pending';
    $request->DateRequested = date('Y-m-d');

    $result = $request->addConsultationRequest();

    if ($result === "slot_taken") {
        $message = "That slot is already booked for that date.";
    } elseif ($result) {
        header("Location: view_requests.php?msg=added");
        exit;
    } else {
        $message = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Consultation Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="brand">ConsultEase</a>
        <div class="links">
            <a href="add_request.php">New Request</a>
            <a href="view_requests.php">View All</a>
            <a href="search_requests.php">Search</a>
        </div>
    </nav>

    <div class="container">
        <h2>Submit a Consultation Request</h2>
        <p class="subtitle">Pick your instructor's open slot and let them know why you're coming.</p>

        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="add_request.php">
            <label>Student</label>
            <select name="StudentID" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $s): ?>
                    <option value="<?= $s['StudentID'] ?>"><?= htmlspecialchars($s['FullName']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Faculty Slot</label>
            <select name="SlotID" required>
                <option value="">-- Select Slot --</option>
                <?php foreach ($slots as $s): ?>
                    <option value="<?= $s['SlotID'] ?>">
                        <?= htmlspecialchars($s['FacultyName']) ?> — <?= $s['DayOfWeek'] ?> (<?= $s['StartTime'] ?>–<?= $s['EndTime'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Consultation Date</label>
            <input type="date" name="ConsultationDate" required>

            <label>Purpose</label>
            <textarea name="Purpose" rows="3" required placeholder="e.g. Discuss capstone chapter 1 feedback"></textarea>

            <button type="submit">Submit Request</button>
        </form>
    </div>
</body>
</html>