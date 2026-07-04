<?php require __DIR__ . '/../partials/header.php'; ?>

<h2>Employés</h2>

<a class="btn" href="index.php?module=employe&action=ajouter">+ Ajouter un employé</a>

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
