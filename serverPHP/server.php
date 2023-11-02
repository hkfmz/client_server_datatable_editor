<?php
header("Access-Control-Allow-Origin: http://127.0.0.1:5501"); // Remplacez le domaine par celui de votre page HTML
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// TRAITEMENT DU CODE
$dataJson = array();
function readJSONFile($filePath) {
  // Vérifie si le fichier existe
  if (file_exists($filePath)) {
    // Lit le contenu du fichier JSON
    
    $jsonContent = file_get_contents($filePath);
    
    $dataJson = $jsonContent;
    
    // echo "Contenu du fichier JSON : <pre>";
    // echo $dataJson;
    // echo "</pre>";
    
    // Décodage du JSON en tant qu'objet
    $jsonData = json_decode($dataJson);
    
    // echo json_encode($jsonData);
    
    if ($jsonData !== null) {
      return $jsonData; // Retourne l'objet JSON
    } else {
      return null; // En cas d'erreur lors du décodage JSON
    }
  } else {
    return null; // Le fichier n'existe pas
  }
}
// Exemple d'utilisation :
$filePath = "dataJSON.json";
$jsonObject = readJSONFile($filePath);

// Fonction pour ajouter un élément au fichier JSON
function ajouterElementAuJSON($nouvelElementID, $nouvelElement) {
    $jsonFile = 'dataJSON.json';

    // Lire le contenu actuel du fichier JSON
    $data = file_get_contents($jsonFile);
    $json = json_decode($data, true);

    // Générer un nouvel ID unique pour l'élément
    // $nouvelElementID = uniqid();

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

// EXECUTION DU CODE SERVER

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  if (isset($_POST['action'])) {
        
    $action = $_POST['action'];

      if ($action === "afficher") {

          if ($jsonObject !== null) {
            $reponse = json_encode($jsonObject);
            echo $reponse;
          } else {
            $reponse = json_encode("Erreur : Le fichier JSON n'a pas pu être lu.");
            echo $reponse;
          }
      }
            
      if ($action === "create") {

            $nouvelElement = array();

            $nouvelElement =  $_POST['content'];
            $nouvelElementID = $nouvelElement["DT_RowId"];

              ajouterElementAuJSON($nouvelElementID, $nouvelElement);

              if ($jsonObject !== null) {
                $reponse = json_encode($jsonObject);
                echo $reponse;
              } else {
                $reponse = json_encode("Erreur : Le fichier JSON n'a pas pu être lu.");
                echo $reponse;
              }
      }

      if ($action === "edit") {

              $nouvelElement = array();

              $nouvelElement =  $_POST['content'];

              //  print_r("ELEMENT EDIT =========",$nouvelElement);
              $id = $nouvelElement["DT_RowId"];
              $nouvellesDonnees = $nouvelElement;

                modifierElementJSON($id, $nouvellesDonnees);

                if ($jsonObject !== null) {
                  $reponse = json_encode($jsonObject);
                  echo $reponse;
                } else {
                  $reponse = json_encode("Erreur : Le fichier JSON n'a pas pu être lu.");
                  echo $reponse;
                }
      }

      if ($action === "remove") {

              $nouvelElement = array();

              $nouvelElement =  $_POST['content'];

              $elementID = $nouvelElement["DT_RowId"];

                supprimerElementJSON($elementID);

                if ($jsonObject !== null) {
                  $reponse = json_encode($jsonObject);
                  echo $reponse;
                } else {
                  $reponse = json_encode("Erreur : Le fichier JSON n'a pas pu être lu.");
                  echo $reponse;
                }
      }
 } 


} 

?>

