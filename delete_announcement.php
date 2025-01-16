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

// Έλεγχος αν υπάρχει το ID της ανακοίνωσης
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Διαγραφή της ανακοίνωσης από τη βάση
    $stmt = $conn->prepare("DELETE FROM Anakainoseis WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Επιστροφή στην προηγούμενη σελίδα
        header('Location: announcement.php');
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά τη διαγραφή της ανακοίνωσης.";
    }
} else {
    echo "Η ανακοίνωση δεν βρέθηκε.";
}
?>
