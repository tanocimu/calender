<?php
function show_tweet($category, $maxitem)
{
    $pdo = db_access();
    $query = "SELECT * FROM kana_tweet WHERE category = '$category' ORDER BY kana_tweet.updatetime DESC LIMIT $maxitem;";
    $result = db_prepare_sql($query, $pdo);
    db_close($pdo);

    foreach ($result as $row) {
        $usericon = icon_get($row['author']);
        $text = preg_replace('/^\r\n/m', '', (nl2br(un_enc($row['item']))));
        $text = strip_tags($text);
        echo "
        <div class='tweet_box'>
        <div class='user_info'>
        <img src='./images/" . nl2br(un_enc($usericon)) . "' class='usericon' />
        <label class='username'>{$row['author']}</label>
        ";

        if (login()) {
            echo " <a id='edit' class='edit'>…</a>";
        }

        echo "
        </div>
        <p class='text'>{$text}</p>
        ";

        if ($row['imageurl'] != "") {
            echo "
        <img src='./images/" . nl2br(un_enc($row['imageurl'])) . "' />
        ";
        }

        echo "
        <label class='updatetime'>" . nl2br(un_enc($row['updatetime'])) . "</label>
        </div>";
    }
}

function icon_get($username)
{
    $pdo = db_access();
    $query = "SELECT imageurl FROM kana_user WHERE username='$username';";
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
    <form id='form' method='post' action='index.php' enctype='multipart/form-data'>
        <a id='tweet_picker_show' class='tweet_picker_show'>カレンダー</a>
        <input id='num' type='hidden' name='num' value=''>
        <input id='author' type='hidden' name='author' value='$author'>
        <select name='category' id='category'>
            <option value='tweet'>おしらせ</option>
            <option value='calenderkita'>豊田北高校</option>
            <option value='calenderhigashi'>豊田東高校</option>
            <option value='calendernishi'>豊田西高校</option>
        </select>
        <textarea id='item' type='text' name='item' value='' placeholder='何かつぶやこう！' ></textarea>
        <input class='inputimage' id='image' type='file' name='image[]' accept='image/*'>
        <input id='imageurl' type='hidden' name='imageurl'>
        <div id='preview'></div>
        <button class='submit' id='submit' name='submit' value='submit'>投稿する</button>
        <button class='cancel' id='cancel' name='cancel' value='cancel'>キャンセル</button>
      <!-- <button class='delete' id='delete' name='delete' value='delete'>削除</button> -->
    </form>
</div>
";
}
?>

<!-- EOF -->