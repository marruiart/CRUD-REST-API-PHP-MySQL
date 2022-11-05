<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/character/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$character = json_decode($json);
curl_close($curl);
?>

<form class="form --horizontal">
    <div class="div--horizontal">
        <div class="form__div--horizontal">
            <label for="CharId" class="label">Id:</label>
            <input type="text" id="CharId" class="input --display" name="CharId" value="<?= $character->CharId ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="Name" class="label">Name:</label>
            <input type="text" id="Name" class="input --display" name="Name" value="<?= $character->Name ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="Surname" class="label">Surname:</label>
            <input type="text" id="Surname" class="input --display" name="Surname" value="<?= $character->Surname ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="Birth" class="label">Birth:</label>
            <input type="text" id="Birth" class="input --display" name="Birth" value="<?= $character->Birth ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="Sex" class="label">Sex:</label>
            <input type="text" id="Sex" class="input --display" name="Sex" value="<?= $character->Sex ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="Type" class="label">Type:</label>
            <input type="text" id="Type" class="input --display" name="Type" value="<?= ucfirst($character->Type) ?>" disabled>
        </div>
        <div class="form__div--horizontal">
            <label for="HouName" class="label">House:</label>
            <input type="text" id="HouName" class="input --display" name="HouName" value="<?= $character->HouName ?>" disabled>
        </div>
        <?php
        if ($character->Type == 'student') {
        ?>
            <div class="form__div--horizontal">
                <label for="PetName" class="label">Pet:</label>
                <input type="text" id="PetName" class="input --display" name="PetName" value="<?= $character->PetName ?>" disabled>
            </div>
    </div>
<?php
        } else if ($character->Type == 'professor') {
?>
    <div class="form__div--horizontal">
        <label for="SubName" class="label">Subject:</label>
        <input type="text" id="SubName" class="input --display" name="SubName" value="<?= $character->SubName ?>" disabled>
    </div>
    </div>
<?php
        }
?>
</form>