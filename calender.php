<?php
function show_calender()
{
    $year = date('Y');
    $month = date('m');
    $startDate = get_beginning_month_date($year , $month);
    $endDate = get_ending_month_date($year , $month);

    echo "<div class='calender_box'><h1>{$month}月の購買スケジュール</h1>";
    show_calender_header();
    show_calender_date($startDate, $endDate);
    echo "</div>";
}

function show_calender_date($start, $end)
{
    $today = date("Y-m-d");
    $thismonth = date("Ym");

    $endDate = date("Ymd", strtotime($end));
    for ($i = 0; $i < 100; $i++) {
        $date = date("Y-m-d", strtotime("$i day", strtotime($start)));
        $day =  date("d", strtotime($date));
        $month = date("Ym", strtotime($date));

        if ($thismonth != $month) {
            echo "<div class='calender_td thismonth'>$day</div>";
        } else if ($date == $today) {
            echo "<div class='calender_td today'>$day</div>";
        } else if ($i % 7 == 5) {
            echo "<div class='calender_td holiday'>$day</div>";
        } else if ($i % 7 == 6) {
            echo "<div class='calender_td holiday'>$day</div><br clear='all' />";
        } else {
            echo "<div class='calender_td'>$day</div>";
        }

        if (date("Ymd", strtotime("$i day", strtotime($start))) == $endDate) return;
    }
}

function get_beginning_month_date($year, $month)
{
    # 月初の週の月曜日を取得
    $ymd = date("$year-$month-01");
    $w = date("w", strtotime($ymd)) - 1;
    $beginning_week_date =
        date('Y-m-d', strtotime("-{$w} day", strtotime($ymd)));
    return $beginning_week_date;
}

function get_ending_month_date($year, $month)
{
    # 月末の週の日曜日を取得
    $ymd = date("$year-$month-t");
    $w = date("w", strtotime($ymd)) - 7;
    $ending_week_date =
        date('Y-m-d', strtotime("-{$w} day", strtotime($ymd)));
    return $ending_week_date;
}

function show_calender_header()
{
    echo "<div class='calender_th'>月</div>
<div class='calender_th'>火</div>
<div class='calender_th'>水</div>
<div class='calender_th'>木</div>
<div class='calender_th'>金</div>
<div class='calender_th'>土</div>
<div class='calender_th'>日</div>
<br clear='all' />";
}
