<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">
    <h2 class="--header_c --uppercase">Character details</h2>

    <?php
    include("detail.php");
    ?>

    <div class="div__buttons">
        <a href='/' class="button">Back</a>
    </div>
</main>
</div>
</body>

</html>