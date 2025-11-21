<?php
/**
 * Controller - Gestione logica applicazione
 * File: controller/AllenamentoController.php
 */

require_once __DIR__ . '/../model/db.php';

class AllenamentoController {
    
    /**
     * Gestisce l'inserimento di un nuovo allenamento
     * @return array Array con 'success' (bool) e 'message' (string)
     */
    public static function inserisci() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => ''];
        }
        
        // Validazione dati
        $errori = self::validaDatiAllenamento($_POST);
        
        if (!empty($errori)) {
            return ['success' => false, 'message' => implode('<br>', $errori)];
        }
        
        // Prepara i dati
        $dati = [
            'utente' => $_SESSION['utente'],
            'giorno' => $_POST['giorno'],
            'esercizio' => trim($_POST['esercizio']),
            'tipologia' => $_POST['tipologia'],
            'serie' => (int)$_POST['serie'],
            'ripetizioni' => (int)$_POST['ripetizioni'],
            'peso' => (float)$_POST['peso'],
            'riposo' => (int)$_POST['riposo'],
            'note' => trim($_POST['note'])
        ];
        
        // Inserisci nel database
        if (inserisciAllenamento($dati)) {
            return ['success' => true, 'message' => '✅ Esercizio aggiunto con successo!'];
        } else {
            return ['success' => false, 'message' => '❌ Errore durante l\'inserimento. Riprova.'];
        }
    }
    
    /**
     * Gestisce l'eliminazione di un allenamento
     * @param int $id ID dell'allenamento da eliminare
     * @return array Array con 'success' (bool) e 'message' (string)
     */
    public static function elimina($id) {
        if (!isset($_SESSION['utente'])) {
            return ['success' => false, 'message' => '❌ Devi essere autenticato.'];
        }
        
        $id = (int)$id;
        
        if ($id <= 0) {
            return ['success' => false, 'message' => '❌ ID non valido.'];
        }
        
        // Verifica che l'allenamento esista e appartenga all'utente
        $allenamento = getAllenamentoById($id);
        
        if (!$allenamento) {
            return ['success' => false, 'message' => '❌ Allenamento non trovato.'];
        }
        
        if ($allenamento['utente'] !== $_SESSION['utente']) {
            return ['success' => false, 'message' => '❌ Non puoi eliminare questo allenamento.'];
        }
        
        // Elimina
        if (eliminaAllenamento($id, $_SESSION['utente'])) {
            return ['success' => true, 'message' => '✅ Esercizio eliminato con successo!'];
        } else {
            return ['success' => false, 'message' => '❌ Errore durante l\'eliminazione.'];
        }
    }
    
    /**
     * Gestisce la modifica di un allenamento
     * @param int $id ID dell'allenamento da modificare
     * @return array Array con 'success' (bool) e 'message' (string)
     */
    public static function modifica($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => ''];
        }
        
        if (!isset($_SESSION['utente'])) {
            return ['success' => false, 'message' => '❌ Devi essere autenticato.'];
        }
        
        $id = (int)$id;
        
        // Validazione dati
        $errori = self::validaDatiAllenamento($_POST);
        
        if (!empty($errori)) {
            return ['success' => false, 'message' => implode('<br>', $errori)];
        }
        
        // Prepara i dati
        $dati = [
            'giorno' => $_POST['giorno'],
            'esercizio' => trim($_POST['esercizio']),
            'tipologia' => $_POST['tipologia'],
            'serie' => (int)$_POST['serie'],
            'ripetizioni' => (int)$_POST['ripetizioni'],
            'peso' => (float)$_POST['peso'],
            'riposo' => (int)$_POST['riposo'],
            'note' => trim($_POST['note'])
        ];
        
        // Modifica nel database
        if (modificaAllenamento($id, $dati, $_SESSION['utente'])) {
            return ['success' => true, 'message' => '✅ Esercizio modificato con successo!'];
        } else {
            return ['success' => false, 'message' => '❌ Errore durante la modifica o non hai i permessi.'];
        }
    }
    
    /**
     * Valida i dati di un allenamento
     * @param array $dati Dati da validare
     * @return array Array di errori (vuoto se validazione OK)
     */
    private static function validaDatiAllenamento($dati) {
        $errori = [];
        
        // Validazione giorno
        $giorniValidi = ['Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'];
        if (empty($dati['giorno']) || !in_array($dati['giorno'], $giorniValidi)) {
            $errori[] = 'Giorno non valido.';
        }
        
        // Validazione esercizio
        if (empty(trim($dati['esercizio']))) {
            $errori[] = 'Il nome dell\'esercizio è obbligatorio.';
        }
        
        // Validazione tipologia
        $tipologieValide = ['Petto', 'Schiena', 'Spalle', 'Gambe', 'Braccia', 'Addominali', 'Cardio'];
        if (empty($dati['tipologia']) || !in_array($dati['tipologia'], $tipologieValide)) {
            $errori[] = 'Tipologia non valida.';
        }
        
        // Validazione serie
        if (!isset($dati['serie']) || $dati['serie'] < 1 || $dati['serie'] > 20) {
            $errori[] = 'Le serie devono essere tra 1 e 20.';
        }
        
        // Validazione ripetizioni
        if (!isset($dati['ripetizioni']) || $dati['ripetizioni'] < 1 || $dati['ripetizioni'] > 200) {
            $errori[] = 'Le ripetizioni devono essere tra 1 e 200.';
        }
        
        // Validazione peso
        if (!isset($dati['peso']) || $dati['peso'] < 0 || $dati['peso'] > 500) {
            $errori[] = 'Il peso deve essere tra 0 e 500 kg.';
        }
        
        // Validazione riposo
        if (!isset($dati['riposo']) || $dati['riposo'] < 0 || $dati['riposo'] > 600) {
            $errori[] = 'Il riposo deve essere tra 0 e 600 secondi.';
        }
        
        return $errori;
    }
    
    /**
     * Gestisce il login
     * @return array Array con 'success' (bool) e 'message' (string)
     */
    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => ''];
        }
        
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => '❌ Username e password sono obbligatori.'];
        }
        
        // Verifica credenziali
        $conn = getConnection();
        $sql = "SELECT * FROM utenti WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $utente = $result->fetch_assoc();
            
            // Verifica password
            if ($password === $utente['password']) {                  //(password_verify($password, $utente['password'])
                $_SESSION['utente'] = $utente['username'];
                $stmt->close();
                $conn->close();
                return ['success' => true, 'message' => '✅ Login effettuato con successo!'];
            }
        }
        
        $stmt->close();
        $conn->close();
        return ['success' => false, 'message' => '❌ Username o password errati.'];
    }
    
    /**
     * Gestisce la registrazione
     * @return array Array con 'success' (bool) e 'message' (string)
     */
    public static function registrazione() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => ''];
        }
        
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confermaPassword = $_POST['conferma_password'];
        
        // Validazione
        $errori = [];
        
        if (empty($username) || strlen($username) < 3) {
            $errori[] = 'Username deve essere di almeno 3 caratteri.';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errori[] = 'Email non valida.';
        }
        
        if (strlen($password) < 6) {
            $errori[] = 'La password deve essere di almeno 6 caratteri.';
        }
        
        if ($password !== $confermaPassword) {
            $errori[] = 'Le password non coincidono.';
        }
        
        if (!empty($errori)) {
            return ['success' => false, 'message' => implode('<br>', $errori)];
        }
        
        // Verifica se username esiste già
        $conn = getConnection();
        $sql = "SELECT id FROM utenti WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => '❌ Username o email già esistenti.'];
        }
        
        $stmt->close();
        
        // Inserisci nuovo utente
        $passdb= $password;                 //$passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utenti (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $passdb);
        
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return ['success' => true, 'message' => '✅ Registrazione completata! Ora puoi effettuare il login.'];
        } else {
            $stmt->close();
            $conn->close();
            return ['success' => false, 'message' => '❌ Errore durante la registrazione.'];
        }
    }
}
?>