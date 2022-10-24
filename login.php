<?php
require_once("./config.php");
require_once "./head.php";
?>
<style>
    .login_box {
        width: calc(100% - 100px);
        margin: auto;
    }

    .login_box input {
        width: 100%;
        height: 36px;
        border-radius: 18px;
        padding: 0px 20px;
        box-sizing: border-box;
        margin: auto;
        margin-bottom: 40px;
    }

    .login_box label {
        display: block;
        width: 100%;
        text-align: center;
        margin-bottom: 50px;
    }

    .login_usericon {
        display: block;
        margin: auto;
        margin-bottom: 20px;
        width: 15vw;
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        border: 0;
        box-sizing: border-box;
    }

    .iconchange_form {
        position: relative;
        background: #ffffff;
        margin: 10px auto;
        padding: 20px 40px;
        width: calc(100% - 40px);
        border-radius: 10px;
        height: 150px;
        box-sizing: border-box;
        text-align: center;
    }

    .iconchange_form .iconchange {
        position: absolute;
        top: 70px;
        right: 50px;
        width: 100px;
        height: 26px;
        border-radius: 13px;
        color: #ffffff;
        background: rgb(89, 179, 112);
    }

    .iconchange_form input[type="file"] {
        text-indent: -9999px;
        position: absolute;
        top:50px;
        left:60px;
        background: url('./parts/file.png') no-repeat center;
        background-size: 30px 26px;
        border: solid 1px rgb(199, 199, 199);
        width: 70px;
        height: 70px;
        border-radius: 10px;
    }
</style>
<?php
take_submit();
usericon_change();
show_login_form();
require_once("./footer.php");
?>
<!-- EOF -->