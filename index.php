<?php
session_start(); 

// Αν ο χρήστης είναι ήδη συνδεδεμένος, ανακατεύθυνση στο home.php
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

$error_message = null; // Αρχικοποίηση σε null

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Σύνδεση με τη βάση δεδομένων χρησιμοποιώντας τον profile χρήστη
    $host = "localhost";
    $dbname = "student4198partB";
    $username = "profileUSER";
    $db_password = "Testing1001@";

    $conn = new mysqli($host, $username, $db_password, $dbname);

    // Έλεγχος σύνδεσης
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query με prepared statements
    $stmt = $conn->prepare('SELECT * FROM Xristes WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ελέγξτε αν ο χρήστης βρέθηκε
    if ($password != null) {
        if ($row = $result->fetch_assoc()) {
            // Επαλήθευση του password
            if ($password == $row['password']) {
                // Ο χρήστης βρέθηκε και ο κωδικός είναι σωστός 
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['onoma'] = $row['onoma'];
                $_SESSION['rolos'] = $row['rolos'];

     
                header('Location: home.php');
                exit();
            } else {
                // Λάθος κωδικός
                $error_message = "Λάθος email ή password.";
            }
        } else {
            // Ο χρήστης δεν βρέθηκε
            $error_message = "Λάθος email ή password.";
        }
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
  <meta charset="UTF-8">
  <title>Πιστοποίηση</title>
  <style>
    body {
      font-family: sans-serif;
      background-color: #f0f0f0;
      background-image: url("im2.jpg"); 
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 400px;
      width: 100%;
    }
    .error {
      color: red;
      margin-bottom: 15px;
    }
    input[type="email"],
    input[type="password"],
    input[type="submit"] {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }
    input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }
    input[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Πιστοποίηση</h1>

    <!-- Εμφάνιση μηνύματος λάθους μόνο αν υπάρχει -->
    <?php if (!empty($error_message)): ?>
      <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="index.php" method="post">
      <label for="email">Email:</label><br>
      <input type="email" id="email" name="email" required><br><br>
      <label for="password">Password:</label><br>
      <input type="password" id="password" name="password" required><br><br>
      <input type="submit" value="Σύνδεση">
    </form>
  </div>
</body>
</html>
