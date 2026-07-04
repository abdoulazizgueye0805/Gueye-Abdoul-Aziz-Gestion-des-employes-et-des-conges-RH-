<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Departement.php';
require_once __DIR__ . '/../classes/DepartementManager.php';
require_once __DIR__ . '/../classes/Employe.php';
require_once __DIR__ . '/../classes/EmployeManager.php';
require_once __DIR__ . '/../classes/Conge.php';
require_once __DIR__ . '/../classes/CongeManager.php';

function validerPeriodeConge(string $dateDebut, string $dateFin): ?string
{
    $aujourdHui = date('Y-m-d');

    if ($dateDebut < $aujourdHui) {
        return "La date de début ne peut pas être une date passée.";
    }

    if ($dateFin < $dateDebut) {
        return "La date de fin doit être postérieure ou égale à la date de début.";
    }

    return null;
}

$database = new Database();
$departementManager = new DepartementManager($database);
$employeManager = new EmployeManager($database);
$congeManager = new CongeManager($database);

$module = $_GET['module'] ?? 'departement';
$action = $_GET['action'] ?? 'liste';

if ($module === 'departement') {
    switch ($action) {
        case 'ajouter':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $departement = new Departement($_POST['nom']);
                $departementManager->ajouter($departement);
                header('Location: index.php?module=departement&action=liste');
                exit;
            }
            require __DIR__ . '/../views/departement/formulaire.php';
            break;

        case 'modifier':
            $id = (int) $_GET['id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $departement = new Departement($_POST['nom'], $id);
                $departementManager->modifier($departement);
                header('Location: index.php?module=departement&action=liste');
                exit;
            }

            $departement = $departementManager->trouverParId($id);
            require __DIR__ . '/../views/departement/formulaire.php';
            break;

        case 'supprimer':
            $id = (int) $_GET['id'];
            $departementManager->supprimer($id);
            header('Location: index.php?module=departement&action=liste');
            exit;

        case 'liste':
        default:
            $departements = $departementManager->lister();
            require __DIR__ . '/../views/departement/liste.php';
            break;
    }
}

if ($module === 'employe') {
    $departements = $departementManager->lister();

    switch ($action) {
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

        case 'modifier':
            $id = (int) $_GET['id'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $employe = new Employe(
                    $_POST['nom'],
                    $_POST['poste'],
                    $_POST['email'],
                    (int) $_POST['departement_id'],
                    $id
                );
                $employeManager->modifier($employe);
                header('Location: index.php?module=employe&action=liste');
                exit;
            }

            $employe = $employeManager->trouverParId($id);
            require __DIR__ . '/../views/employe/formulaire.php';
            break;

        case 'supprimer':
            $id = (int) $_GET['id'];
            $employeManager->supprimer($id);
            header('Location: index.php?module=employe&action=liste');
            exit;

        case 'liste':
        default:
            $employes = $employeManager->lister();
            $departementsParId = [];
            foreach ($departements as $departement) {
                $departementsParId[$departement->getId()] = $departement->getNom();
            }
            require __DIR__ . '/../views/employe/liste.php';
            break;
    }
}

if ($module === 'conge') {
    $employes = $employeManager->lister();

    switch ($action) {
        case 'ajouter':
            $formAction = 'ajouter';
            $estModification = false;

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $conge = new Conge(
                    (int) $_POST['employe_id'],
                    $_POST['type'],
                    $_POST['date_debut'],
                    $_POST['date_fin']
                );
                $erreur = validerPeriodeConge($_POST['date_debut'], $_POST['date_fin']);

                if ($erreur === null) {
                    $congeManager->ajouter($conge);
                    header('Location: index.php?module=conge&action=liste');
                    exit;
                }
            }

            require __DIR__ . '/../views/conge/formulaire.php';
            break;

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
                $conge = $congeManager->trouverParId($id);
            }

            require __DIR__ . '/../views/conge/formulaire.php';
            break;

        case 'valider':
            $congeManager->changerStatut((int) $_GET['id'], 'valide');
            header('Location: index.php?module=conge&action=liste');
            exit;

        case 'refuser':
            $congeManager->changerStatut((int) $_GET['id'], 'refuse');
            header('Location: index.php?module=conge&action=liste');
            exit;

        case 'supprimer':
            $congeManager->supprimer((int) $_GET['id']);
            header('Location: index.php?module=conge&action=liste');
            exit;

        case 'liste':
        default:
            $filtreStatut = $_GET['statut'] ?? '';
            $filtreEmployeId = isset($_GET['employe_id']) && $_GET['employe_id'] !== ''
                ? (int) $_GET['employe_id']
                : '';

            if ($filtreEmployeId !== '') {
                $conges = $congeManager->listerParEmploye($filtreEmployeId);
            } elseif ($filtreStatut !== '') {
                $conges = $congeManager->listerParStatut($filtreStatut);
            } else {
                $conges = $congeManager->lister();
            }

            $employesParId = [];
            foreach ($employes as $employe) {
                $employesParId[$employe->getId()] = $employe->getNom();
            }

            require __DIR__ . '/../views/conge/liste.php';
            break;
    }
}
