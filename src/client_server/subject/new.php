<?php
$header = "Harry Potter";
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
?>

<main class="main --bg_light_bg_c">

	<h2 class="--header_c --uppercase">New subject</h2>

	<?php
	require_once "../config.php";

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$apiUrl = $webServer . '/subject';

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

		$subject = json_decode($server_output);
		$_GET["id"] = $subject->SubId;

		include("detail.php");
	} else {
	?>
		<form class="form" method="POST">
			<div class="form__div">
				<label for="SubName" class="label">Subject name:</label>
				<input type="text" id="SubName" class="input --display" name="SubName">
			</div>
			<div class="div__buttons">
				<button class="button" type="submit">Save</button>
			</div>
		</form>
	<?php
	}
	?>

	<div class="div__buttons">
		<a href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/subject/list.php" class="button">Back</a>
	</div>

</main>
<footer class="footer --bg_header_c"></footer>
</div>
</body>

</html>