<?php

session_start();

unset($_SESSION);
session_destroy();
echo "<meta http-equiv=\"Refresh\"content=\"0; URL=login.php\">";
exit;
?>