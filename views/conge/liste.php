<?php
// Cette vue affiche la liste des demandes de conge, avec des filtres
// (par statut et/ou par employe) fournis par le routeur.
/** @var Conge[] $conges */
/** @var Employe[] $employes */
/** @var array<int, string> $employesParId */
/** @var string $filtreStatut */
/** @var int|string $filtreEmployeId */

require __DIR__ . '/../partials/header.php';
?>

<h2>Congés</h2>

<a class="btn" href="index.php?module=conge&action=ajouter">+ Soumettre une demande de congé</a>

<!-- Formulaire de filtres : chaque select se soumet automatiquement au changement
     (onchange="this.form.submit()"), pas besoin de bouton "Filtrer". -->
<form method="get" action="index.php" style="display:flex; gap:16px; align-items:end; max-width:none;">
    <input type="hidden" name="module" value="conge">
    <input type="hidden" name="action" value="liste">

    <div style="flex:1;">
        <label for="statut">Filtrer par statut</label>
        <select id="statut" name="statut" onchange="this.form.submit()">
            <option value="">Tous les statuts</option>
            <!-- "selected" est ajoute dynamiquement pour que le filtre actif reste visible apres rechargement -->
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
            <!-- On affiche le NOM de l'employe (via $employesParId), pas juste son id -->
            <td><?= htmlspecialchars($employesParId[$conge->getEmployeId()] ?? '—') ?></td>
            <td><?= htmlspecialchars($conge->getType()) ?></td>
            <td><?= htmlspecialchars($conge->getDateDebut()) ?></td>
            <td><?= htmlspecialchars($conge->getDateFin()) ?></td>
            <!-- Badge colore selon le statut : voir les classes .badge-en_attente / .badge-valide /
                 .badge-refuse dans style.css -->
            <td><span class="badge badge-<?= $conge->getStatut() ?>"><?= htmlspecialchars($conge->getStatut()) ?></span></td>
            <td class="actions">
                <!-- Valider/Refuser ne sont proposes que si la demande est encore en attente -->
                <?php if ($conge->getStatut() === 'en_attente'): ?>
                <a href="index.php?module=conge&action=valider&id=<?= $conge->getId() ?>">Valider</a>
                <a href="index.php?module=conge&action=refuser&id=<?= $conge->getId() ?>">Refuser</a>
                <?php endif; ?>
                <a href="index.php?module=conge&action=modifier&id=<?= $conge->getId() ?>">Modifier</a>
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
