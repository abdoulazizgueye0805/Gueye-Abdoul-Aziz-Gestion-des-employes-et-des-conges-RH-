<?php
// Cette vue affiche le tableau de tous les departements.
// $departements est fourni par le routeur (public/index.php) avant ce require.
/** @var Departement[] $departements */

require __DIR__ . '/../partials/header.php';
?>

<h2>Départements</h2>

<!-- Lien vers le formulaire d'ajout -->
<a class="btn" href="index.php?module=departement&action=ajouter">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
    Ajouter un département
</a>

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
                <a href="index.php?module=departement&action=modifier&id=<?= $departement->getId() ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    Modifier
                </a>
                <!-- confirm() : boite de dialogue JS pour eviter une suppression accidentelle -->
                <a class="action-supprimer" href="index.php?module=departement&action=supprimer&id=<?= $departement->getId() ?>"
                   onclick="return confirm('Supprimer ce département ?');">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                    Supprimer
                </a>
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
