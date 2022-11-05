<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
$houId = isset($_GET['HouId']) ? $_GET['HouId'] : null;
$header = "Harry Potter";
$title = "Houses list";
if ($houId != null) {
    $title .= " for house " . $houId;
}

include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
include $_SERVER['DOCUMENT_ROOT'] . "/menu.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase"><?= $title ?></h2>

    <table class="table--small">
        <thead class="--bg_enfasis_c --white">
            <tr>
                <td>House</td>
                <td class="td--small"></td>
                <td class="td--small"></td>
                <td class="td--small"></td>
            </tr>
        </thead>

        <?php

        if ($houId == null) {
            $apiUrl = $webServer . '/house';
        } else {
            $apiUrl = $webServer . '/house/' . $houId . "/character";
        }

        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $houses = json_decode($json);
        curl_close($curl);

        foreach ($houses as $house) {
        ?>
            <tr>
                <td><?= $house->HouName ?></td>
                <td class="td--small --text_center"><a href="/house/view.php?id=<?= $house->HouId ?>" class="--header_c">
                        <i class="fa-solid fa-eye"></i></a> </td>
                <td class="td--small --text_center"><a href="/house/edit.php?id=<?= $house->HouId ?>" class="--header_c">
                        <i class="fa-solid fa-pen"></i></a> </td>
                <td class="td--small --text_center"><a href="/house/delete.php?id=<?= $house->HouId ?>" class="--header_c">
                        <i class="fa-regular fa-trash-can"></i></a> </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <a class="button" href="/house/new.php<?= $houId != null ? '?HouId=' . $houId : '' ?>">New house</a>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>