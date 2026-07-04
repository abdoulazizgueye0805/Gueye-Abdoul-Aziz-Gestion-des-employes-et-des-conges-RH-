<?php require __DIR__ . '/../partials/header.php'; ?>

<h2>Départements</h2>

<a class="btn" href="index.php?module=departement&action=ajouter">+ Ajouter un département</a>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($departements as $departement): ?>
        <tr>
            <td><?= htmlspecialchars((string) $departement->getId()) ?></td>
            <td><?= htmlspecialchars($departement->getNom()) ?></td>
            <td class="actions">
                <a href="index.php?module=departement&action=modifier&id=<?= $departement->getId() ?>">Modifier</a>
                <a href="index.php?module=departement&action=supprimer&id=<?= $departement->getId() ?>"
                   onclick="return confirm('Supprimer ce département ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($departements)): ?>
        <tr>
            <td colspan="3">Aucun département enregistré pour le moment.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
