<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">

    <h2 class="--header_c --uppercase">Edit character</h2>

    <?php
    require_once "../config.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id = $_GET['id'];
        $apiUrl = $webServer . '/character/' . $id;

        $params = array(
            "Name" => $_POST['Name'],
            "Surname" => $_POST['Surname'],
            "Birth" => $_POST['Birth'],
            "Sex" => $_POST['Sex'],
            "HouId" => $_POST['HouId'],
            "Type" => $_POST['Type'],
            "PetId" => $_POST['PetId'],
            "SubId" => $_POST['SubId']
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
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php" class="button">Back</a>
            <a href="/character/edit.php?id=<?= $character->CharId ?>" class="button"> <i class="fa-solid fa-pen"></i> &nbsp;Edit </a>
        </div>
    <?php
    } else {
        $id = $_GET["id"];
        $apiUrl = $webServer . '/character/' . $id;
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $character = json_decode($json);
        curl_close($curl);

        $apiUrl = $webServer . '/house';
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $houses = json_decode($json);
        curl_close($curl);

        $apiUrl = $webServer . '/pet';
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $pets = json_decode($json);
        curl_close($curl);

        $apiUrl = $webServer . '/subject';
        $curl = curl_init($apiUrl);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $subjects = json_decode($json);
        curl_close($curl);

    ?>

        <form class="form --horizontal" method="POST">
            <div class="div--horizontal">
                <div class="form__div--horizontal">
                    <label for="CharId" class="label">Id:</label>
                    <input type="text" id="CharId" class="input --display" name="CharId" value="<?= $character->CharId ?>" disabled>
                </div>
                <div class="form__div--horizontal">
                    <label for="Name" class="label">Name:</label>
                    <input type="text" id="Name" class="input --display" name="Name" value="<?= $character->Name ?>" required>
                </div>
                <div class="form__div--horizontal">
                    <label for="Surname" class="label">Surname:</label>
                    <input type="text" id="Surname" class="input --display" name="Surname" value="<?= $character->Surname ?>" required>
                </div>
                <div class="form__div--horizontal">
                    <label for="Birth" class="label">Birth:</label>
                    <input type="text" id="Birth" class="input --display" name="Birth" value="<?= $character->Birth ?>" required>
                </div>
                <div class="form__div--horizontal">
                    <label for="Sex" class="label">Sex:</label>
                    <select id="Sex" class="input --display" name="Sex" required>
                        <?php
                        $sexes = ['Male', 'Female'];
                        echo "<option value=''>---</option>";
                        foreach ($sexes as $sex) {
                            $selected = $character->Sex == $sex ? "selected" : "";
                            echo "<option value=$sex $selected> $sex </option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form__div--horizontal">
                    <label for="Type" class="label">Type:</label>
                    <select id="Type" class="input --display" name="Type" required>
                        <?php
                        $types = ['student', 'professor'];
                        echo "<option value=''>---</option>";
                        foreach ($types as $type) {
                            $selected = $character->Type == $type ? "selected" : "";
                            echo "<option value=$type $selected> " . ucfirst($type) . " </option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form__div--horizontal">
                    <label for="HouId" class="label">House:</label>
                    <select id="HouId" class="input --display" name="HouId">
                        <?php
                        echo "<option value=''>---</option>";
                        foreach ($houses as $house) {
                            $selected = $character->HouId == $house->HouId ? "selected" : "";
                            echo "<option value=$house->HouId $selected> $house->HouName </option>";
                        }
                        ?>
                    </select>
                </div>
                <?php
                if ($character->Type == 'student') {
                ?>
                    <div class="form__div--horizontal">
                        <label for="PetId" class="label">Pet:</label>
                        <select id="PetId" class="input --display" name="PetId" required>
                            <?php
                            echo "<option value=''>---</option>";
                            foreach ($pets as $pet) {
                                $selected = $character->PetId == $pet->PetId ? "selected" : "";
                                echo "<option value=$pet->PetId $selected> $pet->PetName </option>";
                            }
                            ?>
                        </select>
                    </div>
            </div>
        <?php
                } else if ($character->Type == 'professor') {
        ?>
            <div class="form__div--horizontal">
                <label for="SubId" class="label">Subject:</label>
                <select id="SubId" class="input --display" name="SubId">
                    <?php
                    echo "<option value=NULL>---</option>";
                    foreach ($subjects as $subject) {
                        $selected = $character->SubId == $subject->SubId ? "selected" : "";
                        echo "<option value=$subject->SubId $selected> $subject->SubName </option>";
                    }
                    ?>
                </select>
            </div>
            </div>
        <?php
                }
        ?>
        <div class="div__buttons">
            <button class="button" type="submit">Save</button>
        </div>
        </form>
        <div class="div__buttons">
            <a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/index.php" class="button">Back</a>
        </div>
    <?php
    }
    ?>
</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>