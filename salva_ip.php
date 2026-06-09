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
    // 2. Connessione al database
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}

// 3. Verifica che il form sia stato inviato tramite POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera l'IP dal form e rimuove spazi vuoti
    $ip_da_salvare = trim($_POST['ip_address']);

    // (Opzionale) Validazione: controlla se è un IP valido
    if (filter_var($ip_da_salvare, FILTER_VALIDATE_IP)) {
        
        // 4. Preparazione della query SQL (Previene SQL Injection)
        $sql = "INSERT INTO utenti (ip) VALUES (:ip)";
        $stmt = $pdo->prepare($sql);
        
        // Esecuzione della query passando il valore
        if ($stmt->execute([':ip' => $ip_da_salvare])) {
            //echo "IP salvato con successo!";
            // Puoi anche reindirizzare l'utente dopo il successo:
            header("Location: index.php");
        } else {
            echo "Si è verificato un errore durante il salvataggio.";
        }
        
    } else {
        echo "L'indirizzo IP inserito non è valido.";
    }
} else {
    echo "Accesso non consentito.";
}
?>