<?php
require_once "./head.php";
require_once "./tweet.php";
require_once "./calender.php";
?>
<body>

<?php
show_tweet();
show_tweet();
show_tweet();
?>
<div class='tweet_box'>
<div class='user_info'>
    <img src='./images/test.jpg' class='usericon' />
    <label class='username'>kanna</label>
    </div>
    <p class='text'>10月の購買スケジュール
    </p>
    <?php
show_calender();
?>
    <label class='updatetime'>2022/10/02</label>
</div>

<footer>
    <a href="#" class="icon_home">avv</a>
    <a href="#" class="icon_calender">geg</a>
    <a href="#" class="icon_key">a</a>
</footer>
</body>
</html>
<!-- end   -->