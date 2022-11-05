<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/config.php";

$apiUrl = $webServer . '/character';

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING, "");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$characters = json_decode($json);
curl_close($curl);

include "student/table.php";
include "professor/table.php";
?>

<a class="button" href="/character/new.php<?= $charId != null ? '?CharId=' . $charId : '' ?>"><i class="fa-solid fa-user-plus"></i> &nbsp; New character</a>