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

if ($url == '/character' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List students");
    $students = getAllStudents($dbConn);
    $professors = getAllProfessors($dbConn);
    $characters = array_merge($students, $professors);
    echo json_encode($characters);
}

if ($url == '/character' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create Character");
    $input = $_POST;
    $charId = addCharacter($input, $dbConn);
    if ($charId) {
        $input['CharId'] = $charId;
        $input['link'] = "/character/$charId";
    }

    echo json_encode($input);
}

if (preg_match("/character\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    error_log("UPDATE hpcharacter");

    $input = $_GET;
    $charId = $matches[1];
    updateCharacter($input, $dbConn, $charId);

    $character = getCharacter($dbConn, $charId);
    echo json_encode($character);
}

if (preg_match("/character\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Get Character");

    $charId = $matches[1];
    $character = getCharacter($dbConn, $charId);

    echo json_encode($character);
}

if (preg_match("/character\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $charId = $matches[1];
    error_log("Delete Character: " . $charId);
    $deletedCount = deleteCharacter($dbConn, $charId);
    $deleted = $deletedCount > 0 ? "true" : "false";

    echo json_encode([
        'id' => $charId,
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
function getCharacter($db, $id)
{
    $type = getCharType($db, $id);
    if ($type == 'student') {
        $table = 'pet';
        $table_id = 'PetId';
    } else if ($type == 'professor') {
        $table = 'subject';
        $table_id = 'SubId';
    }

    $statement = $db->prepare("SELECT * FROM hpcharacter c 
    LEFT JOIN house h ON (c.HouId = h.HouId) 
    LEFT JOIN $type typ ON (c.CharId = typ.CharId)
    LEFT JOIN $table tab ON (tab.$table_id = typ.$table_id)
    WHERE c.CharId=:id");

    $statement->bindValue(':id', $id);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

function getCharType($db, $id)
{
    $sql = "SELECT Type FROM hpcharacter WHERE CharId=$id";
    $statement = $db->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();
    return $statement->fetch()["Type"];
}

/**
 * Delete record based on ID
 *
 * @param $db
 * @param $id
 * 
 * @return integer number of deleted records
 */
function deleteCharacter($db, $id)
{
    $type = getCharType($db, $id);

    $sql = "DELETE FROM $type WHERE CharId=:id;
    DELETE FROM hpcharacter WHERE CharId=:id";
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
function getAllStudents($db)
{
    $statement = $db->prepare("SELECT * FROM hpcharacter c 
    INNER JOIN student st ON (c.CharId = st.CharId) 
    INNER JOIN pet pe ON (pe.PetId = st.PetId)
    LEFT JOIN house h ON (c.HouId = h.HouId)");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

function getAllProfessors($db)
{
    $statement = $db->prepare("SELECT * FROM hpcharacter c 
    INNER JOIN professor pr ON (c.CharId = pr.CharId) 
    LEFT JOIN subject su ON (su.SubId = pr.SubId)
    LEFT JOIN house h ON (c.HouId = h.HouId)");
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
function addCharacter($input, $db)
{

    $sql = "INSERT INTO hpcharacter 
          (Name, Surname, Sex, HouId, Birth, Type) 
          VALUES (:Name, :Surname, :Sex, :HouId, :Birth, :Type)";
    $statement = $db->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();

    $charId = $db->lastInsertId();

    if ($input["Type"] == "student")
        $field = "PetId";
    else
        $field = "SubId";

    $subsql = "INSERT INTO " . $input["Type"] . " 
            (CharId, $field) 
            VALUES ($charId, :$field)";

    $substatement = $db->prepare($subsql);
    bindTypeValues($substatement, $input);

    $substatement->execute();

    return $charId;
}

/**
 * @param $statement
 * @param $params
 * @return PDOStatement
 */
function bindAllValues($statement, $params)
{
    $allowedFields = ['Name', 'Surname', 'Sex', 'HouId', 'Birth', 'Type'];

    foreach ($params as $param => $value) {
        if (in_array($param, $allowedFields)) {
            if ($value == '' && $param == 'HouId')
                $value = NULL;
            error_log("bind $param $value");
            $statement->bindValue(':' . $param, $value);
        }
    }

    return $statement;
}

function bindTypeValues($statement, $params)
{
    if ($params["Type"] == 'student')
        $allowedFields = ['PetId'];
    else
        $allowedFields = ['SubId'];

    foreach ($params as $param => $value) {
        if (in_array($param, $allowedFields)) {
            if ($value == '' && $param == 'SubId')
                $value = NULL;
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
    $allowedFields = ['Name', 'Surname', 'Sex', 'HouId', 'Birth', 'Type'];

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
function updateCharacter($input, $db, $id)
{

    $fields = getParams($input);

    $sql = "UPDATE hpcharacter 
            SET $fields 
            WHERE CharId=$id";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    if ($input["Type"] == "student")
        $field = "PetId";
    else {
        $field = "SubId";
    }

    $subsql = "UPDATE " . $input["Type"] . "
                SET $field =" . $input["$field"] . " 
                WHERE CharId=$id";
    $substatement = $db->prepare($subsql);

    $substatement->execute();

    return $id;
}
