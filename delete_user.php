<?php
session_start();

// Σύνδεση με τη βάση δεδομένων χρησιμοποιώντας τον profile χρήστη
$host = "localhost";
$dbname = "student4198partB";
$username = "profileUSER";
$password = "Testing1001@";

$conn = new mysqli($host, $username, $password, $dbname);

// Έλεγχος σύνδεσης
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Έλεγχος αν ο χρήστης είναι Tutor
if (!isset($_SESSION['rolos']) || $_SESSION['rolos'] !== 'Tutor') {
    echo "Μη εξουσιοδοτημένη πρόσβαση.";
    exit();
}

// Έλεγχος αν υπάρχει το ID του χρήστη
if (!isset($_GET['id'])) {
    echo "Μη έγκυρη αίτηση.";
    exit();
}

$id = intval($_GET['id']);

// Διαγραφή του χρήστη
$stmt = $conn->prepare("DELETE FROM Xristes WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: users.php");
    exit();
} else {
    echo "Υπήρξε πρόβλημα κατά τη διαγραφή του χρήστη.";
}
?>
