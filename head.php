<?php
session_start();
require_once("config.php");
require_once("db_write.php");
require_once("loginfunc.php");
require_once "./tweet.php";
require_once "./calenderfunc.php";
submit_recieve();
?>
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="Mountain Top Tourist Servicesの説明を記載">
    <meta charset="UTF-8">

    <meta property="og:title" content="Mountain Top Tourist Services">
    <meta property="og:site_name" content="Mountain Top Tourist Services">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https:///">
    <meta property="og:description" content="Mountain Top Tourist Servicesの説明を記載">


    <meta name="robots" content="noindex">

    <title>かんなのこうばい</title>

    <link rel="stylesheet" href="./css/reset.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p" rel="stylesheet">
    <link href="https://fonts.googleapis.com/earlyaccess/nikukyu.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style_tweet.css">
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/picker.css">
</head>

<body>
    <div id="picker_overlay" class="picker_overlay">
        <div class='picker_box'>
            <div class='picker_year'>
                <a id="year_previous">&lt;&nbsp;&nbsp;&nbsp;</a>
                <a id="year">2022</a>
                <a id="year_next">&nbsp;&nbsp;&nbsp;&gt;</a>
                <a id="picker_cancel">&times;</a>
            </div>
            <div id='picker_month' class='picker_month'>
                <a id="m01">1月</a>
                <a id="m02">2月</a>
                <a id="m03">3月</a>
                <a id="m04">4月</a>
                <a id="m05">5月</a>
                <a id="m06">6月</a>
                <a id="m07">7月</a>
                <a id="m08">8月</a>
                <a id="m09">9月</a>
                <a id="m10">10月</a>
                <a id="m11">11月</a>
                <a id="m12">12月</a>
            </div>
            <div id='picker_day' class='picker_day'>
            </div>
        </div>
    </div>
    <?php
    if (login()) {
        show_tweet_form();
    } ?>