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

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος και έχει ρόλο 'Tutor'
if (!isset($_SESSION['user_id']) || $_SESSION['rolos'] !== 'Tutor') {
    header('Location: login.php');
    exit();
}

// Αρχικοποίηση μηνύματος σφάλματος
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Λήψη των δεδομένων από τη φόρμα
    $thema = $_POST['thema'];
    $keimeno = $_POST['keimeno'];
    $link = $_POST['link']; // Λήψη του υπερσυνδέσμου
    $imerominia = date('Y-m-d'); // Τρέχουσα ημερομηνία

    // Εισαγωγή των δεδομένων στη βάση
    $stmt = $conn->prepare('INSERT INTO Anakainoseis (thema, keimeno, imerominia, link) VALUES (?, ?, ?, ?)');
    $stmt->bind_param("ssss", $thema, $keimeno, $imerominia, $link);

    if ($stmt->execute()) {
        // Ανακατεύθυνση στο announcement.php
        header('Location: announcement.php');
        exit();
    } else {
        $error_message = 'Υπήρξε πρόβλημα κατά την αποθήκευση της ανακοίνωσης.';
    }
}
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Προσθήκη Ανακοίνωσης</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f0f0;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        .container {
            height: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-image: url("im2.jpg");
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            text-align: center;
            color: white;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
        }

        label {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            resize: both;
            height: 200px;
        }

        .button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Προσθήκη Ανακοίνωσης</h1>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="add_announcement.php" method="post">

            <label for="thema">Θέμα:</label>
            <input type="text" id="thema" name="thema" required>
            <label for="keimeno">Κείμενο:</label>
            <textarea id="keimeno" name="keimeno" required></textarea>
            <label for="link">Υπερσύνδεσμος:</label>
            <input type="text" id="link" name="link"><br><br>
            <input type="submit" value="Προσθήκη" class="button">
        </form>
    </div>
</body>

</html>
