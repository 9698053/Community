<?php
/**
 * View - Home Page
 * File: view/index.php
 */

session_start();
require_once __DIR__ . '/../model/db.php';

// Ottieni tutti gli allenamenti dal database
$allenamenti = getAllAllenamenti();

// Giorni della settimana in ordine
$giorniSettimana = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Community - Allenamenti Settimanali</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container">
        <h1>💪 Gym Community</h1>
        <p class="subtitle">Programma di Allenamento Settimanale della Community</p>

        <?php foreach ($giorniSettimana as $giorno): ?>
            <div class="day-section">
                <div class="day-header">📅 <?php echo strtoupper($giorno); ?></div>
                
                <?php
                // Filtra gli esercizi per il giorno corrente
                $eserciziGiorno = array_filter($allenamenti, function($ex) use ($giorno) {
                    return $ex['giorno'] === $giorno;
                });
                ?>
                
                <?php if (count($eserciziGiorno) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Esercizio</th>
                                <th>Tipologia</th>
                                <th>Serie</th>
                                <th>Ripetizioni</th>
                                <th>Peso (kg)</th>
                                <th>Riposo (sec)</th>
                                <th>Utente</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eserciziGiorno as $esercizio): ?>
                                <?php $tipologiaClass = 'badge-' . strtolower($esercizio['tipologia']); ?>
                                <tr>
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
                                    <td><?php echo htmlspecialchars($esercizio['utente']); ?></td>
                                    <td class="note-cell"><?php echo htmlspecialchars($esercizio['note']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-exercises">Nessun esercizio programmato per questo giorno</div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>