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

    <title>かんなの購買</title>

    <link rel="stylesheet" href="./css/reset.css">
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+1p" rel="stylesheet">
    <link href="https://fonts.googleapis.com/earlyaccess/nikukyu.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style_tweet.css">
</head>

<body>
    <?php
    if (login()) {
        show_tweet_form();
    } ?>