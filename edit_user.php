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

// Ανάκτηση στοιχείων του χρήστη
$stmt = $conn->prepare("SELECT * FROM Xristes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "Ο χρήστης δεν βρέθηκε.";
    exit();
}

// Ενημέρωση στοιχείων χρήστη
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $onoma = $_POST['onoma'];
    $eponymo = $_POST['eponymo'];
    $email = $_POST['email'];
    $rolos = $_POST['rolos'];

    $stmt = $conn->prepare("UPDATE Xristes SET onoma = ?, eponymo = ?, email = ?, rolos = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $onoma, $eponymo, $email, $rolos, $id);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Υπήρξε πρόβλημα κατά την ενημέρωση των στοιχείων του χρήστη.";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Επεξεργασία Χρήστη</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            background-image: url("im2.jpg");
            background-size: cover;
            color: #fff;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            color: #ddd;
        }

        input, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
        }

        button {
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Επεξεργασία Χρήστη</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label for="onoma">Όνομα:</label>
            <input type="text" id="onoma" name="onoma" value="<?php echo htmlspecialchars($user['onoma']); ?>" required>

            <label for="eponymo">Επώνυμο:</label>
            <input type="text" id="eponymo" name="eponymo" value="<?php echo htmlspecialchars($user['eponymo']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="rolos">Ρόλος:</label>
            <select id="rolos" name="rolos" required>
                <option value="Tutor" <?php if ($user['rolos'] === 'Tutor') echo 'selected'; ?>>Tutor</option>
                <option value="Student" <?php if ($user['rolos'] === 'Student') echo 'selected'; ?>>Student</option>
            </select>

            <button type="submit">Αποθήκευση Αλλαγών</button>
        </form>
    </div>
</body>
</html>
