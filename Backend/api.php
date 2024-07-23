<?php
header('Content-Type: application/json');
// Configuration CORS
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

$action = $_GET['action'] ?? '';

$usr = 'root';
$psswd = '';
$host = 'localhost';
$db = 'societe';
 
$connection = new mysqli($host, $usr, $psswd, $db);

if($connection->connect_error) {
    die('Erreur de connexion '. $connection->connect_error);
}

switch($action) {
    case 'getID':
        $sql = "SELECT MAX(CAST(SUBSTRING(numEmp, 2) AS UNSIGNED)) AS last_id FROM employe";
        $result = $connection->query($sql);
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            $row = ['last_id'=> 1];
            echo json_encode($row);
        }
        break;
    
    case 'getAll':
        $sql = "SELECT * FROM employe ORDER BY nom ASC";
        $result = $connection->query($sql);
        if($result) {
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows);
            }
        } else {
            echo 'Vérifier la requête';
        }
        break;
    case 'update':
        // Récupérer les données envoyées dans le corps de la requête
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Récupérer l'ID de l'employé à mettre à jour
        $id = $_GET['id'] ?? '';
        
        if($id && isset($data['nom']) && isset($data['salaire']) && $data['salaire'] !== null) {
            $nom = $data['nom'];
            $salaire = $data['salaire'];
            
            // Préparer et exécuter la requête de mise à jour
            $stmt = $connection->prepare("UPDATE employe SET nom = ?, salaire = ? WHERE numEmp = ?");
            $stmt->bind_param("sis", $nom, $salaire, $id);
            
            if($stmt->execute()) {
                echo json_encode(['message' => 'Employé mis à jour avec succès ' . $id]);
            } else {
                echo json_encode(['message' => 'Erreur lors de la mise à jour de l\'employé']);
            }
            
            $stmt->close();
        } else {
            echo json_encode(['message' => 'ID, nom et salaire sont nécessaires ' . $data['nom'] . ' ' . $data['salaire'] . ' ' . $id]);
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? '';
        
        if($id) {
            $stmt = $connection->prepare("DELETE FROM employe WHERE numEmp = ?");
            $stmt->bind_param("s", $id);
            
            if($stmt->execute()) {
                echo json_encode(['message' => 'Employé supprimé avec succès']);
            } else {
                echo json_encode(['message' => 'Erreur lors de la suppression de l\'employé']);
            }
            
            $stmt->close();
        } else {
            echo json_encode(['message' => 'ID de l\'employé nécessaire']);
        }
        break;
        
    case 'create':
        // Récupérer les données envoyées dans le corps de la requête
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Récupérer l'ID de l'employé à mettre à jour
        $sql = "SELECT MAX(CAST(SUBSTRING(numEmp, 2) AS UNSIGNED)) AS last_id FROM employe";
        $result = $connection->query($sql);
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row['last_id'] + 1;
        } else {
            $id = 1;
        }
        
        if($id && isset($data['nom']) && isset($data['salaire']) && $data['salaire'] !== null) {
            $nom = $data['nom'];
            $salaire = $data['salaire'];
            $numEmp = 'E' . $id;

            // Préparer et exécuter la requête de mise à jour
            $stmt = $connection->prepare("INSERT INTO employe (numEmp, nom, salaire) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $numEmp, $nom, $salaire);
            
            if($stmt->execute()) {
                echo json_encode(['message' => 'Employé mis à jour avec succès ' . $id]);
            } else {
                echo json_encode(['message' => 'Erreur lors de la mise à jour de l\'employé']);
            }
            
            $stmt->close();
        } else {
            echo json_encode(['message' => 'ID, nom et salaire sont nécessaires ' . $data['nom'] . ' ' . $data['salaire'] . ' ' . $id]);
        }
        break;
        
    default:
        echo json_encode(['message' => 'Action non supportée']);
        break;
}