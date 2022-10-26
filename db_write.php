<?php
function db_access()
{
    // DB接続情報
    $user = DB_USERNAME;
    $pass = DB_PASSWORD;
    $dbnm = DB_DATABASE;
    $host = DB_HOSTNAME;
    // 接続先DBリンク
    $connect = "mysql:host={$host};dbname={$dbnm}";

    try {
        // DB接続
        $pdo = new PDO($connect, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        echo "<p>DB接続エラー</p>";
        echo $e->getMessage();
        exit();
    }

    return $pdo;
}

function db_close($pdo)
{
    unset($pdo);
}

function db_prepare_sql(string $sql, $pdo)
{
    try {
        // SQL実行
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // 結果の取得
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //var_dump($result);
    } catch (Exception $e) {
        echo "<p>DB接続エラー</p>";
        echo $e->getMessage();
        exit();
    }

    return $result;
}

function submit_recieve()
{
    $category = array('tweet', 'calenderkita', 'calenderhigashi', 'calendernishi');
    if (isset($_POST['submit']) != "" && $_POST['item'] != "") {
        $pdo = db_access();
        $imageurl = "";
        $item = enc($_POST['item']);

        if (!empty($_FILES['image']['tmp_name'][0])) { //ファイルが選択されていれば$imageにファイル名を代入
            for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
                $imageurl = uniqid(mt_rand(), true); //ファイル名をユニーク化
                $imageurl .= '.' . substr(strrchr($_FILES['image']['name'][$i], '.'), 1); //アップロードされたファイルの拡張子を取得
                move_uploaded_file($_FILES['image']['tmp_name'][$i],  DIR_IMAGES . $imageurl); //imagesディレクトリにファイル保存
            }
        }

        // stk_num空なら記事を新規作成、あればその番号の記事を更新
        $categorynum = $_POST['category'];
        if ($_POST['num'] == "") {
            $sql = "INSERT INTO kana_tweet (num, category, item, imageurl, author, privatepublic, updatetime) VALUES (NULL, '{$category[$categorynum]}', '{$item}', '{$imageurl}', '{$_POST['author']}', '{$_POST['privatepublic']}', current_timestamp());";
            db_prepare_sql($sql, $pdo);
        } else {
            if ($imageurl != '') {
                $sql = "UPDATE kana_tweet SET item = '{$item}',imageurl = '{$imageurl}', privatepublic = '{$_POST['privatepublic']}', updatetime = current_timestamp() WHERE kana_tweet.num = {$_POST['num']};";
            } else {
                $sql = "UPDATE kana_tweet SET item = '{$item}', privatepublic = '{$_POST['privatepublic']}', updatetime = current_timestamp() WHERE kana_tweet.num = {$_POST['num']};";
            }
            db_prepare_sql($sql, $pdo);
        }
        $_SESSION["success"] = "success";

        if ($category[$categorynum] == 'tweet') {
            header('Location: ./');
        } else {
            header('Location: ./calender.php');
        }
        exit;
    } else if (isset($_POST['delete']) && $_POST['num'] != "") {
        // ファイル削除
        if (file_exists($_POST['imageurl'])) {
            unlink($_POST['imageurl']);
        }

        // DB削除
        $categorynum = $_POST['category'];
        $pdo = db_access();
        $sql = "DELETE FROM kana_tweet WHERE kana_tweet.num = '" . $_POST['num'] . "';";
        db_prepare_sql($sql, $pdo);
        $_SESSION["success"] = "delete";
        if ($category[$categorynum] == 'tweet') {
            header('Location: ./');
        } else {
            header('Location: ./calender.php');
        }
        exit;
    }
    return;
}

function show_success_message()
{
    if ($_SESSION["success"] == "success") {
        $success_message = "<div class='editjoined' id='editjoined'>記事の編集に成功しました！</div>";
    } elseif ($_SESSION["success"] == "delete") {
        $success_message = "<div class='editjoined' id='editjoined'>記事を削除しました。</div>";
    }
    echo $success_message;
    $_SESSION["success"] = "";
}

function enc($str)
{
    $str = htmlspecialchars($str, ENT_QUOTES);
    return $str;
}

function un_enc($str)
{
    $str = htmlspecialchars_decode($str);
    return $str;
}
?>
<!-- EOF -->