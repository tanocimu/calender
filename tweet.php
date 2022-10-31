<?php
function show_tweet($category, $maxitem)
{
    $pdo = db_access();
    $query = "SELECT * FROM " . DB_PREFIX . "tweet WHERE category = '$category' ORDER BY " . DB_PREFIX . "tweet.updatetime DESC LIMIT $maxitem;";
    $result = db_prepare_sql($query, $pdo);
    db_close($pdo);

    foreach ($result as $row) {
        $num = $row['num'];
        $commentlist = show_comment($num);

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
        $text = nl2br(un_enc($row['item']));

        $tweet_add_comment = "";

        echo "
        <div class='tweet_box $private_color'>
        <div class='user_info'>
        <img src='./images/" . un_enc($usericon) . "' class='usericon' />
        <label class='edit_cat' id='cat$num'>{$row['category']}</label>
        <label class='edit_private' id='prv$num'>{$row['privatepublic']}</label>
        <label class='username' id='aut$num'>{$row['author']}</label>
        ";

        if (login() == $row['author'] || $_SESSION['admin']) {
            echo "<a id='edit$num' class='edit'>…</a>";
        }

        if (login()) {
            $tweet_add_comment = show_comment_form($num, $category);
        }

        if (check_admin($row['author'])) {
            echo "<label class='crown'>crown</label>";
        }

        echo "
        </div>
        <div class='tweet_item' id='text$num'>{$text}</div>
        ";

        if ($row['imageurl'] != "") {
            echo "
        <img src='./images/" . un_enc($row['imageurl']) . "' id='img$num' />
        ";
        }

        $item_calender = $row['etc'];
        echo "
        <div id='json$num' class='hide_json'>" . $item_calender . "</div>
        <label class='updatetime'>" . un_enc($row['updatetime']) . "</label>
        $tweet_add_comment
        $commentlist
        </div>";
    }
}

function show_comment($itemnum = 0)
{
    $commentlist = '';
    $pdo = db_access();
    $sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE lipnum = $itemnum";
    $result = db_prepare_sql($sql, $pdo);
    db_close($pdo);

    foreach ($result as $row) {
        $usericon = icon_get($row['author']);
        $crown = "";
        if (check_admin($row['author'])) {
            $crown = "<label class='crown'>crown</label>";
        }
        $commentlist .= "        
        <div class='comment_box'>
        <div class='user_info'>
        <img src='./images/" . un_enc($usericon) . "' class='usericon' />
        <label class='username'>{$row['author']}</label>
        </div><div class='tweet_comment'>" . $row['comment'] . "$crown</div></div>";
    }

    return $commentlist;
}

function show_comment_form($lipnum, $category)
{
    $author = $_SESSION['user_name'];
    $string = "
    <div class='tweet_add_comment'>
    <form id='commentform' method='post' action='" . $_SERVER['REQUEST_URI'] . "' enctype='multipart/form-data'>
    <input id='lipnum' type='hidden' name='lipnum' value='$lipnum'>
    <input id='category' type='hidden' name='category' value='$category'>
    <input id='author' type='hidden' name='author' value='$author'>
    <textarea id='comment' type='text' name='comment' value='' placeholder='コメントして盛り上げよう！' ></textarea>
    <button class='comment_submit' id='comment_submit' name='comment_submit' value='comment_submit'>返信</button>
</form></div>
    ";

    return $string;
}

function icon_get($username)
{
    $pdo = db_access();
    $query = "SELECT imageurl FROM " . DB_PREFIX . "user WHERE username='$username';";
    $userimage = db_prepare_sql($query, $pdo);
    db_close($pdo);

    // user icon get
    $usericon = "";
    foreach ($userimage as $row) {
        $usericon = $row['imageurl'];
    }

    return $usericon;
}

function show_tweet_form()
{
    $usericon = icon_get($_SESSION['user_name']);
    $author = $_SESSION['user_name'];
    echo "
    <div id='tweet_form' class='tweet_form'>
    <img src='./images/$usericon' class='form_usericon' />
    <form id='form' method='post' action='" . $_SERVER['REQUEST_URI'] . "' enctype='multipart/form-data'>
        <a id='tweet_picker_show' class='tweet_picker_show'>カレンダー</a>
        <input id='num' type='hidden' name='num' value=''>
        <input id='author' type='hidden' name='author' value='$author'>
        <select name='category' id='category'>
            <option value='0'>おしらせ</option>
            <option value='1'>豊田北高校</option>
            <option value='2'>豊田東高校</option>
            <option value='3'>豊田西高校</option>
        </select>
        <select name='privatepublic' id='privatepublic'>
            <option value='0'>非公開</option>
            <option value='1' selected>公開</option>
        </select>
        <textarea id='item' type='text' name='item' value='' placeholder='何かつぶやこう！' ></textarea>
        <textarea id='item_calender' type='text' name='item_calender' value='' ></textarea>
        <input class='inputimage' id='image' type='file' name='image[]' accept='image/*'>
        <input id='imageurl' type='hidden' name='imageurl'>
        <div id='preview'></div>
        <button class='submit' id='submit' name='submit' value='submit'>投稿する</button>
        <button class='cancel' id='cancel' name='cancel' value='cancel'>キャンセル</button>
        <a class='calender_delete' id='calender_delete'>📅削除</a>
    </form>
</div>
";
}
?>

<!-- EOF -->