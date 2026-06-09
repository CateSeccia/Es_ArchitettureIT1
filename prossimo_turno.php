<?php
// Include il file di connessione per usare $pdo
require_once 'connessione.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_partita']) && isset($_POST['turno_attuale'])) {
    
    $id_partita = intval($_POST['id_partita']);
    $turno_attuale = intval($_POST['turno_attuale']);

    try {
        if ($turno_attuale < 9) {
            // Se siamo tra il turno 1 e il turno 8, aumentiamo il turno di 1
            $sql = "UPDATE partite SET turno = turno + 1 WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id_partita]);
            
        } elseif ($turno_attuale == 9) {
            // Se siamo al turno 9 e premiamo "Termina", il turno diventa 10 e lo stato passa a 'finita'
            $sql = "UPDATE partite SET turno = 10, stato = 'finita' WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id_partita]);
        }
        
    } catch (\PDOException $e) {
        die("Errore durante l'aggiornamento del turno: " . $e->getMessage());
    }
}

// Torna alla pagina principale per vedere le modifiche nelle colonne separate
header("Location: partita.php");
exit();
?>