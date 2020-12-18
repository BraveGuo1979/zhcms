<?php
if (! isset($_SESSION)) {
	session_start();
}
$_SESSION['adminname'] = "";
Header("Location: /manager/index.html");