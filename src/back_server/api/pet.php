<?php
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,"/") !== 0){
    $url = "/$url";
}

$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

header("Content-Type:application/json");
error_log("URL: " . $url);
error_log("METHOD: " . $_SERVER['REQUEST_METHOD']);

if($url == '/pet' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List Pets");
    $pets = getAllPets($dbConn);
    echo json_encode($pets);
}

if (preg_match("/pet\/([0-9]+)\/character/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List pet characters");

    $petId = $matches[1];
    $characters = getCharacters($dbConn, $petId);
    echo json_encode($characters);
    return;
}

if($url == '/pet' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create Pet");
    $input = $_POST;
    $petId = addPet($input, $dbConn);
    if($petId){
        $input['PetId'] = $petId;
        $input['link'] = "/pet/$petId";
    }

    echo json_encode($input);

}

if(preg_match("/pet\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update Pet");

    $input = $_GET;
    $petId = $matches[1];
    updatePet($input, $dbConn, $petId);

    $pet = getPet($dbConn, $petId);
    echo json_encode($pet);
}

if(preg_match("/pet\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get Pet");

    $petId = $matches[1];
    $pet = getPet($dbConn, $petId);

    echo json_encode($pet);
}

if(preg_match("/pet\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $petId = $matches[1];
    error_log("Delete Pet: ". $petId);
    $deletedCount = deletePet($dbConn, $petId);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'id'=> $petId,
        'deleted'=> $deleted
    ]);
}

/**
 * Get record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getPet($db, $id) {
    $statement = $db->prepare("SELECT * FROM pet WHERE PetId=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Delete record based on ID
 *
 * @param $db
 * @param $id
 * 
 * @return integer number of deleted records
 */
function deletePet($db, $id) {
    $sql = "DELETE FROM pet WHERE PetId=:id";
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllPets($db) {
    $statement = $db->prepare("SELECT * FROM pet");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

/**
 * Get all characters who own the pet
 *
 * @param $db
 * @param $houId
 * @return mixed fetchAll result
 */
function getCharacters($db, $petId)
{
    $statement = $db->prepare("SELECT p.*, c.CharId, c.Name, c.Surname 
                                FROM hpcharacter c INNER JOIN student s ON c.CharId = s.CharId
                                INNER JOIN pet p ON s.PetId = p.PetId
                                WHERE p.PetId = $petId");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

/**
 * Add record
 *
 * @param $input
 * @param $db
 * @return integer id of the inserted record
 */
function addPet($input, $db){

    $sql = "INSERT INTO pet 
          (PetName) 
          VALUES 
          (:PetName)";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);

    $statement->execute();

    return $db->lastInsertId();
}

/**
 * @param $statement
 * @param $params
 * @return PDOStatement
 */
function bindAllValues($statement, $params){
    $allowedFields = ['PetName'];

    foreach($params as $param => $value){
        if(in_array($param, $allowedFields)){
            error_log("bind $param $value");
            $statement->bindValue(':'.$param, $value);
        }
    }
    return $statement;
}

/**
 * Get fields as parameters to set in record
 *
 * @param $input
 * @return string
 */
function getParams($input) {
    $allowedFields = ['PetName'];

    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
                $filterParams[] = "$param=:$param";
        }
    }

    return implode(", ", $filterParams);
}


/**
 * Update Record
 *
 * @param $input
 * @param $db
 * @param $id
 * @return integer number of updated records
 */
function updatePet($input, $db, $id){

    $fields = getParams($input);

    $sql = "
          UPDATE pet 
          SET $fields 
          WHERE PetId=$id
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $id;
}



