<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ανάκτηση στοιχείων του συνδεδεμένου χρήστη
$onoma = $_SESSION['onoma'];
$rolos = $_SESSION['rolos'];
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Αρχική Σελίδα</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f0f0f0;
            background-image: url("im2.jpg");
            background-size: cover;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 10px;
        }

        .button {
            display: flex;
            justify-content: center;
            align-items: center;
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
            justify-content: center;
        }

        .logout a {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout a:hover {
            background-color: #d43f3f;
        }

        .content {
            text-align: center;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding: 10px 0;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Αρχική Σελίδα</h1>
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
            <p>Συνδεδεμένος χρήστης: <?php echo htmlspecialchars($onoma); ?> (<?php echo htmlspecialchars($rolos); ?>)</p> 
            <p>Καλωσορίσατε στον Εκπαιδευτικό Ιστοχώρο μας!</p>
            <ul>
                <li>Ανακοινώσεις: Όλες οι σημαντικές ενημερώσεις για το μάθημα.</li>
                <li>Επικοινωνία: Εύκολη πρόσβαση σε πληροφορίες επικοινωνίας με τον καθηγητή.</li>
                <li>Έγγραφα Μαθήματος: Περιλαμβάνει σημειώσεις και διαφάνειες διαλέξεων.</li>
                <li>Εργασίες: Εκφωνήσεις και παραδοτέα για τις εργασίες του μαθήματος.</li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; 2024 Εκπαιδευτικός Ιστοχώρος - Όλα τα δικαιώματα διατηρούνται.</p>
        </div>
    </div>
</body>
</html>
