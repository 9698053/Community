<?php
/**
 * View - Edit Esercizio (Form)
 * File: view/edit.php
 */

session_start();

// Protezione: solo utenti autenticati
if (!isset($_SESSION['utente'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../controller/AllenamentoController.php';
require_once __DIR__ . '/../model/db.php';

// Verifica ID
if (!isset($_GET['id'])) {
    header('Location: modifica.php');
    exit();
}

$id = (int)$_GET['id'];
$allenamento = getAllenamentoById($id);

// Verifica che l'allenamento esista e appartenga all'utente
if (!$allenamento || $allenamento['utente'] !== $_SESSION['utente']) {
    header('Location: modifica.php');
    exit();
}

// Gestione modifica
$messaggio = '';
$messaggioTipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risultato = AllenamentoController::modifica($id);
    $messaggio = $risultato['message'];
    $messaggioTipo = $risultato['success'] ? 'successo' : 'errore';
    
    // Ricarica i dati
    if ($risultato['success']) {
        $allenamento = getAllenamentoById($id);
    }
}
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
        <p class="subtitle">Aggiorna i dati del tuo esercizio</p>
        
        <?php if (!empty($messaggio)): ?>
            <div class="messaggio <?php echo $messaggioTipo; ?>">
                <?php echo $messaggio; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="edit.php?id=<?php echo $id; ?>">
            <div class="form-group">
                <label for="giorno">Giorno della Settimana <span class="required">*</span></label>
                <select id="giorno" name="giorno" required>
                    <option value="">-- Seleziona un giorno --</option>
                    <?php
                    $giorni = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
                    foreach ($giorni as $g) {
                        $selected = ($allenamento['giorno'] === $g) ? 'selected' : '';
                        echo "<option value=\"$g\" $selected>$g</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="esercizio">Nome Esercizio <span class="required">*</span></label>
                <input type="text" id="esercizio" name="esercizio" required 
                       value="<?php echo htmlspecialchars($allenamento['esercizio']); ?>" 
                       placeholder="es. Panca piana">
            </div>
            
            <div class="form-group">
                <label for="tipologia">Tipologia Muscolare <span class="required">*</span></label>
                <select id="tipologia" name="tipologia" required>
                    <option value="">-- Seleziona tipologia --</option>
                    <?php
                    $tipologie = ['Petto', 'Schiena', 'Spalle', 'Gambe', 'Braccia', 'Addominali', 'Cardio'];
                    foreach ($tipologie as $t) {
                        $selected = ($allenamento['tipologia'] === $t) ? 'selected' : '';
                        echo "<option value=\"$t\" $selected>$t</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="serie">Serie <span class="required">*</span></label>
                    <input type="number" id="serie" name="serie" required min="1" max="20" 
                           value="<?php echo $allenamento['serie']; ?>" placeholder="es. 4">
                </div>
                
                <div class="form-group">
                    <label for="ripetizioni">Ripetizioni <span class="required">*</span></label>
                    <input type="number" id="ripetizioni" name="ripetizioni" required min="1" max="200" 
                           value="<?php echo $allenamento['ripetizioni']; ?>" placeholder="es. 10">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="peso">Peso (kg) <span class="required">*</span></label>
                    <input type="number" id="peso" name="peso" required min="0" step="0.5" 
                           value="<?php echo $allenamento['peso']; ?>" placeholder="es. 60">
                </div>
                
                <div class="form-group">
                    <label for="riposo">Riposo (secondi) <span class="required">*</span></label>
                    <input type="number" id="riposo" name="riposo" required min="0" step="15" 
                           value="<?php echo $allenamento['riposo']; ?>" placeholder="es. 90">
                </div>
            </div>
            
            <div class="form-group">
                <label for="note">Note (opzionale)</label>
                <textarea id="note" name="note" placeholder="Inserisci eventuali note o suggerimenti..."><?php echo htmlspecialchars($allenamento['note']); ?></textarea>
            </div>
            
            <div class="form-row">
                <button type="submit" class="btn btn-primary">💾 Salva Modifiche</button>
                <a href="modifica.php" class="btn" style="background-color: #6c757d; color: white; text-align: center;">
                    ❌ Annulla
                </a>
            </div>
        </form>
    </div>
</body>
</html>