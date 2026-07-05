<?php
// search.php - Patient & Medical Record Search Proxy 
require_once 'db_config.php'; 

$keyword = $_GET['keyword'] ?? '';

// use prepared statement to prevent SQL Injection
//  use '?' as a placeholder. separates the command from the data.
$sql = "SELECT id, name, illness_history FROM patient_records WHERE name LIKE ?";
$stmt = $pdo->prepare($sql);

// parameter binding
$searchTerm = "%" . $keyword . "%";
$stmt->execute([$searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    foreach ($results as $row) {
        //  output encoding, prevent XSS
        // htmlspecialchars() converts characters into safe HTML entities
        $safeKeyword = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
        $safeHistory = htmlspecialchars($row['illness_history'], ENT_QUOTES, 'UTF-8');

        echo "<div>Result found for keyword: " . $safeKeyword . "<br>";
        echo "Patient: " . $safeName . " | History: " . $safeHistory . "</div><hr>";
    }
} else {
    // secure error echos
    echo "No records found for: " . htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
}
?>