<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";
$subId = isset($_GET['SubId']) ? $_GET['SubId'] : null;
$header = "Harry Potter";
$title = "Subjects list";
if ($subId != null) {
    $title .= " for subject " . $subId;
}

include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
include $_SERVER['DOCUMENT_ROOT'] . "/menu.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase"><?= $title ?></h2>

    <table class="table--medium">
        <thead class="--bg_enfasis_c --white">
            <tr>
                <td>Subject</td>
                <td>Professor</td>
            </tr>
        </thead>

        <?php

        $apiUrl = $webServer . '/professor-subject';
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $subjects = json_decode($json);
        curl_close($curl);

        foreach ($subjects as $subject) {
        ?>
            <tr>
                <td><?= $subject->SubName ?></td>
                <td><?= $subject->Name . " " . $subject->Surname ?></td>

            </tr>
        <?php
        }
        ?>
    </table>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>