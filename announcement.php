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
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ανάκτηση στοιχείων συνδεδεμένου χρήστη
$user_id = $_SESSION['user_id'];
$onoma = $_SESSION['onoma'];
$rolos = $_SESSION['rolos'];

// Λήψη όλων των ανακοινώσεων από τη βάση
$query = "SELECT * FROM Anakainoseis ORDER BY imerominia DESC";
$results = $conn->query($query);

if (!$results) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
 <title>Ανακοινώσεις</title>
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
    min-height: 100%; 
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-image: url("im2.jpg");
    background-size: cover;
    background-position: center; 
    background-repeat: no-repeat; 
    display: flex;
    flex-direction: column;
    justify-content: flex-start; 
}

h1 {
 text-align: center;
 color: white; 
 }

.button-container {
display: flex;
justify-content: center;
margin-bottom: 20px;
}

.button {
margin: 0 10px;
 padding: 10px 20px;
 background-color: #007bff;
color: #fff;
 border: none;
border-radius: 5px;
 text-decoration: none;
 }

.content {
 text-align: center;
 line-height: 1.6;
 color: white; 
 }

.announcement {
 border-bottom: 1px solid rgba(255, 255, 255, 0.3);
padding-bottom: 20px;
margin-bottom: 20px;
 text-align: left;
 }

.announcement h2 {
 color: white;
}

 .announcement .date {
 font-style: italic;
 color: #ddd; 
 }

.announcement a {
color: #007bff;
 text-decoration: none;
}

.announcement a:hover {
 text-decoration: underline;
 }
 .announcement a.button {
  color: white; 
  text-decoration: none;
 }

 .top-link {
display: inline-block;
 margin-top: 20px;
padding: 10px 15px;
background-color: #007bff;
color: #fff;
 text-decoration: none;
border-radius: 5px;
 font-size: 14px; 
 }

 .top-link:hover {
background-color: #0056b3;
 }

 .logout {
display: flex;
 justify-content: flex-end;
}

.logout a {
background-color: #ff4d4d; 
 }
</style>
</head>
<body>
<a id="top"></a> 
 <div class="container">
 <h1>Ανακοινώσεις</h1>

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

 <?php if ($rolos == 'Tutor'): ?>
   
 <a href="add_announcement.php" class="button">Προσθήκη Νέας Ανακοίνωσης</a>
<?php endif; ?>

            <p>Συνδεδεμένος χρήστης: <?php echo htmlspecialchars($onoma); ?> (<?php echo htmlspecialchars($rolos); ?>)</p> 

<?php while ($row = $results->fetch_assoc()): ?>
<div class="announcement">
<h2><?php echo htmlspecialchars($row['thema']); ?></h2>
 <p class="date"><?php echo htmlspecialchars($row['imerominia']); ?></p>
 <p><?php echo htmlspecialchars($row['keimeno']); ?></p>

 <?php if (!empty($row['link'])): ?>
    <p><a href="<?php echo htmlspecialchars($row['link']); ?>">Σύνδεσμος</a></p> 
 <?php endif; ?>

<?php if ($rolos == 'Tutor'): ?>

    <a href="edit_announcement.php?id=<?php echo $row['id']; ?>" class="button">[Επεξεργασία]</a>
    <a href="delete_announcement.php?id=<?php echo $row['id']; ?>" class="button">[Διαγραφή]</a>

    

<?php endif; ?>
 </div>
<?php endwhile; ?>

<a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
</div>
 </div>
</body>
</html>
