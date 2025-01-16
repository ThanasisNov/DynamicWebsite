<?php
session_start();
session_unset(); // Διαγραφή όλων των δεδομένων συνεδρίας
session_destroy(); // Καταστροφή συνεδρίας
header('Location: index.php'); // Ανακατεύθυνση στη σελίδα σύνδεσης
exit();
?>
