<?php
/**
 * Navbar - Barra di navigazione
 * File: includes/navbar.php
 */

// Determina la pagina corrente
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav>
    <ul>
        <li>
            <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                🏠 Home
            </a>
        </li>
        
        <?php if (isset($_SESSION['utente'])): ?>
            <li>
                <a href="inserisci.php" class="<?php echo $currentPage === 'inserisci.php' ? 'active' : ''; ?>">
                    ➕ Inserisci Esercizio
                </a>
            </li>
            <li>
                <a href="elimina.php" class="<?php echo $currentPage === 'elimina.php' ? 'active' : ''; ?>">
                    🗑️ Elimina Esercizio
                </a>
            </li>
            <li>
                <a href="modifica.php" class="<?php echo $currentPage === 'modifica.php' ? 'active' : ''; ?>">
                    ✏️ Modifica Esercizio
                </a>
            </li>
            <li>
                <a href="logout.php">
                    🚪 Logout (<?php echo htmlspecialchars($_SESSION['utente']); ?>)
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="login.php" class="<?php echo $currentPage === 'login.php' ? 'active' : ''; ?>">
                    🔐 Login
                </a>
            </li>
            <li>
                <a href="registrazione.php" class="<?php echo $currentPage === 'registrazione.php' ? 'active' : ''; ?>">
                    📝 Registrazione
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>