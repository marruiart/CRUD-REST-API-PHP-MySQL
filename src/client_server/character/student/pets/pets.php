<?php
$apiUrl = $webServer . '/pet/' . $id . '/character';

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$characters = json_decode($json);
curl_close($curl);

$count = 0;
foreach ($characters as $character) {
    if ($character->PetId == $pet->PetId)
        $count += 1;
}

$n = preg_match('/[a,e,i,o,u]/i', $pet->PetName[0]) ? "n" : "";

if ($count == 0)
    $title = "No student has a$n " . $pet->PetName;
else if ($count == 1)
    $title = "$count student has a$n " . $pet->PetName;
else
    $title = "$count students have a$n " . $pet->PetName;
?>
<h2 class="--header_c --uppercase"><?= $title ?></h2>
<?php
if ($count != 0) {
?>
    <table class="table--medium">
        <thead class="--bg_enfasis_c --white">
            <tr>
                <td class="td--small">Id</td>
                <td>Full name</td>
                <td>House</td>
                <td class="td--small"></td>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($characters as $character) {
                include $_SERVER['DOCUMENT_ROOT'] . "/character/student/pets/list.php";
            }
            ?>
        </tbody>
    </table>
<?php
}
?>