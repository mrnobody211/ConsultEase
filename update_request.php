<?php require 'auth.php'; ?>
<?php
require_once 'db.php';
require_once 'ConsultationRequest.php';

$database = new Database();
$db = $database->connect();
$request = new ConsultationRequest($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['RequestID'];

    $request->ConsultationDate = $_POST['ConsultationDate'];
    $request->Purpose = $_POST['Purpose'];
    $request->Status = $_POST['Status'];

    if ($request->updateConsultationRequest($id)) {
        header("Location: view_requests.php?msg=updated");
        exit;
    } else {
        echo "Update failed.";
    }
}
