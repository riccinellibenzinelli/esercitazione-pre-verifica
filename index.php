<?php
require_once 'db.php';

// --- LOGICA DI AZIONE ---

// 1. Inserimento nuovo libro
if (isset($_POST['action']) && $_POST['action'] == 'add_libro') {
    $stmt = $pdo->prepare("INSERT INTO libri (titolo, anno_pubblicazione, isbn, id_autore) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['titolo'], $_POST['anno'], $_POST['isbn'], $_POST['id_autore']]);
    header("Location: index.php?msg=Libro aggiunto");
    exit;
}

// 2. Inserimento nuovo prestito
if (isset($_POST['action']) && $_POST['action'] == 'add_prestito') {
    $data_inizio = date('Y-m-d');
    $data_fine = date('Y-m-d', strtotime('+15 days'));
    $stmt = $pdo->prepare("INSERT INTO prestiti (id_libro, id_utente, data_inizio, data_fine_prevista, restituito) VALUES (?, ?, ?, ?, 0)");
    $stmt->execute([$_POST['id_libro'], $_POST['id_utente'], $data_inizio, $data_fine]);
    header("Location: index.php?msg=Prestito registrato");
    exit;
}

// 3. Registrazione restituzione
if (isset($_GET['restituisci'])) {
    $stmt = $pdo->prepare("UPDATE prestiti SET restituito = 1 WHERE id_prestito = ?");
    $stmt->execute([$_GET['restituisci']]);
    header("Location: index.php?id_utente_filtro=" . $_GET['u_ref'] . "&msg=Libro restituito");
    exit;
}

// --- RECUPERO DATI PER LE VIEW ---

$autori = $pdo->query("SELECT id_autore, nome, cognome FROM autori")->fetchAll();
$libri = $pdo->query("SELECT id_libro, titolo FROM libri")->fetchAll();
$utenti = $pdo->query("SELECT id_utente, nome, cognome FROM utenti")->fetchAll();

// Elenco prestiti filtrati per utente
$prestiti_utente = [];
$utente_selezionato = $_GET['id_utente_filtro'] ?? null;
if ($utente_selezionato) {
    $stmt = $pdo->prepare("
        SELECT p.id_prestito, l.titolo, p.data_inizio, p.restituito 
        FROM prestiti p 
        JOIN libri l ON p.id_libro = l.id_libro 
        WHERE p.id_utente = ?
    ");
    $stmt->execute([$utente_selezionato]);
    $prestiti_utente = $stmt->fetchAll();
}

include 'view.php'; // Carica la parte grafica
?>