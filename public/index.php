<?php

// Ce fichier est le POINT D'ENTREE unique de toute l'application (le "controleur").
// C'est le seul fichier que le navigateur appelle directement.
// Il decide, selon l'URL demandee, quelle action effectuer et quelle vue afficher.

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Departement.php';
require_once __DIR__ . '/../classes/DepartementManager.php';
require_once __DIR__ . '/../classes/Employe.php';
require_once __DIR__ . '/../classes/EmployeManager.php';
require_once __DIR__ . '/../classes/Conge.php';
require_once __DIR__ . '/../classes/CongeManager.php';

// Fonction utilitaire : verifie que la periode d'un conge est valide.
// Renvoie null si tout est correct, ou un message d'erreur en texte sinon.
function validerPeriodeConge(string $dateDebut, string $dateFin): ?string
{
    $aujourdHui = date('Y-m-d'); // date du jour au format AAAA-MM-JJ

    // Regle 1 : on ne peut pas poser un conge qui commence dans le passe.
    if ($dateDebut < $aujourdHui) {
        return "La date de début ne peut pas être une date passée.";
    }

    // Regle 2 : la date de fin doit etre apres (ou egale a) la date de debut.
    if ($dateFin < $dateDebut) {
        return "La date de fin doit être postérieure ou égale à la date de début.";
    }

    return null; // aucune erreur
}

// On cree une seule fois la connexion et les 3 gestionnaires (managers),
// reutilises ensuite dans tout le fichier.
$database = new Database();
$departementManager = new DepartementManager($database);
$employeManager = new EmployeManager($database);
$congeManager = new CongeManager($database);

// On lit dans l'URL quel "module" (departement / employe / conge) et quelle "action"
// (liste / ajouter / modifier / supprimer...) sont demandes.
// Exemple d'URL : index.php?module=employe&action=liste
// Si rien n'est precise, on affiche par defaut la liste des departements.
$module = $_GET['module'] ?? 'departement';
$action = $_GET['action'] ?? 'liste';

// ==================== MODULE DEPARTEMENTS ====================
if ($module === 'departement') {
    switch ($action) {

        // --- Ajouter un departement ---
        case 'ajouter':
            // Si le formulaire a ete envoye (methode POST), on traite les donnees.
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $departement = new Departement($_POST['nom']);
                $departementManager->ajouter($departement);
                // Redirection vers la liste pour eviter de renvoyer le formulaire
                // si l'utilisateur rafraichit la page (technique "PRG" : Post/Redirect/Get).
                header('Location: index.php?module=departement&action=liste');
                exit;
            }
            // Sinon (methode GET), on affiche simplement le formulaire vide.
            require __DIR__ . '/../views/departement/formulaire.php';
            break;

        // --- Modifier un departement existant ---
        case 'modifier':
            $id = (int) $_GET['id']; // id du departement a modifier, passe dans l'URL

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // On cree un objet Departement avec le nouveau nom ET le meme id,
                // pour que la requete UPDATE sache quelle ligne modifier.
                $departement = new Departement($_POST['nom'], $id);
                $departementManager->modifier($departement);
                header('Location: index.php?module=departement&action=liste');
                exit;
            }

            // Sinon, on va chercher le departement existant pour pre-remplir le formulaire.
            $departement = $departementManager->trouverParId($id);
            require __DIR__ . '/../views/departement/formulaire.php';
            break;

        // --- Supprimer un departement ---
        case 'supprimer':
            $id = (int) $_GET['id'];
            $departementManager->supprimer($id);
            header('Location: index.php?module=departement&action=liste');
            exit;

        // --- Afficher la liste des departements (cas par defaut) ---
        case 'liste':
        default:
            $departements = $departementManager->lister();
            require __DIR__ . '/../views/departement/liste.php';
            break;
    }
}

// ==================== MODULE EMPLOYES ====================
if ($module === 'employe') {
    // On a besoin de la liste des departements dans presque toutes les actions
    // (pour remplir le menu deroulant du formulaire employe).
    $departements = $departementManager->lister();

    switch ($action) {

        // --- Ajouter un employe ---
        case 'ajouter':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $employe = new Employe(
                    $_POST['nom'],
                    $_POST['poste'],
                    $_POST['email'],
                    (int) $_POST['departement_id']
                );
                $employeManager->ajouter($employe);
                header('Location: index.php?module=employe&action=liste');
                exit;
            }
            require __DIR__ . '/../views/employe/formulaire.php';
            break;

        // --- Modifier un employe existant ---
        case 'modifier':
            $id = (int) $_GET['id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $employe = new Employe(
                    $_POST['nom'],
                    $_POST['poste'],
                    $_POST['email'],
                    (int) $_POST['departement_id'],
                    $id // on garde le meme id pour la mise a jour
                );
                $employeManager->modifier($employe);
                header('Location: index.php?module=employe&action=liste');
                exit;
            }

            $employe = $employeManager->trouverParId($id);
            require __DIR__ . '/../views/employe/formulaire.php';
            break;

        // --- Supprimer un employe ---
        case 'supprimer':
            $id = (int) $_GET['id'];
            $employeManager->supprimer($id);
            header('Location: index.php?module=employe&action=liste');
            exit;

        // --- Afficher la liste des employes (avec recherche optionnelle) ---
        case 'liste':
        default:
            // Si un terme de recherche est present dans l'URL (?q=...), on filtre ;
            // sinon on affiche tous les employes.
            $termeRecherche = trim($_GET['q'] ?? '');
            $employes = $termeRecherche !== ''
                ? $employeManager->rechercher($termeRecherche)
                : $employeManager->lister();

            // On construit un tableau "id du departement => nom du departement"
            // pour pouvoir afficher facilement le NOM du departement de chaque employe
            // dans la vue (au lieu de juste son id).
            $departementsParId = [];
            foreach ($departements as $departement) {
                $departementsParId[$departement->getId()] = $departement->getNom();
            }
            require __DIR__ . '/../views/employe/liste.php';
            break;
    }
}

