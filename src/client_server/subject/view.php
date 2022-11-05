<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">
    <h2 class="--header_c --uppercase">Subject details</h2>

    <?php
    include("detail.php");
    ?>

    <div class="div__buttons">
        <a href="<?php $_SERVER['DOCUMENT_ROOT']?>/subject/list.php" class="button">Back</a>
    </div>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>