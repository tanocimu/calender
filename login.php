<?php
require_once("./config.php");
require_once "./head.php";

take_submit();
usericon_change();
show_login_form();

if ($_SESSION['admin']) {
    show_signupform();
}

require_once("./footer.php");
?>
<!-- EOF -->