<?php
require_once("./config.php");
require_once "./head.php";
?>

<div class='select_category_box'>
    <a id='cat_show1'>豊田東高校</a>
    <a id='cat_show2'>豊田西高校</a>
    <a id='cat_show3'>豊田北高校</a>
</div>

<div id='cat_content1'>
<?php show_calender('calenderhigashi', 3); ?>
</div>

<div id='cat_content2'>
<?php show_calender('calendernishi', 3); ?>
</div>

<div id='cat_content3'>
<?php show_calender('calenderkita', 3); ?>
</div>

<?php
require_once("./footer.php");
?>
<!-- EOF -->