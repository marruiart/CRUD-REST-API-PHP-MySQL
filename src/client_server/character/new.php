<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">

	<h2 class="--header_c --uppercase">New character</h2>

	<?php
	require_once "../config.php";

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$apiUrl = $webServer . '/character';

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_POST, 1);

		curl_setopt(
			$ch,
			CURLOPT_POSTFIELDS,
			http_build_query($_POST)
		);

		// Receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec($ch);

		curl_close($ch);

		$character = json_decode($server_output);
		$_GET["id"] = $character->CharId;

		include("detail.php");
	} else {
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
					<label for="Name" class="label">Name:</label>
					<input type="text" id="Name" class="input --display" name="Name" autocomplete="off" required>
				</div>
				<div class="form__div--horizontal">
					<label for="Surname" class="label">Surname:</label>
					<input type="text" id="Surname" class="input --display" name="Surname" autocomplete="off" required>
				</div>
				<div class="form__div--horizontal">
					<label for="Birth" class="label">Birth:</label>
					<input type="date" id="Birth" class="input --display" name="Birth" autocomplete="off" required>
				</div>
				<div class="form__div--horizontal">
					<label for="Sex" class="label">Sex:</label>
					<select id="Sex" class="input --display" name="Sex" required>
						<?php
						$sexes = ['Male', 'Female'];
						echo "<option value=''>---</option>";
						foreach ($sexes as $sex) {
							echo "<option value=$sex> $sex </option>";
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
							echo "<option value=$type> " . ucfirst($type) . " </option>";
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
							echo "<option value=$house->HouId> $house->HouName </option>";
						}
						?>
					</select>
				</div>
				<div class="form__div--horizontal">
					<label for="PetId" class="label">Pet:</label>
					<select id="PetId" class="input --display" name="PetId">
						<?php
						echo "<option value=''>---</option>";
						foreach ($pets as $pet) {
							echo "<option value=$pet->PetId> $pet->PetName </option>";
						}
						?>
					</select>
				</div>
				<div class="form__div--horizontal">
					<label for="SubId" class="label">Subject:</label>
					<select id="SubId" class="input --display" name="SubId">
						<?php
						echo "<option value=''>---</option>";
						foreach ($subjects as $subject) {
							echo "<option value=$subject->SubId> $subject->SubName </option>";
						}
						?>
					</select>
				</div>

			</div>
			<div class="div__buttons">
				<button class="button" type="submit">Save</button>
			</div>
		</form>
	<?php
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