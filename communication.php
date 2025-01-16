<?php
session_start();

// Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
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
    <title>Επικοινωνία</title>
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
            background-color: rgba(10, 9, 9, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: white;
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
            line-height: 1.6;
            color: white;
        }

        form {
            background-color: rgba(10, 9, 9, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        input[type="email"], input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Επικοινωνία</h1>

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
            <p>Επιλέξτε έναν από τους παρακάτω τρόπους για να επικοινωνήσετε με τον καθηγητή:</p>

            <h2>Web φόρμα</h2>
            <p>Συμπληρώστε την παρακάτω φόρμα:</p>
            <form action="send_email.php" method="post">
                <label for="email">Email αποστολέα:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="topic">Θέμα:</label><br>
                <input type="text" id="topic" name="topic" required><br><br>

                <label for="message">Μήνυμα:</label><br>
                <textarea id="message" name="message" rows="10" cols="50" required></textarea><br><br>

                <input type="submit" value="Αποστολή">
            </form>

            <h2>Email</h2>
            <p>Μπορείτε επίσης να στείλετε email απευθείας στο: <a href="mailto:nova.thanos@gmail.com">nova.thanos@gmail.com</a></p>
        </div>
    </div>
</body>
</html>
