<!-- En-tete commun a toutes les pages : inclus par chaque vue via require. -->
<!-- Contient l'ouverture du HTML, les feuilles de style, et la barre de navigation. -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des employés et des congés - RH</title>
    <!-- Police Google Fonts "Poppins", utilisee dans style.css -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Chemin relatif car ce fichier est servi depuis la racine "public/" -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <h1>Gestion des employés et des congés</h1>
        <!-- Navigation vers les 3 modules de l'application -->
        <nav>
            <a href="index.php?module=departement&action=liste">Départements</a>
            <a href="index.php?module=employe&action=liste">Employés</a>
            <a href="index.php?module=conge&action=liste">Congés</a>
        </nav>
    </header>
    <!-- Balise "main" ouverte ici, refermee dans footer.php : -->
    <!-- tout le contenu specifique de chaque page vient s'inserer entre les deux. -->
    <main>
