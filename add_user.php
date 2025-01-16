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

// Διαχείριση φόρμας προσθήκης χρήστη
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $onoma = $_POST['onoma'];
    $eponymo = $_POST['eponymo'];
    $email = $_POST['email'];
    $rolos = $_POST['rolos'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Xristes (onoma, eponymo, email, rolos, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $onoma, $eponymo, $email, $rolos, $password);

    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    } else {
        $error = "Υπήρξε πρόβλημα κατά την προσθήκη του χρήστη.";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Προσθήκη Χρήστη</title>
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
        <h1>Προσθήκη Νέου Χρήστη</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label for="onoma">Όνομα:</label>
            <input type="text" id="onoma" name="onoma" required>

            <label for="eponymo">Επώνυμο:</label>
            <input type="text" id="eponymo" name="eponymo" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="rolos">Ρόλος:</label>
            <select id="rolos" name="rolos" required>
                <option value="Tutor">Tutor</option>
                <option value="Student">Student</option>
            </select>

            <label for="password">Κωδικός:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Προσθήκη Χρήστη</button>
        </form>
    </div>
</body>
</html>
