<?php
require_once "./head.php";
?>

    <h1>かんなのこうばい</h1>
    <?php
    show_tweet('tweet', 10)
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

    <?php require_once "./footer.php"; ?>
    <!-- end   -->