<?php
// Database credentials
$host = "192.168.0.7";
$dbname = "your_database_name"; // Specify your database name
$username = "dvwa";
$password = "password";

try {
    // Create a new PDO instance
    $pdo = new PDO("sqlsrv:Server=$host;Database=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT first_name, password FROM users");
    $stmt->execute();
    
    // Fetch results and display them
    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo htmlspecialchars($record["first_name"]) . ", " . htmlspecialchars($record["password"]) . "<br />";
    }
} catch (PDOException $e) {
    // Display error message (do not expose sensitive info in a production environment)
    echo "Error: " . htmlspecialchars($e->getMessage());
}
?>
