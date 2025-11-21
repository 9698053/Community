<?php
/**
 * View - Registrazione
 * File: view/registrazione.php
 */

session_start();

// Se già autenticato, redirect alla home
if (isset($_SESSION['utente'])) {
    header('Location: index.php');
    exit();
}

require_once __DIR__ . '/../controller/AllenamentoController.php';

// Gestione registrazione
$messaggio = '';
$messaggioTipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risultato = AllenamentoController::registrazione();
    $messaggio = $risultato['message'];
    $messaggioTipo = $risultato['success'] ? 'successo' : 'errore';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione - Gym Community</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container login-container">
        <h1>📝 Registrazione</h1>
        <p class="subtitle">Crea il tuo account per iniziare a gestire i tuoi allenamenti</p>
        
        <?php if (!empty($messaggio)): ?>
            <div class="messaggio <?php echo $messaggioTipo; ?>">
                <?php echo $messaggio; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="registrazione.php">
            <div class="form-group">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="username" name="username" required 
                       placeholder="Scegli un username (min. 3 caratteri)" 
                       minlength="3" autofocus>
            </div>
            
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required 
                       placeholder="La tua email">
            </div>
            
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required 
                       placeholder="Scegli una password (min. 6 caratteri)" 
                       minlength="6">
            </div>
            
            <div class="form-group">
                <label for="conferma_password">Conferma Password <span class="required">*</span></label>
                <input type="password" id="conferma_password" name="conferma_password" required 
                       placeholder="Ripeti la password" 
                       minlength="6">
            </div>
            
            <button type="submit" class="btn-submit">✅ Registrati</button>
        </form>
        
        <div class="login-links">
            <p>Hai già un account? <a href="login.php">Accedi qui</a></p>
        </div>
    </div>
</body>
</html>