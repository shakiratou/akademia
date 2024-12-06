<?php
session_start();
session_destroy();
header('Location: login/index.php'); // Redirigez après déconnexion
exit;
?>
