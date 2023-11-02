<?php

// Fonction pour ajouter un élément au fichier JSON
function ajouterElementAuJSON($nouvelElement) {
    $jsonFile = 'dataJSON.json';

    // Lire le contenu actuel du fichier JSON
    $data = file_get_contents($jsonFile);
    $json = json_decode($data, true);

    // Générer un nouvel ID unique pour l'élément
    $nouvelElementID = uniqid();

    // Ajouter le nouvel élément avec l'ID généré
    $json[$nouvelElementID] = $nouvelElement;

    // Enregistrer les modifications dans le fichier JSON
    file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
}

// Fonction pour modifier un élément existant dans le fichier JSON
function modifierElementJSON($elementID, $nouvellesDonnees) {
    $jsonFile = 'dataJSON.json';

    // Lire le contenu actuel du fichier JSON
    $data = file_get_contents($jsonFile);
    $json = json_decode($data, true);

    // Vérifier si l'élément existe
    if (array_key_exists($elementID, $json)) {
        // Mettre à jour les données de l'élément
        $json[$elementID] = $nouvellesDonnees;

        // Enregistrer les modifications dans le fichier JSON
        file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
    } else {
        echo "L'élément avec l'ID $elementID n'existe pas.";
    }
}

// Fonction pour supprimer un élément du fichier JSON
function supprimerElementJSON($elementID) {
    $jsonFile = 'dataJSON.json';

    // Lire le contenu actuel du fichier JSON
    $data = file_get_contents($jsonFile);
    $json = json_decode($data, true);

    // Vérifier si l'élément existe
    if (array_key_exists($elementID, $json)) {
        // Supprimer l'élément
        unset($json[$elementID]);

        // Enregistrer les modifications dans le fichier JSON
        file_put_contents($jsonFile, json_encode($json, JSON_PRETTY_PRINT));
    } else {
        echo "L'élément avec l'ID $elementID n'existe pas.";
    }
}

// Exemple d'utilisation :
$nouvelElement = array(
    "item" => "Orange 🍊",
    "status" => "À faire",
    "DT_RowId" => uniqid() // Générer un nouvel ID unique
);

ajouterElementAuJSON($nouvelElement);

// Modifier un élément existant (remplacez 'ID_de_l_element_a_modifier' par l'ID de l'élément que vous souhaitez modifier)
$elementIDAModifier = 'ID_de_l_element_a_modifier';
$nouvellesDonnees = array(
    "item" => "Nouvelle Tâche",
    "status" => "Terminé"
);

modifierElementJSON($elementIDAModifier, $nouvellesDonnees);

// Supprimer un élément (remplacez 'ID_de_l_element_a_supprimer' par l'ID de l'élément que vous souhaitez supprimer)
$elementIDASupprimer = 'ID_de_l_element_a_supprimer';
supprimerElementJSON($elementIDASupprimer);
?>
