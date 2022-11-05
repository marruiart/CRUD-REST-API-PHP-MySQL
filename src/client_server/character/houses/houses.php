<?php
$apiUrl = $webServer . '/house/' . $id . '/character';
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$characters = json_decode($json);
curl_close($curl);

$count = 0;
foreach ($characters as $character) {
    if ($character->HouId == $house->HouId)
        $count += 1;
}

if ($count == 0)
    $title = "No character belong to " . $house->HouName;
else if ($count == 1)
    $title = "$count character belongs to " . $house->HouName;
else
    $title = "$count characters belong to " . $house->HouName;
?>

<h2 class="--header_c --uppercase"><?= $title ?></h2>
<?php
if ($count != 0) {
?>
    <table class="table--small">
        <thead class="--bg_enfasis_c --white">
            <tr>
                <td class="td--small">Id</td>
                <td>Full name</td>
                <td class="td--small"></td>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($characters as $character) {
                include $_SERVER['DOCUMENT_ROOT'] . "/character/houses/list.php";
            }
            ?>
        </tbody>
    </table>
<?php
}
?>