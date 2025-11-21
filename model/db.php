<?php
require_once __DIR__ . '/config.php';

function getConnection() {
    // Crea la connessione
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Verifica la connessione
    if ($conn->connect_error) {
        die("Errore di connessione al database: " . $conn->connect_error);
    }
    
    // Imposta il charset
    //$conn->set_charset(DB_CHARSET);
    
    return $conn;
}

function getAllAllenamenti() {
    $conn = getConnection();
    $allenamenti = [];
    
    $sql = "SELECT * FROM allenamenti ORDER BY 
            FIELD(giorno, 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'), 
            id ASC";
    
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $allenamenti[] = $row;
        }
    }
    
    $conn->close();
    return $allenamenti;
}

/**
 * Ottiene gli allenamenti di un utente specifico
 * @param string $utente Nome utente
 * @return array Array di allenamenti dell'utente
 */
function getAllenamentiByUtente($utente) {
    $conn = getConnection();
    $allenamenti = [];
    
    $sql = "SELECT * FROM allenamenti WHERE utente = ? ORDER BY 
            FIELD(giorno, 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato', 'Domenica'), 
            id ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $utente);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $allenamenti[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();    return $allenamenti;
}

/**
 * Inserisce un nuovo allenamento nel database
 * @param array $dati Array associativo con i dati dell'allenamento
 * @return bool True se inserimento riuscito, False altrimenti
 */
function inserisciAllenamento($dati) {
    $conn = getConnection();
    
    $sql = "INSERT INTO allenamenti (utente, giorno, esercizio, tipologia, serie, ripetizioni, peso, riposo, note) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param(
        "ssssiidis",
        $dati['utente'],
        $dati['giorno'],
        $dati['esercizio'],
        $dati['tipologia'],
        $dati['serie'],
        $dati['ripetizioni'],
        $dati['peso'],
        $dati['riposo'],
        $dati['note']
    );
    
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();    
    return $success;
}

/**
 * Elimina un allenamento dal database
 * @param int $id ID dell'allenamento da eliminare
 * @param string $utente Username dell'utente (per sicurezza)
 * @return bool True se eliminazione riuscita, False altrimenti
 */
function eliminaAllenamento($id, $utente) {
    $conn = getConnection();
    
    // Elimina solo se l'allenamento appartiene all'utente
    $sql = "DELETE FROM allenamenti WHERE id = ? AND utente = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $utente);
    
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();    
    return $success;
}

/**
 * Ottiene un singolo allenamento per ID
 * @param int $id ID dell'allenamento
 * @return array|null Dati dell'allenamento o null se non trovato
 */
function getAllenamentoById($id) {
    $conn = getConnection();
    
    $sql = "SELECT * FROM allenamenti WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $allenamento = null;
    if ($result && $result->num_rows > 0) {
        $allenamento = $result->fetch_assoc();
    }
    
    $stmt->close();
    $conn->close();    
    return $allenamento;
}

/**
 * Modifica un allenamento esistente
 * @param int $id ID dell'allenamento da modificare
 * @param array $dati Array associativo con i nuovi dati
 * @param string $utente Username dell'utente (per sicurezza)
 * @return bool True se modifica riuscita, False altrimenti
 */
function modificaAllenamento($id, $dati, $utente) {
    $conn = getConnection();
    
    // Modifica solo se l'allenamento appartiene all'utente
    $sql = "UPDATE allenamenti 
            SET giorno = ?, esercizio = ?, tipologia = ?, serie = ?, ripetizioni = ?, peso = ?, riposo = ?, note = ?
            WHERE id = ? AND utente = ?";
    
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param(
        "sssiidisis",
        $dati['giorno'],
        $dati['esercizio'],
        $dati['tipologia'],
        $dati['serie'],
        $dati['ripetizioni'],
        $dati['peso'],
        $dati['riposo'],
        $dati['note'],
        $id,
        $utente
    );
    
    $success = $stmt->execute();
    
    $stmt->close();
    $conn->close();    
    return $success;
}
?>