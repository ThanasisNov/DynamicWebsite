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

// Έλεγχος αν υπάρχει το ID του εγγράφου
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Ανάκτηση διαδρομής του αρχείου από τη βάση δεδομένων
    $stmt = $conn->prepare("SELECT arxeio FROM Eggrafa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($file_path);
    $stmt->fetch();
    $stmt->close();

    // Διαγραφή του αρχείου από τον φάκελο
    if ($file_path && file_exists($file_path)) {
        if (!unlink($file_path)) {
            echo "Αποτυχία διαγραφής του αρχείου.";
            exit();
        }
    }

    // Διαγραφή του εγγράφου από τη βάση δεδομένων
    $stmt = $conn->prepare("DELETE FROM Eggrafa WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: documents.php');
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά τη διαγραφή του εγγράφου.";
    }
} else {
    echo "Το έγγραφο δεν βρέθηκε.";
    exit();
}
?>
