<?php require 'auth.php'; ?>
<?php
require_once 'db.php';
require_once 'ConsultationRequest.php';

$database = new Database();
$db = $database->connect();
$request = new ConsultationRequest($db);

if (isset($_GET['id'])) {
    if ($request->deleteConsultationRequest($_GET['id'])) {
        header("Location: view_requests.php?msg=deleted");
        exit;
    } else {
        echo "Delete failed.";
    }
}