<tr>
    <td class="td--small --text_center"><?= $character->CharId ?></td>
    <td><?= $character->Name . " " . $character->Surname ?></td>

    <td class="td--small --text_center"><a href="/character/view.php?id=<?= $character->CharId ?>" class="--header_c">
            <i class="fa-solid fa-eye"></i></a></td>
</tr>