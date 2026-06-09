<?php
// 1. Dati di configurazione del database
$host = 'localhost';
$db   = 'gioco';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Errore di connessione: " . $e->getMessage());
}

// 2. Verifica che la richiesta sia arrivata tramite POST e che l'ID esista
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_da_eliminare'])) {
    
    // Convertiamo l'ID in un numero intero per sicurezza
    $id = intval($_POST['id_da_eliminare']);

    // 3. Prepariamo la query di eliminazione
    $sql = "DELETE FROM utenti WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    // Eseguiamo il comando
    $stmt->execute([':id' => $id]);
}

// 4. Ritorna automaticamente alla pagina principale
header("Location: index.php");
exit();
?>