<?php require __DIR__ . '/../partials/header.php'; ?>

<h2>Employés</h2>

<a class="btn" href="index.php?module=employe&action=ajouter">+ Ajouter un employé</a>

<form method="get" action="index.php" style="display:flex; gap:12px; align-items:end; max-width:none; margin-top:16px;">
    <input type="hidden" name="module" value="employe">
    <input type="hidden" name="action" value="liste">

    <div style="flex:1;">
        <label for="q">Rechercher (nom, poste ou email)</label>
        <input type="text" id="q" name="q" value="<?= htmlspecialchars($termeRecherche) ?>"
               placeholder="Ex : Diop, Comptable...">
    </div>

    <button class="btn" type="submit" style="margin-bottom:16px;">Rechercher</button>
    <?php if ($termeRecherche !== ''): ?>
    <a class="btn btn-danger" style="margin-bottom:16px;" href="index.php?module=employe&action=liste">Effacer</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Poste</th>
            <th>Email</th>
            <th>Département</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($employes as $employe): ?>
        <tr>
            <td><?= htmlspecialchars((string) $employe->getId()) ?></td>
            <td><?= htmlspecialchars($employe->getNom()) ?></td>
            <td><?= htmlspecialchars($employe->getPoste()) ?></td>
            <td><?= htmlspecialchars($employe->getEmail()) ?></td>
            <td><?= htmlspecialchars($departementsParId[$employe->getDepartementId()] ?? '—') ?></td>
            <td class="actions">
                <a href="index.php?module=employe&action=modifier&id=<?= $employe->getId() ?>">Modifier</a>
                <a href="index.php?module=employe&action=supprimer&id=<?= $employe->getId() ?>"
                   onclick="return confirm('Supprimer cet employé ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($employes)): ?>
        <tr>
            <td colspan="6">Aucun employé enregistré pour le moment.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
