<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/house/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$house = json_decode($json);
curl_close($curl);
?>

<form class="form">
    <div class="form__div">
        <label for="HouId" class="label">Id:</label>
        <input id="HouId" class="input --display" type="text" name="HouId" value="<?= $house->HouId ?>" disabled>
    </div>
    <div class="form__div">
        <label for="HouName" class="label">House:</label>
        <input id="HouName" class="input --display" type="text" name="HouName" value="<?= $house->HouName ?>" disabled>
    </div>
</form>

<?php
include $_SERVER['DOCUMENT_ROOT'] . "/character/houses/houses.php";
?>