<?php
require_once "config.php";

$header = "Harry Potter";

include $_SERVER['DOCUMENT_ROOT'] . "/head.php";

include $_SERVER['DOCUMENT_ROOT'] . "/menu.php";
?>
        <main class="main --bg_light_bg_c">
            <?php
            include "character/list.php";
            ?>
        </main>
    </div>
</body>

</html>

