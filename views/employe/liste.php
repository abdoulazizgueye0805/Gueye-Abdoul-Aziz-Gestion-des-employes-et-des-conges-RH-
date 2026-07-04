<?php
// Cette vue affiche le tableau des employes, avec une barre de recherche.
// Toutes ces variables sont preparees par le routeur (public/index.php) avant ce require.
/** @var Employe[] $employes */
/** @var array<int, string> $departementsParId */
/** @var string $termeRecherche */

require __DIR__ . '/../partials/header.php';
?>

<h2>Employés</h2>

<a class="btn" href="index.php?module=employe&action=ajouter">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
    Ajouter un employé
</a>

<!-- Formulaire de recherche : methode GET, donc le terme tape apparait dans l'URL
     (ex: ?module=employe&action=liste&q=Diop), ce qui permet de recharger/partager la page facilement. -->
<form method="get" action="index.php" style="display:flex; gap:12px; align-items:end; max-width:none; margin-top:16px;">
    <!-- Champs caches : on garde toujours module=employe et action=liste, seul "q" change -->
    <input type="hidden" name="module" value="employe">
    <input type="hidden" name="action" value="liste">

    <div style="flex:1;">
        <label for="q">Rechercher (nom, poste ou email)</label>
        <!-- On reaffiche le terme tape, pour que la barre de recherche ne se vide pas apres soumission -->
        <input type="text" id="q" name="q" value="<?= htmlspecialchars($termeRecherche) ?>"
               placeholder="Ex : Diop, Comptable...">
    </div>

    <button class="btn" type="submit" style="margin-bottom:16px;">Rechercher</button>
    <!-- Le bouton "Effacer" n'apparait que si une recherche est active -->
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
            <!-- On affiche le NOM du departement (via le tableau de correspondance $departementsParId),
                 pas juste son id, qui serait illisible pour l'utilisateur. -->
            <td><?= htmlspecialchars($departementsParId[$employe->getDepartementId()] ?? '—') ?></td>
            <td class="actions">
                <a href="index.php?module=employe&action=modifier&id=<?= $employe->getId() ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"></path></svg>
                    Modifier
                </a>
                <a class="action-supprimer" href="index.php?module=employe&action=supprimer&id=<?= $employe->getId() ?>"
                   onclick="return confirm('Supprimer cet employé ?');">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                    Supprimer
                </a>
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
