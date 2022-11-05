<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/pet/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$pet = json_decode($json);
curl_close($curl);
?>

<form class="form">
    <div class="form__div">
        <label for="PetId" class="label">Id:</label>
        <input id="PetId" class="input --display" type="text" name="PetId" value="<?= $pet->PetId ?>" disabled>
    </div>
    <div class="form__div">
        <label for="PetName" class="label">Pet:</label>
        <input id="PetName" class="input --display" type="text" name="PetName" value="<?= $pet->PetName ?>" disabled>
    </div>
</form>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/character/student/pets/pets.php";
?>