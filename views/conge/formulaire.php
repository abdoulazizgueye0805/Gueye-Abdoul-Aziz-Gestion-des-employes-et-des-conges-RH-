<?php
// Formulaire utilise pour SOUMETTRE une nouvelle demande de conge ou MODIFIER une existante.
// $conge n'existe que si on est en mode modification.
// $formAction et $estModification sont fournis explicitement par le routeur (plutot que
// devines avec isset($conge)), pour rester corrects meme en cas d'erreur de validation
// (ou $conge peut alors contenir les valeurs invalides saisies par l'utilisateur).
/** @var Conge|null $conge */
/** @var Employe[] $employes */
/** @var string $formAction */
/** @var bool $estModification */
/** @var string|null $erreur */

require __DIR__ . '/../partials/header.php';
?>

<h2><?= $estModification ? 'Modifier la demande de congé' : 'Soumettre une demande de congé' ?></h2>

<!-- Message d'erreur affiche si la periode saisie est invalide (date passee, fin avant debut...) -->
<?php if (!empty($erreur)): ?>
<p style="color:#b91c1c; font-weight:600;"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post" action="index.php?module=conge&action=<?= $formAction ?>">
    <!-- Menu deroulant : choix de l'employe concerne par la demande -->
    <label for="employe_id">Employé</label>
    <select id="employe_id" name="employe_id" required>
        <?php foreach ($employes as $employe): ?>
        <option value="<?= $employe->getId() ?>"
            <?= (isset($conge) && $conge->getEmployeId() === $employe->getId()) ? 'selected' : '' ?>>
            <?= htmlspecialchars($employe->getNom()) ?>
        </option>
        <?php endforeach; ?>
    </select>

    <!-- Liste fixe de types de conge (simple a maintenir pour ce projet) -->
    <label for="type">Type de congé</label>
    <select id="type" name="type" required>
        <?php foreach (['Congé payé', 'Congé maladie', 'Congé sans solde', 'Autre'] as $type): ?>
        <option value="<?= $type ?>" <?= (isset($conge) && $conge->getType() === $type) ? 'selected' : '' ?>>
            <?= $type ?>
        </option>
        <?php endforeach; ?>
    </select>

    <!-- L'attribut "min" empeche visuellement de choisir une date passee dans le calendrier du navigateur -->
    <label for="date_debut">Date de début</label>
    <input type="date" id="date_debut" name="date_debut" required
           min="<?= date('Y-m-d') ?>"
           value="<?= isset($conge) ? htmlspecialchars($conge->getDateDebut()) : '' ?>">

    <label for="date_fin">Date de fin</label>
    <input type="date" id="date_fin" name="date_fin" required
           min="<?= date('Y-m-d') ?>"
           value="<?= isset($conge) ? htmlspecialchars($conge->getDateFin()) : '' ?>">

    <button class="btn" type="submit">Enregistrer</button>
</form>

<!-- Petit script cote navigateur : quand on change la date de debut, on empeche
     de choisir une date de fin anterieure. C'est un confort visuel uniquement :
     la vraie securite vient de la verification PHP cote serveur (validerPeriodeConge). -->
<script>
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');

    dateDebut.addEventListener('change', () => {
        dateFin.min = dateDebut.value;
        if (dateFin.value && dateFin.value < dateDebut.value) {
            dateFin.value = dateDebut.value;
        }
    });
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
