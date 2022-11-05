<?php
$title = "Professors list";
?>

<h2 class="--header_c --uppercase"><?= $title ?></h2>

<table>
    <thead class="--bg_enfasis_c --white">
        <tr>
            <td>Name</td>
            <td>Surname</td>
            <td>Sex</td>
            <td>Birth</td>
            <td>House</td>
            <td class="td--small"></td>
            <td class="td--small"></td>
            <td class="td--small"></td>
        </tr>
    </thead>

    <tbody>
        <?php
        foreach ($characters as $character) {
            if ($character->Type == 'professor')
                include "list.php";
        }
        ?>
    </tbody>
</table>