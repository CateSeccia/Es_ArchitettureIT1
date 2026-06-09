<?php
// Include il file di connessione creato prima
require_once 'connessione.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepariamo l'inserimento. Dato che stato e turno hanno valori di default,
        // basta inserire una riga "vuota" o specificare i campi se vuoi essere sicuro.
        $sql = "INSERT INTO partite (stato, turno) VALUES ('iniziata', 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
    } catch (\PDOException $e) {
        // Se qualcosa va storto, puoi gestire l'errore qui
        die("Errore durante l'avvio della partita: " . $e->getMessage());
    }
}

// In ogni caso, a operazione conclusa, torna alla pagina principale
header("Location: partita.php");
exit();
?>