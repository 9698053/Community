<?php
/**
 * View - Modifica Esercizio (Lista)
 * File: view/modifica.php
 */

session_start();

// Protezione: solo utenti autenticati
if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../model/db.php';

// Ottieni gli allenamenti dell'utente corrente
$allenamenti = getAllenamentiByUtente($_SESSION['utente']);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Esercizio - Gym Community</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container">
        <h1>✏️ Modifica Esercizio</h1>
        <p class="subtitle">Seleziona l'esercizio da modificare</p>
        
        <?php if (count($allenamenti) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Giorno</th>
                        <th>Esercizio</th>
                        <th>Tipologia</th>
                        <th>Serie</th>
                        <th>Ripetizioni</th>
                        <th>Peso (kg)</th>
                        <th>Riposo (sec)</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allenamenti as $esercizio): ?>
                        <?php $tipologiaClass = 'badge-' . strtolower($esercizio['tipologia']); ?>
                        <tr>
                            <td><?php echo htmlspecialchars($esercizio['giorno']); ?></td>
                            <td><strong><?php echo htmlspecialchars($esercizio['esercizio']); ?></strong></td>
                            <td>
                                <span class="badge <?php echo $tipologiaClass; ?>">
                                    <?php echo htmlspecialchars($esercizio['tipologia']); ?>
                                </span>
                            </td>
                            <td><?php echo $esercizio['serie']; ?></td>
                            <td><?php echo $esercizio['ripetizioni']; ?></td>
                            <td><?php echo $esercizio['peso']; ?> kg</td>
                            <td><?php echo $esercizio['riposo']; ?> sec</td>
                            <td>
                                <a href="edit.php?id=<?php echo $esercizio['id']; ?>" class="btn btn-warning">
                                    ✏️ Modifica
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-exercises">
                Non hai ancora inserito nessun esercizio. 
                <a href="inserisci.php" style="color: #667eea; font-weight: bold;">Inserisci il tuo primo esercizio!</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>