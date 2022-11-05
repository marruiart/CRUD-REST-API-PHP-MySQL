<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase">Delete character</h2>
    <?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/character/' . $id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);

$result = json_decode($server_output);

if ($result->deleted == "true") {
    echo "Character $id has been deleted";
} else {
    echo "ERROR: Can't delete character $id";
}

?>
    <div class="div__buttons">
        <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php" class="button">Back</a>
    </div>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>
