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

// Έλεγχος για είσοδο χρήστη
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ανάκτηση στοιχείων συνδεδεμένου χρήστη
$user_id = $_SESSION['user_id'];
$onoma = $_SESSION['onoma'];
$rolos = $_SESSION['rolos'];

// Ανάκτηση εγγράφων από τη βάση δεδομένων
$query = "SELECT id, titlos, perigrafi, arxeio FROM Eggrafa";
$results = $conn->query($query);

if (!$results) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Έγγραφα Μαθήματος</title>
    <style>
        body, html {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #000;
            color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #111 url("im2.jpg") no-repeat center center / cover;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        .button-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .logout {
            display: flex;
            justify-content: flex-end;
        }

        .logout a {
            background-color: #ff4d4d;
        }

        .content {
            text-align: center;
            margin-top: 20px;
        }

        .document-list {
            list-style: none;
            padding: 0;
        }

        .document-item {
            background: rgba(0, 0, 0, 0.7); 
            margin: 20px 0;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .document-title {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #fff; 
        }

        .document-description {
            margin-bottom: 10px;
            font-size: 0.9em;
            color: #ddd;
        }

        .document-link {
            color: #007bff;
            text-decoration: none;
        }

        .document-link:hover {
            text-decoration: underline;
        }

        .top-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .top-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Έγγραφα Μαθήματος</h1>

        <div class="button-container">
    <a href="home.php" class="button">Αρχική Σελίδα</a>
    <a href="announcement.php" class="button">Ανακοινώσεις</a>
    <a href="communication.php" class="button">Επικοινωνία</a>
    <a href="documents.php" class="button">Έγγραφα Μαθήματος</a>
    <a href="homework.php" class="button">Εργασίες</a>
    <?php if (isset($rolos) && $rolos === 'Tutor'): ?>
        <a href="users.php" class="button">Χρήστες</a>
    <?php endif; ?>
    <div class="logout">
        <a href="logout.php" class="button">Αποσύνδεση</a>
    </div>
</div>


        <div class="content">
            <?php if ($rolos === 'Tutor'): ?>
                <a href="add_document.php" class="button">Προσθήκη Νέου Εγγράφου</a>
            <?php endif; ?>
            <p>Συνδεδεμένος χρήστης: <?php echo htmlspecialchars($onoma); ?> (<?php echo htmlspecialchars($rolos); ?>)</p>
            <p>Κατεβάστε τα έγγραφα του μαθήματος:</p>
            <ul class="document-list">
                <?php while ($row = $results->fetch_assoc()): ?>
                    <li class="document-item">
                        <h2 class="document-title">Τίτλος: <?php echo htmlspecialchars($row['titlos']); ?></h2>
                        <p class="document-description">Περιγραφή: <?php echo htmlspecialchars($row['perigrafi']); ?></p>
                        <p><a href="<?php echo htmlspecialchars($row['arxeio']); ?>" download class="document-link">Κατεβάστε το έγγραφο</a></p>
                        <?php if ($rolos === 'Tutor'): ?>
                            <a href="edit_document.php?id=<?php echo $row['id']; ?>" class="button">[Επεξεργασία]</a>
                            <a href="delete_document.php?id=<?php echo $row['id']; ?>" class="button">[Διαγραφή]</a>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
            <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
        </div>
    </div>
</body>
</html>
