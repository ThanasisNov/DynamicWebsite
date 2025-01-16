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

// Έλεγχος αν υπάρχει το ID της εργασίας
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    // Ανάκτηση της εργασίας από τη βάση
    $stmt = $conn->prepare("SELECT * FROM Ergasies WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if (!$result) {
        echo "Η εργασία δεν βρέθηκε.";
        exit();
    }
} else {
    echo "Η εργασία δεν βρέθηκε.";
    exit();
}

// Ενημέρωση της εργασίας
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stoxoi = $_POST['stoxoi'];
    $paradotea = $_POST['paradotea'];
    $imerominia_paradosis = $_POST['imerominia_paradosis'];

    if (isset($_FILES['ekfonisi']) && $_FILES['ekfonisi']['error'] === UPLOAD_ERR_OK) {
        $file_path = 'uploads_homework/' . basename($_FILES['ekfonisi']['name']);

        // Διαγραφή παλιού αρχείου
        if (file_exists($result['ekfonisi'])) {
            unlink($result['ekfonisi']);
        }

        // Μεταφόρτωση νέου αρχείου
        if (move_uploaded_file($_FILES['ekfonisi']['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("UPDATE Ergasies SET stoxoi = ?, paradotea = ?, imerominia_paradosis = ?, ekfonisi = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $stoxoi, $paradotea, $imerominia_paradosis, $file_path, $id);
        } else {
            echo "Σφάλμα κατά τη μεταφόρτωση του αρχείου.";
            exit();
        }
    } else {
        $stmt = $conn->prepare("UPDATE Ergasies SET stoxoi = ?, paradotea = ?, imerominia_paradosis = ? WHERE id = ?");
        $stmt->bind_param("sssi", $stoxoi, $paradotea, $imerominia_paradosis, $id);
    }

    if ($stmt->execute()) {
        header('Location: homework.php');
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά την ενημέρωση της εργασίας.";
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Επεξεργασία Εργασίας</title>
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
        <h1>Επεξεργασία Εργασίας</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="stoxoi">Στόχοι:</label>
            <textarea id="stoxoi" name="stoxoi" required><?php echo htmlspecialchars($result['stoxoi']); ?></textarea>

            <label for="paradotea">Παραδοτέα:</label>
            <textarea id="paradotea" name="paradotea" required><?php echo htmlspecialchars($result['paradotea']); ?></textarea>

            <label for="imerominia_paradosis">Ημερομηνία Παράδοσης:</label>
            <input type="date" id="imerominia_paradosis" name="imerominia_paradosis" value="<?php echo htmlspecialchars($result['imerominia_paradosis']); ?>" required>

            <label for="ekfonisi">Εκφώνηση:</label>
            <input type="file" id="ekfonisi" name="ekfonisi">
            <p class="current-file">Τρέχον Αρχείο: <?php echo htmlspecialchars($result['ekfonisi']); ?></p>

            <button type="submit">Αποθήκευση</button>
        </form>
    </div>
</body>

</html>
