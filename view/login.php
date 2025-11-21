<?php
/**
 * View - Login
 * File: view/login.php
 */

session_start();

// Se già autenticato, redirect alla home
if (isset($_SESSION['utente'])) {
    header('Location: index.php');
    exit();
}

require_once __DIR__ . '/../controller/AllenamentoController.php';

// Gestione login
$messaggio = '';
$messaggioTipo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $risultato = AllenamentoController::login();
    
    if ($risultato['success']) {
        header('Location: index.php');
        exit();
    } else {
        $messaggio = $risultato['message'];
        $messaggioTipo = 'errore';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gym Community</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container login-container">
        <h1>🔐 Login</h1>
        <p class="subtitle">Accedi al tuo account per gestire i tuoi allenamenti</p>
        
        <?php if (!empty($messaggio)): ?>
            <div class="messaggio <?php echo $messaggioTipo; ?>">
                <?php echo $messaggio; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="username" name="username" required 
                       placeholder="Inserisci il tuo username" autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required 
                       placeholder="Inserisci la tua password">
            </div>
            
            <button type="submit" class="btn-submit">🔓 Accedi</button>
        </form>
        
        <div class="login-links">
            <p>Non hai un account? <a href="registrazione.php">Registrati qui</a></p>
            <p style="margin-top: 20px; color: #999; font-size: 14px;">
                <strong>Utenti di test:</strong><br>
                Username: mario | luca| giulia<br>
                Password: pass123
            </p>
        </div>
    </div>
</body>
</html>