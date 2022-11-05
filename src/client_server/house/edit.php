<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase">Edit house</h2>

    <?php
    require_once "../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_GET['id'];
        $apiUrl = $webServer . '/house/' . $id;
        $params = array(
            "HouName"   => $_POST['HouName'],
        );
        $apiUrl .= "?" . http_build_query($params);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $result = json_decode($server_output);

        include("detail.php");
    ?>
        <div class="div__buttons">
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/house/list.php" class="button">Back</a>
            <a href="/house/edit.php?id=<?= $house->HouId ?>" class="button"> <i class="fa-solid fa-pen"></i> &nbsp;Edit </a>
        </div>
    <?php
    } else {
        $id = $_GET["id"];
        $apiUrl = $webServer . '/house/' . $id;
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $house = json_decode($json);
        curl_close($curl);
    ?>
        <form class="form" method="POST">
            <div class="form__div">
                <label for="HouId" class="label">Id:</label>
                <input type="text" id="HouId" class="input --display" name="HouId" value="<?= $house->HouId ?>" disabled>
            </div>
            <div class="form__div">
                <label for="HouName" class="label">House:</label>
                <input type="text" id="HouName" class="input --display" name="HouName" value="<?= $house->HouName ?>">
            </div>
            <div class="div__buttons">
                <button class="button" type="submit">Save</button>
            </div>
        </form>
        <div class="div__buttons">
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/house/list.php" class="button">Back</a>
        </div>
    <?php
    }
    ?>

</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>