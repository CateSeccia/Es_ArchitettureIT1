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
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // 2. Query per recuperare tutti i dati dalla tabella
    // Ordiniamo per ID decrescente così l'ultimo IP inserito compare in alto
    $sql = "SELECT id, ip FROM utenti ORDER BY id";
    $stmt = $pdo->query($sql);
    $registri = $stmt->fetchAll();

} catch (\PDOException $e) {
    die("Errore di connessione o query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css"
>
</head>
<body>
    
<h1>Inserisci nuovo IP</h1>

<!-- Messaggi di successo o errore -->
 <?php if (isset($_SESSION['messaggio_errore'])): ?>
        <div class="allerta-errore">
            <?php 
                echo $_SESSION['messaggio_errore']; 
                unset($_SESSION['messaggio_errore']); // Cancella il messaggio così scompare al prossimo ricaricamento
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['messaggio_successo'])): ?>
        <div class="allerta-successo">
            <?php 
                echo $_SESSION['messaggio_successo']; 
                unset($_SESSION['messaggio_successo']); // Cancella il messaggio
            ?>
        </div>
    <?php endif; ?>

<form action="salva_ip.php" method="post">
	<input type="text" id="ip_address" name="ip_address" placeholder="Inserisci il tuo IP" pattern="^((25[0-5]|(2[0-4]|1\d|[1-9]|)\d)\.?\b){4}$"
    title="Inserisci un indirizzo IPv4 valido (es. 192.168.1.1)" required>
	<input type="submit">
</form>

<h2>IP Registrati nel Database</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Indirizzo IP</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($registri) > 0): ?>
                <?php foreach ($registri as $riga): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($riga['id']); ?></td>
                        <td><?php echo htmlspecialchars($riga['ip']); ?></td>
                        <td>
                            <form action="elimina_ip.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="id_da_eliminare" value="<?php echo $riga['id']; ?>">
                                <button type="submit" onclick="return confirm('Sei sicuro di voler eliminare questo IP?');" style="color: red; cursor: pointer;">
                                    Elimina
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Nessun IP registrato al momento.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>