<?php
session_start();
require_once 'db_connect.php';

$message = '';
$messageType = '';

// Get student ID from URL
$id = $_GET['id'] ?? '';

if (empty($id) || !is_numeric($id)) {
    $_SESSION['error'] = "Invalid student ID.";
    header('Location: select.php');
    exit;
}

try {
    // First, check if student exists
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        $_SESSION['error'] = "Student not found.";
        header('Location: select.php');
        exit;
    }
    
    // If we reach here, delete the student
    $deleteStmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $deleteStmt->execute([$id]);
    
    if ($deleteStmt->rowCount() > 0) {
        $_SESSION['success'] = "Student '{$student['name']}' has been deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete student.";
    }
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

// Redirect back to list
header('Location: select.php');
exit;
?>