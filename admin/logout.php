<?php
// --------------------------------------------------------
//
// j0rpi_GameDB
//
// File: admin/logout.php
// Purpose: Kill session
//
// --------------------------------------------------------

session_start();
session_unset();
session_destroy();

header('Location: login.php');
exit;
?>