<?php

session_start(); // 0. ATTIVA LE SESSIONI

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

        // Contiamo quanti IP uguali a quello inserito esistono già
        $sql_check = "SELECT COUNT(*) FROM utenti WHERE ip = :ip";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([':ip' => $ip_da_salvare]);
        $numero_doppioni = $stmt_check->fetchColumn();

        if ($numero_doppioni > 0) {
            $_SESSION['messaggio_errore'] = "Errore: Questo indirizzo IP è già stato registrato!";
            header("Location: index.php");
            exit();
        }
        
        // 4. Preparazione della query SQL (Previene SQL Injection)
        $sql = "INSERT INTO utenti (ip) VALUES (:ip)";
        $stmt = $pdo->prepare($sql);
        
        // Esecuzione della query passando il valore
        if ($stmt->execute([':ip' => $ip_da_salvare])) {
            // Opzionale: puoi anche salvare un messaggio di successo!
            $_SESSION['messaggio_successo'] = "IP registrato con successo!";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['messaggio_errore'] = "Si è verificato un errore durante il salvataggio.";
            header("Location: index.php");
            exit();
        }
        
    } else {
        $_SESSION['messaggio_errore'] = "L'indirizzo IP inserito non è valido.";
        header("Location: index.php");
        exit();
    }
} else {
    echo "Accesso non consentito.";
}
?>