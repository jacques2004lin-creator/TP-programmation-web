<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

// vérification des paramètres envoyés dans l'URL ID de la réservation et action souhaitée
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    // détermination du nouveau statut en fonction du bouton cliqué
    $nouveau_statut = '';
    if ($action === 'confirmer') {
        $nouveau_statut = 'confirmée';
    } elseif ($action === 'annuler') {
        $nouveau_statut = 'annulée';
    }

    // mise à jour dans la base de données
    if ($nouveau_statut !== '') {
        $stmt = $pdo->prepare("UPDATE reservations SET statut = :statut WHERE id = :id");
        $stmt->execute([
            ':statut' => $nouveau_statut,
            ':id' => $id
        ]);
    }
}

header('Location: dashboard.php');
exit;
