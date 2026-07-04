<?php
/** @var Conge[] $conges */
/** @var Employe[] $employes */
/** @var array<int, string> $employesParId */
/** @var string $filtreStatut */
/** @var int|string $filtreEmployeId */

require __DIR__ . '/../partials/header.php';
?>

<h2>Congés</h2>

<?php if (($_GET['erreur'] ?? '') === 'solde_insuffisant'): ?>
<p style="color:#b91c1c; font-weight:600;">Impossible de valider : l'employé n'a pas assez de jours de congés disponibles.</p>
<?php endif; ?>

<a class="btn" href="index.php?module=conge&action=ajouter">+ Soumettre une demande de congé</a>

<form method="get" action="index.php" style="display:flex; gap:16px; align-items:end; max-width:none;">
    <input type="hidden" name="module" value="conge">
    <input type="hidden" name="action" value="liste">

    <div style="flex:1;">
        <label for="statut">Filtrer par statut</label>
        <select id="statut" name="statut" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <option value="en_attente" <?= $filtreStatut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
            <option value="valide" <?= $filtreStatut === 'valide' ? 'selected' : '' ?>>Validé</option>
            <option value="refuse" <?= $filtreStatut === 'refuse' ? 'selected' : '' ?>>Refusé</option>
        </select>
    </div>

    <div style="flex:1;">
        <label for="employe_id">Filtrer par employé</label>
        <select id="employe_id" name="employe_id" onchange="this.form.submit()">
            <option value="">Tous les employés</option>
            <?php foreach ($employes as $employe): ?>
            <option value="<?= $employe->getId() ?>" <?= $filtreEmployeId === $employe->getId() ? 'selected' : '' ?>>
                <?= htmlspecialchars($employe->getNom()) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Employé</th>
            <th>Type</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($conges as $conge): ?>
        <tr>
            <td><?= htmlspecialchars((string) $conge->getId()) ?></td>
            <td><?= htmlspecialchars($employesParId[$conge->getEmployeId()] ?? '—') ?></td>
            <td><?= htmlspecialchars($conge->getType()) ?></td>
            <td><?= htmlspecialchars($conge->getDateDebut()) ?></td>
            <td><?= htmlspecialchars($conge->getDateFin()) ?></td>
            <td><span class="badge badge-<?= $conge->getStatut() ?>"><?= htmlspecialchars($conge->getStatut()) ?></span></td>
            <td class="actions">
                <?php if ($conge->getStatut() === 'en_attente'): ?>
                <a href="index.php?module=conge&action=valider&id=<?= $conge->getId() ?>">Valider</a>
                <a href="index.php?module=conge&action=refuser&id=<?= $conge->getId() ?>">Refuser</a>
                <a href="index.php?module=conge&action=modifier&id=<?= $conge->getId() ?>">Modifier</a>
                <?php endif; ?>
                <a href="index.php?module=conge&action=supprimer&id=<?= $conge->getId() ?>"
                   onclick="return confirm('Supprimer cette demande de conge ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($conges)): ?>
        <tr>
            <td colspan="7">Aucune demande de congé pour le moment.</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
