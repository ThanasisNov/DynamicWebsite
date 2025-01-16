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

$user_id = $_SESSION['user_id'];
$onoma = $_SESSION['onoma'];
$rolos = $_SESSION['rolos'];

// Ανάκτηση εργασιών από τη βάση δεδομένων
$query = "SELECT id, stoxoi, ekfonisi, paradotea, imerominia_paradosis FROM Ergasies";
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
    <title>Εργασίες</title>
    <style>
        body, html {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #000;
            background-image: url("im2.jpg");
            background-size: cover;
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

        .homework-list {
            list-style: none;
            padding: 0;
        }

        .homework-item {
            background: rgba(0, 0, 0, 0.7);
            margin: 20px 0;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .homework-item h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #fff;
        }

        .homework-item p {
            margin: 5px 0;
            font-size: 1em;
            color: #ccc;
        }

        .homework-item a {
            color: #007bff;
            text-decoration: none;
        }

        .homework-item a:hover {
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

        .button1 {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button1:hover {
            background-color: #0056b3;
        }

        .action-buttons a {
            display: inline-block;
            margin-right: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9em;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Εργασίες</h1>

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
                <a href="add_homework.php" class="button1">Προσθήκη Νέας Εργασίας</a>
            <?php endif; ?>

            <p>Συνδεδεμένος χρήστης: <?php echo htmlspecialchars($onoma); ?> (<?php echo htmlspecialchars($rolos); ?>)</p>
            <p>Παρακάτω μπορείτε να δείτε τις διαθέσιμες εργασίες:</p>
            <ul class="homework-list">
    <?php
    $counter = 1; // Αρχικοποίηση του μετρητή για τον τίτλο
    while ($row = $results->fetch_assoc()): ?>
        <li class="homework-item">
            <h2>Εργασία <?php echo $counter++; ?></h2> 
            <p><strong>Στόχοι:</strong> <?php echo htmlspecialchars($row['stoxoi']); ?></p>
            <p><strong>Παραδοτέα:</strong> <?php echo htmlspecialchars($row['paradotea']); ?></p>
            <p><strong>Ημερομηνία Παράδοσης:</strong> <?php echo htmlspecialchars($row['imerominia_paradosis']); ?></p>
            <p><a href="<?php echo htmlspecialchars($row['ekfonisi']); ?>" download>Κατεβάστε την εκφώνηση</a></p>
            <?php if ($rolos === 'Tutor'): ?>
                <div class="action-buttons">
                    <a href="edit_homework.php?id=<?php echo $row['id']; ?>" class="button">Επεξεργασία</a>
                    <a href="delete_homework.php?id=<?php echo $row['id']; ?>" class="button delete">Διαγραφή</a>
                </div>
            <?php endif; ?>
        </li>
    <?php endwhile; ?>
</ul>

            <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
        </div>
    </div>
</body>
</html>
