<?php
function show_calender($target, $maxitem)
{
    $pdo = db_access();
    $sql = "SELECT * FROM " . DB_PREFIX . "tweet WHERE category = '$target' ORDER BY updatetime DESC LIMIT $maxitem";
    $result = db_prepare_sql($sql, $pdo);

    foreach ($result as $row) {
        $num = $row['num'];
        $private = $row['privatepublic'];
        if (login() != true) {
            if ($private != true) {
                continue;
            }
        }

        $private_color = "";
        if ($private != true) {
            $private_color = "tweet_private";
        }

        $usericon = icon_get($row['author']);

        echo "<div class='tweet_box $private_color'>
        <div class='user_info'>
            <img src='./images/{$usericon}' class='usericon' />
            <label class='edit_cat' id='cat$num'>{$row['category']}</label>
            <label class='edit_private' id='prv$num'>{$row['privatepublic']}</label>
            <label class='username' id='aut$num'>{$row['author']}</label>
            ";

        if (login() == $row['author'] || $_SESSION['admin']) {
            echo "<a id='edit$num' class='edit'>…</a>";
        }

        if (check_admin($row['author'])) {
            echo "<label class='crown'>crown</label>";
        }

        echo "</div>";

        $text = nl2br(un_enc($row['item']));
        echo "<div class='tweet_item' id='text$num'>{$text}</div>";
        echo "<div class='tweet_item'>";
        $item_calender = $row['etc'];
        if ($item_calender != '') {
            $json = json_decode(un_enc($item_calender), true);
            if ($json != null) {
                $workingdays = $json['workingday'];
                $year = $json['year'];
                $month = $json['month'];
                $targetname = $json['target'];
                $startDate = get_beginning_month_date($year, $month);
                $endDate = get_ending_month_date($year, $month);
                $targetMonth = sprintf('%04d%02d', $year, $month);

                echo "{$targetname}の{$month}月の購買スケジュール                    
                    <div class='calender_box'>";
                show_calender_header();
                show_calender_date($startDate, $endDate, $targetMonth, $workingdays);
                echo "</div>";
            }
        }
        echo "<div id='json$num' class='hide_json'>" . $item_calender . "</div></div><label class='updatetime'>" . un_enc($row['updatetime']) . "</label></div>";
    }
}

function show_navigation()
{
    echo "<nav>
<a class='navbt nav01'>豊田北高校</a>
<a class='navbt nav02'>豊田西高校</a>
<a class='navbt nav03'>豊田東高校</a>
</nav>";
}

function show_calender_date($start, $end, $targetMonth, $workingdays)
{
    $today = date("Y-m-d");
    $endDate = date("Ymd", strtotime($end));

    for ($i = 0; $i < 50; $i++) {
        $date = date("Y-m-d", strtotime("$i day", strtotime($start)));
        $day =  date("d", strtotime($date));
        $month = date("Ym", strtotime($date));

        if ($targetMonth != $month) {
            echo "<div class='calender_td thismonth'>$day</div>"; // 当月以外は灰色
        } else {
            if ($workingdays[$day] > 0) {
                echo "<div class='calender_td working'>$day</div>";
            } else if ($date == $today) {
                echo "<div class='calender_td today'>$day</div>";
            } else if ($i % 7 == 5) {
                echo "<div class='calender_td holiday'>$day</div>";
            } else if ($i % 7 == 6) {
                echo "<div class='calender_td holiday'>$day</div><br clear='all' />";
            } else {
                echo "<div class='calender_td'>$day</div>";
            }
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

?>

<!-- EOF -->