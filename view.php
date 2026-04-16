<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h1 class="mb-4 text-center">Gestionale Biblioteca</h1>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Inserisci Nuovo Libro</h5>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_libro">
                        <div class="mb-3">
                            <label class="form-label">Titolo</label>
                            <input type="text" name="titolo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Autore (Menu a tendina)</label>
                            <select name="id_autore" class="form-select" required>
                                <option value="">Scegli autore...</option>
                                <?php foreach($autori as $a): ?>
                                    <option value="<?= $a['id_autore'] ?>"><?= $a['nome'] . " " . $a['cognome'] ?></option>
                                    <?php index_autore; endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col"><input type="number" name="anno" class="form-control" placeholder="Anno"></div>
                            <div class="col"><input type="text" name="isbn" class="form-control" placeholder="ISBN"></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100">Aggiungi Libro</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Registra Nuovo Prestito</h5>
                    <form method="POST">
                        <input type="hidden" name="action" value="add_prestito">
                        <div class="mb-3">
                            <label class="form-label">Libro (Menu a tendina)</label>
                            <select name="id_libro" class="form-select" required>
                                <option value="">Scegli libro...</option>
                                <?php foreach($libri as $l): ?>
                                    <option value="<?= $l['id_libro'] ?>"><?= $l['titolo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Utente (Menu a tendina)</label>
                            <select name="id_utente" class="form-select" required>
                                <option value="">Scegli utente...</option>
                                <?php foreach($utenti as $u): ?>
                                    <option value="<?= $u['id_utente'] ?>"><?= $u['nome'] . " " . $u['cognome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registra Prestito</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Visualizza Prestiti Utente</h5>
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-auto">
                            <select name="id_utente_filtro" class="form-select" required>
                                <option value="">Seleziona utente...</option>
                                <?php foreach($utenti as $u): ?>
                                    <option value="<?= $u['id_utente'] ?>" <?= ($utente_selezionato == $u['id_utente']) ? 'selected' : '' ?>>
                                        <?= $u['nome'] . " " . $u['cognome'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-secondary">Filtra</button>
                        </div>
                    </form>

                    <?php if ($utente_selezionato): ?>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Data Inizio</th>
                                <th>Stato</th>
                                <th>Azione</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($prestiti_utente as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['titolo']) ?></td>
                                    <td><?= $p['data_inizio'] ?></td>
                                    <td>
                                    <span class="badge <?= $p['restituito'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $p['restituito'] ? 'Restituito' : 'In prestito' ?>
                                    </span>
                                    </td>
                                    <td>
                                        <?php if(!$p['restituito']): ?>
                                            <a href="index.php?restituisci=<?= $p['id_prestito'] ?>&u_ref=<?= $utente_selezionato ?>"
                                               class="btn btn-sm btn-outline-danger">Restituisci</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>