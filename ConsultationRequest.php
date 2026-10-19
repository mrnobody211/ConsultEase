<?php
require_once 'db.php';

class ConsultationRequest {
    private $conn;
    private $table = "consultation_requests";

    public $RequestID;
    public $StudentID;
    public $SlotID;
    public $ConsultationDate;
    public $Purpose;
    public $Status;
    public $DateRequested;

    public function __construct($db) {
        $this->conn = $db;
    }

    // helper used inside each transaction
    private function writeLog($action, $details) {
        $stmt = $this->conn->prepare(
            "INSERT INTO activity_logs (UserID, Action) VALUES (?, ?)"
        );
        $stmt->execute([$_SESSION['user_id'], "$action - $details"]);
    }

    // TRANSACTION 1: CREATE
    public function addConsultationRequest() {
        try {
            $this->conn->beginTransaction();

            $checkQuery = "SELECT COUNT(*) FROM {$this->table}
                           WHERE SlotID = :SlotID AND ConsultationDate = :ConsultationDate
                           AND Status IN ('Pending', 'Approved') FOR UPDATE";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':SlotID', $this->SlotID, PDO::PARAM_INT);
            $checkStmt->bindParam(':ConsultationDate', $this->ConsultationDate);
            $checkStmt->execute();

            if ($checkStmt->fetchColumn() > 0) {
                $this->conn->rollBack();
                return "slot_taken";
            }

            $query = "INSERT INTO {$this->table}
                      (StudentID, SlotID, ConsultationDate, Purpose, Status, DateRequested)
                      VALUES (:StudentID, :SlotID, :ConsultationDate, :Purpose, :Status, :DateRequested)";
            $stmt = $this->conn->prepare($query);

            $this->Purpose = htmlspecialchars(strip_tags($this->Purpose));
            $this->Status = $this->Status ?: 'Pending';

            $stmt->bindParam(':StudentID', $this->StudentID, PDO::PARAM_INT);
            $stmt->bindParam(':SlotID', $this->SlotID, PDO::PARAM_INT);
            $stmt->bindParam(':ConsultationDate', $this->ConsultationDate);
            $stmt->bindParam(':Purpose', $this->Purpose);
            $stmt->bindParam(':Status', $this->Status);
            $stmt->bindParam(':DateRequested', $this->DateRequested);
            $stmt->execute();

            $newId = $this->conn->lastInsertId();
            $this->writeLog('ADD', "Added request #$newId (slot {$this->SlotID} on {$this->ConsultationDate})");

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("addConsultationRequest error: " . $e->getMessage());
            return false;
        }
    }

    // READ ALL
    public function getConsultationRequests() {
        $query = "SELECT r.*, u.FullName AS StudentName, uf.FullName AS FacultyName,
                         s.DayOfWeek, s.StartTime, s.EndTime
                  FROM {$this->table} r
                  JOIN students st ON r.StudentID = st.StudentID
                  JOIN users u ON st.UserID = u.UserID
                  JOIN consultation_slots s ON r.SlotID = s.SlotID
                  JOIN faculty f ON s.FacultyID = f.FacultyID
                  JOIN users uf ON f.UserID = uf.UserID
                  ORDER BY r.ConsultationDate DESC, s.StartTime DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("getConsultationRequests error: " . $e->getMessage());
            return [];
        }
    }

    // TRANSACTION 2: UPDATE
    public function updateConsultationRequest($id) {
        $query = "UPDATE {$this->table}
                  SET ConsultationDate = :ConsultationDate,
                      Purpose = :Purpose,
                      Status = :Status
                  WHERE RequestID = :id";
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($query);
            $this->Purpose = htmlspecialchars(strip_tags($this->Purpose));

            $stmt->bindParam(':ConsultationDate', $this->ConsultationDate);
            $stmt->bindParam(':Purpose', $this->Purpose);
            $stmt->bindParam(':Status', $this->Status);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->writeLog('UPDATE', "Updated request #$id (status: {$this->Status})");

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("updateConsultationRequest error: " . $e->getMessage());
            return false;
        }
    }

    // TRANSACTION 3: DELETE
    public function deleteConsultationRequest($id) {
        $query = "DELETE FROM {$this->table} WHERE RequestID = :id";
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->writeLog('DELETE', "Deleted request #$id");

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            error_log("deleteConsultationRequest error: " . $e->getMessage());
            return false;
        }
    }

    // SEARCH
    public function searchConsultationRequests($keyword) {
        $query = "SELECT r.*, u.FullName AS StudentName, uf.FullName AS FacultyName,
                         s.DayOfWeek, s.StartTime, s.EndTime
                  FROM {$this->table} r
                  JOIN students st ON r.StudentID = st.StudentID
                  JOIN users u ON st.UserID = u.UserID
                  JOIN consultation_slots s ON r.SlotID = s.SlotID
                  JOIN faculty f ON s.FacultyID = f.FacultyID
                  JOIN users uf ON f.UserID = uf.UserID
                  WHERE u.FullName LIKE :keyword
                     OR uf.FullName LIKE :keyword
                     OR r.Purpose LIKE :keyword
                     OR r.Status LIKE :keyword
                     OR r.ConsultationDate LIKE :keyword
                  ORDER BY r.ConsultationDate DESC";
        try {
            $stmt = $this->conn->prepare($query);
            $searchTerm = "%" . $keyword . "%";
            $stmt->bindParam(':keyword', $searchTerm);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("searchConsultationRequests error: " . $e->getMessage());
            return [];
        }
    }
}