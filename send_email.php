<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Ανακατεύθυνση αν δεν είναι συνδεδεμένος
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Εισαγωγή των αρχείων της PHPMailer
require 'vendor/autoload.php';

$error_message = null;
$success_message = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ανάκτηση δεδομένων από τη φόρμα
    $senderEmail = $_POST['email'];
    $subject = $_POST['topic'];
    $message = $_POST['message'];

    // Δημιουργία σύνδεσης με τη βάση δεδομένων
    $host = "localhost";
    $dbname = "student4198partB";
    $username = "profileUSER";
    $password = "Testing1001@";

    $conn = new mysqli($host, $username, $password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Ανάκτηση των χρηστών που έχουν ρόλο tutor
    $stmt = $conn->prepare('SELECT email FROM Xristes WHERE rolos = ?');
    $rolos = 'Tutor';
    $stmt->bind_param("s", $rolos);
    $stmt->execute();
    $result = $stmt->get_result();

    $tutors = [];
    while ($row = $result->fetch_assoc()) {
        $tutors[] = $row['email'];
    }

    // Κλείσιμο σύνδεσης
    $stmt->close();
    $conn->close();

    if (empty($tutors)) {
        $error_message = "Δεν βρέθηκαν χρήστες με ρόλο tutor.";
    } else {
        // Δημιουργία PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Ρυθμίσεις SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nova.thanos@gmail.com'; // Το email σας
            $mail->Password = 'mdlm elri datb suil'; // App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Ορισμός κωδικοποίησης
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // Πληροφορίες αποστολέα
            $mail->setFrom($senderEmail, $_SESSION['onoma']);

            // Προσθήκη παραληπτών
            foreach ($tutors as $tutorEmail) {
                $mail->addAddress($tutorEmail);
            }

            // Περιεχόμενο email
            $mail->isHTML(true);
            $mail->Subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
            $mail->Body = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . '<br><br>' .
                          '<strong>Αποστολέας:</strong> ' . htmlspecialchars($senderEmail, ENT_QUOTES, 'UTF-8');
            $mail->AltBody = $message . "\n\nΑποστολέας: " . $senderEmail;

            // Αποστολή email
            $mail->send();
            $success_message = 'Το email στάλθηκε με επιτυχία σε όλους τους tutors.';
        } catch (Exception $e) {
            $error_message = "Το email δεν στάλθηκε. Σφάλμα: {$mail->ErrorInfo}";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Αποστολή Email</title>
  <style>
    body {
      font-family: sans-serif;
      background-color: #f0f0f0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 400px;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
    .success {
      color: green;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Αποστολή Email</h1>

    <?php if (!empty($error_message)): ?>
      <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
      <p class="success"><?php echo htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <a href="communication.php">Επιστροφή στη Φόρμα Επικοινωνίας</a>
  </div>
</body>
</html>
