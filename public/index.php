<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../classes/Departement.php';
require_once __DIR__ . '/../classes/DepartementManager.php';

$database = new Database();
$departementManager = new DepartementManager($database);

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
