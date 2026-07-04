<?php
/** @var Employe|null $employe */
/** @var Departement[] $departements */

require __DIR__ . '/../partials/header.php';
?>

<h2><?= isset($employe) ? 'Modifier l\'employé' : 'Ajouter un employé' ?></h2>

<form method="post" action="index.php?module=employe&action=<?= isset($employe) ? 'modifier&id=' . $employe->getId() : 'ajouter' ?>">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required
           value="<?= isset($employe) ? htmlspecialchars($employe->getNom()) : '' ?>">

    <label for="poste">Poste</label>
    <input type="text" id="poste" name="poste" required
           value="<?= isset($employe) ? htmlspecialchars($employe->getPoste()) : '' ?>">

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required
           value="<?= isset($employe) ? htmlspecialchars($employe->getEmail()) : '' ?>">

    <label for="departement_id">Département</label>
    <select id="departement_id" name="departement_id" required>
        <?php foreach ($departements as $departement): ?>
        <option value="<?= $departement->getId() ?>"
            <?= (isset($employe) && $employe->getDepartementId() === $departement->getId()) ? 'selected' : '' ?>>
            <?= htmlspecialchars($departement->getNom()) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <button class="btn" type="submit">Enregistrer</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
