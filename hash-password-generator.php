<?php
$password1 = "@custodian01";
$password2 = "@head01";

$hash1 = password_hash($password1, PASSWORD_DEFAULT);
$hash2 = password_hash($password2, PASSWORD_DEFAULT);


?>


<div>Hash for '@custodian01': <?= $hash1 ?></div>
<div>Hash for '@head01': <?= $hash2 ?></div>