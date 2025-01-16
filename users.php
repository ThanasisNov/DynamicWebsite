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



// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $onoma = $_SESSION['onoma'];
    $rolos = $_SESSION['rolos'];
} else {
    // Αν ο χρήστης δεν είναι συνδεδεμένος, ανακατεύθυνση στο index.php
    header('Location: index.php');
    exit();
}

// Έλεγχος αν ο χρήστης είναι Tutor
if (!isset($_SESSION['rolos']) || $_SESSION['rolos'] !== 'Tutor') {
    echo "Μη εξουσιοδοτημένη πρόσβαση.";
    exit();
}

// Ανάκτηση χρηστών από τη βάση δεδομένων
$query = "SELECT id, onoma, eponymo, email, rolos FROM Xristes ORDER BY id ASC";
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
    <title>Διαχείριση Χρηστών</title>
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
            max-width: 1200px;
            margin: 20px auto;
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
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout a:hover {
            background-color: #d43f3f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #007bff;
            color: #fff;
        }

        .action-buttons a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
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

        .center-button {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Διαχείριση Χρηστών</h1>

        <div class="button-container">
            <a href="home.php" class="button">Αρχική Σελίδα</a>
            <a href="announcement.php" class="button">Ανακοινώσεις</a>
            <a href="communication.php" class="button">Επικοινωνία</a>
            <a href="documents.php" class="button">Έγγραφα Μαθήματος</a>
            <a href="homework.php" class="button">Εργασίες</a>
            <a href="users.php" class="button">Χρήστες</a>
            <div class="logout">
                <a href="logout.php" class="button">Αποσύνδεση</a>
            </div>
        </div>

        <div class="center-button">
            <a href="add_user.php" class="button">Προσθήκη Νέου Χρήστη</a>
        </div>
		 <p>Συνδεδεμένος χρήστης: <?php echo htmlspecialchars($onoma); ?> (<?php echo htmlspecialchars($rolos); ?>)</p> 

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Όνομα</th>
                    <th>Επώνυμο</th>
                    <th>Email</th>
                    <th>Ρόλος</th>
                    <th>Ενέργειες</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['onoma']); ?></td>
                        <td><?php echo htmlspecialchars($row['eponymo']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['rolos']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_user.php?id=<?php echo $row['id']; ?>">Επεξεργασία</a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτόν τον χρήστη;');">Διαγραφή</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
    </div>
</body>
</html>
