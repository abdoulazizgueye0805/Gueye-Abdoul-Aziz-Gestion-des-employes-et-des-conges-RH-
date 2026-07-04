<?php require __DIR__ . '/../partials/header.php'; ?>

<h2><?= isset($departement) ? 'Modifier le département' : 'Ajouter un département' ?></h2>

<form method="post" action="index.php?module=departement&action=<?= isset($departement) ? 'modifier&id=' . $departement->getId() : 'ajouter' ?>">
    <label for="nom">Nom du département</label>
    <input type="text" id="nom" name="nom" required
           value="<?= isset($departement) ? htmlspecialchars($departement->getNom()) : '' ?>">

    <button class="btn" type="submit">Enregistrer</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
