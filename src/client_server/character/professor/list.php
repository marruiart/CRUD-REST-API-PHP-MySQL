<tr>
        <td><?= $character->Name ?></td>
        <td><?= $character->Surname ?></td>
        <td><?= $character->Sex ?></td>
        <td><?= $character->Birth ?></td>
        <td><?= $character->HouName ?></td>

        <td class="td--small --text_center"><a href="/character/view.php?id=<?= $character->CharId ?>" class="--header_c">
                        <i class="fa-solid fa-eye"></i></a></td>
        <td class="td--small --text_center"><a href="/character/edit.php?id=<?= $character->CharId ?>" class="--header_c">
                        <i class="fa-solid fa-pen"></i></a></td>
        <td class="td--small --text_center"><a href="/character/delete.php?id=<?= $character->CharId ?>" class="--header_c">
                        <i class="fa-regular fa-trash-can"></i></a>
        </td>
</tr>