<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/subject/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$subject = json_decode($json);
curl_close($curl);
?>

<form class="form">
    <div class="form__div">
        <label for="SubId" class="label">Id:</label>
        <input id="SubId" class="input --display" type="text" name="SubId" value="<?= $subject->SubId ?>" disabled>
    </div>
    <div class="form__div">
        <label for="SubName" class="label">Subject:</label>
        <input id="SubName" class="input --display" type="text" name="SubName" value="<?= $subject->SubName ?>" disabled>
    </div>
</form>