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
        <h1>
            <!-- Icone SVG (mallette) a la place d'un emoji, plus propre visuellement -->
            <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="14" rx="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
            </svg>
            Gestion des employés et des congés
        </h1>
        <!-- Navigation vers les 3 modules de l'application, chacune avec sa propre icone -->
        <nav>
            <a href="index.php?module=departement&action=liste">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V7l7-4 7 4v14"></path>
                    <path d="M9 9h1"></path><path d="M14 9h1"></path>
                    <path d="M9 13h1"></path><path d="M14 13h1"></path>
                    <path d="M9 21v-4h6v4"></path>
                </svg>
                Départements
            </a>
            <a href="index.php?module=employe&action=liste">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
                    <circle cx="10" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
                Employés
            </a>
            <a href="index.php?module=conge&action=liste">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path>
                    <path d="m9 16 2 2 4-4"></path>
                </svg>
                Congés
            </a>
        </nav>
    </header>
    <!-- Balise "main" ouverte ici, refermee dans footer.php : -->
    <!-- tout le contenu specifique de chaque page vient s'inserer entre les deux. -->
    <main>
