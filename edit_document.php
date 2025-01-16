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

    // Ανάκτηση του εγγράφου από τη βάση
    $stmt = $conn->prepare("SELECT * FROM Eggrafa WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "Το έγγραφο δεν βρέθηκε.";
        exit();
    }
} else {
    echo "Το έγγραφο δεν βρέθηκε.";
    exit();
}

// Ενημέρωση του εγγράφου
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titlos = $_POST['titlos'];
    $perigrafi = $_POST['perigrafi'];

    if (isset($_FILES['arxeio']) && $_FILES['arxeio']['error'] === UPLOAD_ERR_OK) {
        $file_path = 'uploads/' . basename($_FILES['arxeio']['name']);

        // Διαγραφή του παλιού αρχείου
        if (file_exists($result['arxeio'])) {
            unlink($result['arxeio']);
        }

        // Μεταφόρτωση νέου αρχείου
        if (move_uploaded_file($_FILES['arxeio']['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("UPDATE Eggrafa SET titlos = ?, perigrafi = ?, arxeio = ? WHERE id = ?");
            $stmt->bind_param("sssi", $titlos, $perigrafi, $file_path, $id);
        } else {
            echo "Σφάλμα κατά τη μεταφόρτωση του αρχείου.";
            exit();
        }
    } else {
        $stmt = $conn->prepare("UPDATE Eggrafa SET titlos = ?, perigrafi = ? WHERE id = ?");
        $stmt->bind_param("ssi", $titlos, $perigrafi, $id);
    }

    if ($stmt->execute()) {
        // Επιστροφή στην προηγούμενη σελίδα
        header('Location: documents.php');
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά την ενημέρωση του εγγράφου.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Επεξεργασία Εγγράφου</title>
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

        form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            text-align: left;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: black;
        }

        form input, form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            color: black;
        }

        form textarea {
            resize: both;
            min-height: 150px;
        }

        form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .current-file {
            font-size: 0.9em;
            color: #555;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1>Επεξεργασία Εγγράφου</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="titlos">Τίτλος:</label>
            <input type="text" id="titlos" name="titlos" value="<?php echo htmlspecialchars($result['titlos']); ?>" required>

            <label for="perigrafi">Περιγραφή:</label>
            <textarea id="perigrafi" name="perigrafi" required><?php echo htmlspecialchars($result['perigrafi']); ?></textarea>

            <label for="arxeio">Αρχείο:</label>
            <input type="file" id="arxeio" name="arxeio">
            <p class="current-file">Τρέχον Αρχείο: <?php echo htmlspecialchars($result['arxeio']); ?></p>

            <button type="submit">Αποθήκευση</button>
        </form>
    </div>
</body>

</html>
