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
if ($_SESSION['rolos'] != 'Tutor') {
    header("Location: documents.php");
    exit();
}

//Just in case
$upload_dir = 'uploads';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Διαχείριση φόρμας προσθήκης εγγράφου
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_path = $upload_dir . '/' . basename($_FILES['file']['name']);

    if ($_FILES['file']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            $stmt = $conn->prepare("INSERT INTO Eggrafa (titlos, perigrafi, arxeio) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $file_path);

            if ($stmt->execute()) {
                header("Location: documents.php");
                exit();
            } else {
                $error = "Υπήρξε πρόβλημα κατά την προσθήκη του εγγράφου.";
            }
        } else {
            $error = "Σφάλμα κατά τη μετακίνηση του αρχείου στον φάκελο προορισμού.";
        }
    } else {
        $error = "Σφάλμα κατά τη μεταφόρτωση του αρχείου.";
    }
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Προσθήκη Νέου Εγγράφου</title>
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
        }

        h1 {
            text-align: center;
            color: white; 
        }

        .content {
            text-align: center; 
            line-height: 1.6;
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
            color:black;
        }

        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 15px;
        }

        form input, form textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color:black;
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

        .top-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: black;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px; 
        }

        .top-link:hover {
            background-color: black;
            color:black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Προσθήκη Νέου Εγγράφου</h1>

        <div class="content">
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="add_document.php" method="post" enctype="multipart/form-data">
                <label for="title">Τίτλος:</label>
                <input type="text" name="title" id="title" required>

                <label for="description">Περιγραφή:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="file">Αρχείο:</label>
                <input type="file" name="file" id="file" required>

                <button type="submit">Προσθήκη</button>
            </form>
        </div>
    </div>
</body>
</html>
