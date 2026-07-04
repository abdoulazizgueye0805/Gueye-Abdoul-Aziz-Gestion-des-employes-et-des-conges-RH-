<?php require __DIR__ . '/../partials/header.php'; ?>

<h2><?= isset($conge) ? 'Modifier la demande de congé' : 'Soumettre une demande de congé' ?></h2>

<form method="post" action="index.php?module=conge&action=<?= isset($conge) ? 'modifier&id=' . $conge->getId() : 'ajouter' ?>">
    <label for="employe_id">Employé</label>
    <select id="employe_id" name="employe_id" required>
        <?php foreach ($employes as $employe): ?>
        <option value="<?= $employe->getId() ?>"
            <?= (isset($conge) && $conge->getEmployeId() === $employe->getId()) ? 'selected' : '' ?>>
            <?= htmlspecialchars($employe->getNom()) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label for="type">Type de congé</label>
    <select id="type" name="type" required>
        <?php foreach (['Congé payé', 'Congé maladie', 'Congé sans solde', 'Autre'] as $type): ?>
        <option value="<?= $type ?>" <?= (isset($conge) && $conge->getType() === $type) ? 'selected' : '' ?>>
            <?= $type ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label for="date_debut">Date de début</label>
    <input type="date" id="date_debut" name="date_debut" required
           value="<?= isset($conge) ? htmlspecialchars($conge->getDateDebut()) : '' ?>">

    <label for="date_fin">Date de fin</label>
    <input type="date" id="date_fin" name="date_fin" required
           value="<?= isset($conge) ? htmlspecialchars($conge->getDateFin()) : '' ?>">

    <button class="btn" type="submit">Enregistrer</button>
</form>

<?php require __DIR__ . '/../partials/footer.php'; ?>
