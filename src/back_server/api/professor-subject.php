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

if ($url == '/professor-subject' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List subjects");
    $subjects = getAllProfessorSubjects($dbConn);
    echo json_encode($subjects);
}

if ($url == '/professor-subject' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create ProfessorSubject");
    $input = $_POST;
    $subId = addProfessorSubject($input, $dbConn);
    if ($subId) {
        $input['SubId'] = $subId;
        $input['link'] = "/professor-subject/$subId";
    }

    echo json_encode($input);
}

if (preg_match("/professor-subject\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    error_log("Update ProfessorSubject");

    $input = $_GET;
    $subId = $matches[1];
    updateProfessorSubject($input, $dbConn, $subId);

    $subject = getProfessorSubject($dbConn, $subId);
    echo json_encode($subject);
}

if (preg_match("/professor-subject\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Get ProfessorSubject");

    $subId = $matches[1];
    $subject = getProfessorSubject($dbConn, $subId);

    echo json_encode($subject);
}

if (preg_match("/professor-subject\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE') {

    $subId = $matches[1];
    error_log("Delete ProfessorSubject: " . $subId);
    $deletedCount = deleteProfessorSubject($dbConn, $subId);
    $deleted = $deletedCount > 0 ? "true" : "false";

    echo json_encode([
        'id' => $subId,
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
function getProfessorSubject($db, $id)
{
    $statement = $db->prepare("SELECT * FROM subject WHERE SubId=:id");
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
function deleteProfessorSubject($db, $id)
{
    $sql = "DELETE FROM subject WHERE SubId=:id";
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
function getAllProfessorSubjects($db)
{
    $statement = $db->prepare("SELECT s.*, c.Name, c.Surname
    FROM subject s LEFT JOIN professor p ON p.SubId = s.SubId
    LEFT JOIN hpcharacter c ON c.CharId = p.CharId");
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
function addProfessorSubject($input, $db)
{

    $sql = "INSERT INTO subject 
          (SubName) 
          VALUES 
          (:SubName)";

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
    $allowedFields = ['SubName'];

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
    $allowedFields = ['SubName'];

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
function updateProfessorSubject($input, $db, $id)
{

    $fields = getParams($input);

    $sql = "
          UPDATE subject 
          SET $fields 
          WHERE SubId=$id
           ";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $id;
}
