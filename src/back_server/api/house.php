<?php
$url = $_SERVER['REQUEST_URI'];
if (strpos($url, "/") !== 0) {
    $url = "/$url";
}

$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

header("Content-Type:application/json");
error_log("URL: " . $url);
error_log("METHOD: " . $_SERVER['REQUEST_METHOD']);

if ($url == '/house' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List houses");
    $houses = getAllHouses($dbConn);
    echo json_encode($houses);
}

if (preg_match("/house\/([0-9]+)\/character/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List house characters");

    $houId = $matches[1];
    $characters = getCharacters($dbConn, $houId);
    echo json_encode($characters);
    return;
}

if ($url == '/house' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create house");
    $input = $_POST;
    $houseId = addHouse($input, $dbConn);
    if ($houseId) {
        $input['HouId'] = $houseId;
        $input['link'] = "/pet/$houseId";
    }
    echo json_encode($input);
}

if (preg_match("/house\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    error_log("Update house");

    $input = $_GET;
    $houseId = $matches[1];
    updateHouse($input, $dbConn, $houseId);

    $house = getHouse($dbConn, $houseId);
    echo json_encode($house);
}

if (preg_match("/house\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Get house");

    $houseId = $matches[1];
    $house = getHouse($dbConn, $houseId);

    echo json_encode($house);
}

if (preg_match("/house\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $houseId = $matches[1];
    error_log("Delete house: " . $houseId);
    $deletedCount = deleteHouse($dbConn, $houseId);
    $deleted = $deletedCount > 0 ? "true" : "false";

    echo json_encode([
        'id' => $houseId,
        'deleted' => $deleted
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
function getHouse($db, $id)
{
    $statement = $db->prepare("SELECT * FROM house WHERE HouId=:id");
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
function deleteHouse($db, $id)
{
    $sql = "DELETE FROM house WHERE HouId=:id";
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
function getAllHouses($db)
{
    $statement = $db->prepare("SELECT * FROM house");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

/**
 * Get all characters of the house
 *
 * @param $db
 * @param $houId
 * @return mixed fetchAll result
 */
function getCharacters($db, $houId)
{
    $statement = $db->prepare("SELECT h.*, c.CharId, c.Name, c.Surname 
                                FROM house h INNER JOIN hpcharacter c ON c.HouId = h.HouId
                                WHERE h.HouId = $houId");
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
function addHouse($input, $db)
{

    $sql = "INSERT INTO house 
          (HouName) 
          VALUES 
          (:HouName)";
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
function bindAllValues($statement, $params)
{
    $allowedFields = ['HouName'];

    foreach ($params as $param => $value) {
        if (in_array($param, $allowedFields)) {
            error_log("bind $param $value");
            $statement->bindValue(':' . $param, $value);
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
function getParams($input)
{
    $allowedFields = ['HouName'];

    foreach ($input as $param => $value) {
        if (in_array($param, $allowedFields)) {
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
function updateHouse($input, $db, $id)
{

    $fields = getParams($input);

    $sql = "
          UPDATE house 
          SET $fields 
          WHERE HouId=$id
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $id;
}
