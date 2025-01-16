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

    // Ανάκτηση της ανακοίνωσης από τη βάση
    $stmt = $conn->prepare("SELECT * FROM Anakainoseis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "Η ανακοίνωση δεν βρέθηκε.";
        exit();
    }
} else {
    echo "Η ανακοίνωση δεν βρέθηκε.";
    exit();
}

// Ενημέρωση της ανακοίνωσης
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $thema = $_POST['thema'];
    $keimeno = $_POST['keimeno'];
    $link = $_POST['link']; // Λήψη του υπερσυνδέσμου από τη φόρμα

    $stmt = $conn->prepare("UPDATE Anakainoseis SET thema = ?, keimeno = ?, link = ? WHERE id = ?");
    $stmt->bind_param("sssi", $thema, $keimeno, $link, $id);

    if ($stmt->execute()) {
        // Επιστροφή στην προηγούμενη σελίδα
        header('Location: announcement.php');
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά την ενημέρωση της ανακοίνωσης.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Επεξεργασία Ανακοίνωσης</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Επεξεργασία Ανακοίνωσης</h1>

        <form action="" method="post">
            <label for="thema">Θέμα:</label>
            <input type="text" id="thema" name="thema" value="<?php echo htmlspecialchars($result['thema']); ?>" required>

            <label for="keimeno">Κείμενο:</label>
            <textarea id="keimeno" name="keimeno" required><?php echo htmlspecialchars($result['keimeno']); ?></textarea>
            
            <label for="link">Υπερσύνδεσμος:</label>
            <input type="text" id="link" name="link" value="<?php echo isset($result['link']) ? htmlspecialchars($result['link']) : ''; ?>"><br><br>

            <input type="submit" value="Αποθήκευση" class="button">
        </form>
    </div>
</body>

</html>
