<?php
// Cette vue affiche le tableau de tous les departements.
// $departements est fourni par le routeur (public/index.php) avant ce require.
/** @var Departement[] $departements */

require __DIR__ . '/../partials/header.php';
?>

<h2>Départements</h2>

<!-- Lien vers le formulaire d'ajout -->
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
            <!-- htmlspecialchars() protege contre les failles XSS en echappant les caracteres HTML -->
            <td><?= htmlspecialchars((string) $departement->getId()) ?></td>
            <td><?= htmlspecialchars($departement->getNom()) ?></td>
            <td class="actions">
                <a href="index.php?module=departement&action=modifier&id=<?= $departement->getId() ?>">Modifier</a>
                <!-- confirm() : boite de dialogue JS pour eviter une suppression accidentelle -->
                <a href="index.php?module=departement&action=supprimer&id=<?= $departement->getId() ?>"
                   onclick="return confirm('Supprimer ce département ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <!-- Message affiche si le tableau est vide -->
        <?php if (empty($departements)): ?>
        <tr>
            <td colspan="3">Aucun département enregistré pour le moment.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
