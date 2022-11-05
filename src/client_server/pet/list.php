<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
$petId = isset($_GET['PetId']) ? $_GET['PetId'] : null;
$header = "Harry Potter";
$title = "Pets list";
if ($petId != null) {
    $title .= " for pet " . $petId;
}

include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
include $_SERVER['DOCUMENT_ROOT'] . "/menu.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase"><?= $title ?></h2>

    <table class="table--small">
        <thead class="--bg_enfasis_c --white">
            <tr>
                <td>Pet</td>
                <td class="td--small"></td>
                <td class="td--small"></td>
                <td class="td--small"></td>
            </tr>
        </thead>

        <?php

        $apiUrl = $webServer . '/pet';
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $pets = json_decode($json);
        curl_close($curl);

        foreach ($pets as $pet) {
        ?>
            <tr>
                <td><?= $pet->PetName ?></td>
                <td class="td--small --text_center"><a href="/pet/view.php?id=<?= $pet->PetId ?>" class="--header_c">
                        <i class="fa-solid fa-eye"></i></a> </td>
                <td class="td--small --text_center"><a href="/pet/edit.php?id=<?= $pet->PetId ?>" class="--header_c">
                        <i class="fa-solid fa-pen"></i></a> </td>
                <td class="td--small --text_center"><a href="/pet/delete.php?id=<?= $pet->PetId ?>" class="--header_c">
                        <i class="fa-regular fa-trash-can"></i></a> </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <a class="button" href="/pet/new.php<?= $petId != null ? '?PetId=' . $petId : '' ?>">New pet</a>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>