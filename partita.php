<?php
// Richiede il file di connessione per poter usare la variabile $pdo
require_once 'connessione.php';

try {
    // Recuperiamo lo storico delle partite (dalla più recente alla più vecchia)
    $sql = "SELECT id, stato, turno FROM partite ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $elenco_partite = $stmt->fetchAll();
} catch (\PDOException $e) {
    die("Errore nel recupero delle partite: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Partite</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>

    <h2>Pannello di Gioco</h2>

    <form action="avvia_partita.php" method="POST">
        <button type="submit" class="btn-avvia">Avvia Nuova Partita</button>
    </form>

    <h3>Storico Partite</h3>

    <table>
        <thead>
            <tr>
                <th>ID Partita</th>
                <th>Stato</th>
                <th>Numero Turno</th>
                <th>Azione</th> 
            </tr>
        </thead>
        <tbody>
            <?php if (count($elenco_partite) > 0): ?>
                <?php foreach ($elenco_partite as $partita): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($partita['id']); ?></td>
                        
                        <td class="<?php echo ($partita['stato'] == 'iniziata') ? 'stato-iniziata' : 'stato-finita'; ?>">
                            <?php echo htmlspecialchars($partita['stato']); ?>
                        </td>
                        
                        <td>
                            Turno <?php echo htmlspecialchars($partita['turno']); ?> / 10
                        </td>

                        <td>
                            <?php if ($partita['stato'] == 'iniziata'): ?>
                                <form action="prossimo_turno.php" method="POST" style="margin:0;">
                                    <input type="hidden" name="id_partita" value="<?php echo $partita['id']; ?>">
                                    <input type="hidden" name="turno_attuale" value="<?php echo $partita['turno']; ?>">
                                    
                                    <?php if ($partita['turno'] == 9): ?>
                                        <button type="submit" >
                                            Termina
                                        </button>
                                    <?php else: ?>
                                        <button type="submit">
                                            Prossimo
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nessuna partita nel database. Clicca su "Avvia Nuova Partita".</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>