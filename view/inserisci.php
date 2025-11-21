<?php
/**
 * View - Inserisci Esercizio
 * File: view/inserisci.php
 */

session_start();

// Protezione: solo utenti autenticati
if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../controller/AllenamentoController.php';

// Gestione inserimento
$messaggio = '';
$messaggioTipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risultato = AllenamentoController::inserisci();
    $messaggio = $risultato['message'];
    $messaggioTipo = $risultato['success'] ? 'successo' : 'errore';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci Esercizio - Gym Community</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container">
        <h1>➕ Inserisci Nuovo Esercizio</h1>
        <p class="subtitle">Aggiungi un esercizio al tuo programma settimanale</p>
        
        <?php if (!empty($messaggio)): ?>
            <div class="messaggio <?php echo $messaggioTipo; ?>">
                <?php echo $messaggio; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="inserisci.php">
            <div class="form-group">
                <label for="giorno">Giorno della Settimana <span class="required">*</span></label>
                <select id="giorno" name="giorno" required>
                    <option value="">-- Seleziona un giorno --</option>
                    <option value="Lunedì">Lunedì</option>
                    <option value="Martedì">Martedì</option>
                    <option value="Mercoledì">Mercoledì</option>
                    <option value="Giovedì">Giovedì</option>
                    <option value="Venerdì">Venerdì</option>
                    <option value="Sabato">Sabato</option>
                    <option value="Domenica">Domenica</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="esercizio">Nome Esercizio <span class="required">*</span></label>
                <input type="text" id="esercizio" name="esercizio" required placeholder="es. Panca piana">
            </div>
            
            <div class="form-group">
                <label for="tipologia">Tipologia Muscolare <span class="required">*</span></label>
                <select id="tipologia" name="tipologia" required>
                    <option value="">-- Seleziona tipologia --</option>
                    <option value="Petto">Petto</option>
                    <option value="Schiena">Schiena</option>
                    <option value="Spalle">Spalle</option>
                    <option value="Gambe">Gambe</option>
                    <option value="Braccia">Braccia</option>
                    <option value="Addominali">Addominali</option>
                    <option value="Cardio">Cardio</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="serie">Serie <span class="required">*</span></label>
                    <input type="number" id="serie" name="serie" required min="1" max="20" placeholder="es. 4">
                </div>
                
                <div class="form-group">
                    <label for="ripetizioni">Ripetizioni <span class="required">*</span></label>
                    <input type="number" id="ripetizioni" name="ripetizioni" required min="1" max="200" placeholder="es. 10">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="peso">Peso (kg) <span class="required">*</span></label>
                    <input type="number" id="peso" name="peso" required min="0" step="0.5" placeholder="es. 60">
                </div>
                
                <div class="form-group">
                    <label for="riposo">Riposo (secondi) <span class="required">*</span></label>
                    <input type="number" id="riposo" name="riposo" required min="0" step="15" placeholder="es. 90">
                </div>
            </div>
            
            <div class="form-group">
                <label for="note">Note (opzionale)</label>
                <textarea id="note" name="note" placeholder="Inserisci eventuali note o suggerimenti..."></textarea>
            </div>
            
            <button type="submit" class="btn-submit">💪 Aggiungi Esercizio</button>
        </form>
    </div>
</body>
</html>