// ==================== MODULE CONGES ====================
if ($module === 'conge') {
    // La liste des employes sert a remplir le menu deroulant du formulaire
    // et le filtre de la liste des conges.
    $employes = $employeManager->lister();

    switch ($action) {

        // --- Soumettre une nouvelle demande de conge ---
        case 'ajouter':
            $formAction = 'ajouter';     // utilise dans la vue pour l'attribut "action" du formulaire
            $estModification = false;   // utilise dans la vue pour le titre de la page

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conge = new Conge(
                    (int) $_POST['employe_id'],
                    $_POST['type'],
                    $_POST['date_debut'],
                    $_POST['date_fin']
                    // le statut n'est pas precise : il prendra sa valeur par defaut 'en_attente'
                );
                // Verification des dates AVANT d'enregistrer en base.
                $erreur = validerPeriodeConge($_POST['date_debut'], $_POST['date_fin']);

                if ($erreur === null) {
                    $congeManager->ajouter($conge);
                    header('Location: index.php?module=conge&action=liste');
                    exit;
                }
                // Si $erreur n'est pas null, on ne redirige pas : on retombe plus bas
                // sur l'affichage du formulaire, avec le message d'erreur et les valeurs saisies.
            }

            require __DIR__ . '/../views/conge/formulaire.php';
            break;

        // --- Modifier une demande de conge existante ---
        case 'modifier':
            $id = (int) $_GET['id'];
            $formAction = 'modifier&id=' . $id;
            $estModification = true;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conge = new Conge(
                    (int) $_POST['employe_id'],
                    $_POST['type'],
                    $_POST['date_debut'],
                    $_POST['date_fin'],
                    // On recupere le statut ACTUEL en base (on ne le modifie pas ici,
                    // seuls "Valider"/"Refuser" changent le statut).
                    $congeManager->trouverParId($id)->getStatut(),
                    $id
                );
                $erreur = validerPeriodeConge($_POST['date_debut'], $_POST['date_fin']);

                if ($erreur === null) {
                    $congeManager->modifier($conge);
                    header('Location: index.php?module=conge&action=liste');
                    exit;
                }
            } else {
                // Methode GET : on charge le conge existant pour pre-remplir le formulaire.
                $conge = $congeManager->trouverParId($id);
            }

            require __DIR__ . '/../views/conge/formulaire.php';
            break;

        // --- Valider une demande de conge ---
        case 'valider':
            $congeManager->changerStatut((int) $_GET['id'], 'valide');
            header('Location: index.php?module=conge&action=liste');
            exit;

        // --- Refuser une demande de conge ---
        case 'refuser':
            $congeManager->changerStatut((int) $_GET['id'], 'refuse');
            header('Location: index.php?module=conge&action=liste');
            exit;

        // --- Supprimer une demande de conge ---
        case 'supprimer':
            $congeManager->supprimer((int) $_GET['id']);
            header('Location: index.php?module=conge&action=liste');
            exit;

        // --- Afficher la liste des conges (avec filtres optionnels) ---
        case 'liste':
        default:
            // Recupere les filtres eventuels passes dans l'URL
            // (ex : ?statut=en_attente ou ?employe_id=3).
            $filtreStatut = $_GET['statut'] ?? '';
            $filtreEmployeId = isset($_GET['employe_id']) && $_GET['employe_id'] !== ''
                ? (int) $_GET['employe_id']
                : '';

            // Priorite : si un employe est choisi, on filtre par employe ;
            // sinon si un statut est choisi, on filtre par statut ;
            // sinon on affiche tout.
            if ($filtreEmployeId !== '') {
                $conges = $congeManager->listerParEmploye($filtreEmployeId);
            } elseif ($filtreStatut !== '') {
                $conges = $congeManager->listerParStatut($filtreStatut);
            } else {
                $conges = $congeManager->lister();
            }

            // Tableau "id employe => nom employe" pour afficher le nom
            // plutot que l'id dans le tableau des conges.
            $employesParId = [];
            foreach ($employes as $employe) {
                $employesParId[$employe->getId()] = $employe->getNom();
            }

            require __DIR__ . '/../views/conge/liste.php';
            break;
    }
}
