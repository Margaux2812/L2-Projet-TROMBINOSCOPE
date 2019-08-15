<?php
	if(!empty($_POST["logout"])) {
	session_destroy();
	header('Location: ../index.php');
}
?>