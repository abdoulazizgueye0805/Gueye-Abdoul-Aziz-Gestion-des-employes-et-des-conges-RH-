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

<a class="btn" href="index.php?module=conge&action=ajouter">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
    Soumettre une demande de congé
</a>

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
                <a href="index.php?module=conge&action=valider&id=<?= $conge->getId() ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                    Valider
                </a>
                <a class="action-supprimer" href="index.php?module=conge&action=refuser&id=<?= $conge->getId() ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="M6 6l12 12"></path></svg>
                    Refuser
                </a>
                <?php endif; ?>
                <a href="index.php?module=conge&action=modifier&id=<?= $conge->getId() ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    Modifier
                </a>
                <a class="action-supprimer" href="index.php?module=conge&action=supprimer&id=<?= $conge->getId() ?>"
                   onclick="return confirm('Supprimer cette demande de conge ?');">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                    Supprimer
                </a>
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
