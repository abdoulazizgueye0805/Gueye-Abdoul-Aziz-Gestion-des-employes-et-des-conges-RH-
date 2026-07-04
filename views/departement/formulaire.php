<?php
// Ce formulaire sert a la fois pour AJOUTER et pour MODIFIER un departement.
// $departement n'est defini par le routeur QUE si on est en mode modification
// (sinon la variable n'existe pas, d'ou les verifications isset() ci-dessous).
/** @var Departement|null $departement */

require __DIR__ . '/../partials/header.php';
?>

<!-- Le titre change selon qu'on ajoute ou qu'on modifie -->
<h2><?= isset($departement) ? 'Modifier le département' : 'Ajouter un département' ?></h2>

<!-- L'action du formulaire pointe vers "ajouter" ou "modifier&id=..." selon le mode -->
<form method="post" action="index.php?module=departement&action=<?= isset($departement) ? 'modifier&id=' . $departement->getId() : 'ajouter' ?>">
    <label for="nom">Nom du département</label>
    <!-- Si on modifie, le champ est pre-rempli avec le nom actuel -->
    <input type="text" id="nom" name="nom" required
           value="<?= isset($departement) ? htmlspecialchars($departement->getNom()) : '' ?>">

    <button class="btn" type="submit">Enregistrer</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
