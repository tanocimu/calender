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
    if (isset($_POST['submit']) != "") {
        if ($_POST['item'] != "" || $_POST['item_calender'] != "") {
            $pdo = db_access();
            $imageurl = "";
            $item = enc($_POST['item']);
            $calender = $_POST['item_calender'];

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
                $sql = "INSERT INTO " . DB_PREFIX . "tweet (num, category, item, etc, imageurl, author, privatepublic, updatetime) VALUES (NULL, '{$category[$categorynum]}', '{$item}', '{$calender}', '{$imageurl}', '{$_POST['author']}', '{$_POST['privatepublic']}', current_timestamp());";
                db_prepare_sql($sql, $pdo);
            } else {
                if ($imageurl != '') {
                    $sql = "UPDATE " . DB_PREFIX . "tweet SET item = '{$item}', etc = '{$calender}',imageurl = '{$imageurl}', privatepublic = '{$_POST['privatepublic']}', updatetime = current_timestamp() WHERE " . DB_PREFIX . "tweet.num = {$_POST['num']};";
                } else {
                    $sql = "UPDATE " . DB_PREFIX . "tweet SET item = '{$item}', etc = '{$calender}', privatepublic = '{$_POST['privatepublic']}', updatetime = current_timestamp() WHERE " . DB_PREFIX . "tweet.num = {$_POST['num']};";
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
        }
    } else if (isset($_POST['delete']) && $_POST['num'] != "") {
        // ファイル削除
        if (file_exists($_POST['imageurl'])) {
            //     unlink($_POST['imageurl']);
        }

        // DB削除
        $categorynum = $_POST['category'];
        $pdo = db_access();
        $sql = "DELETE FROM " . DB_PREFIX . "tweet WHERE " . DB_PREFIX . "tweet.num = '" . $_POST['num'] . "';";
        db_prepare_sql($sql, $pdo);
        $_SESSION["success"] = "delete";
        if ($category[$categorynum] == 'tweet') {
            header('Location: ./');
        } else {
            header('Location: ./calender.php');
        }
        exit;
    } else if (isset($_POST['comment_submit']) && $_POST['comment'] != '') {
        $category = $_POST['category'];
        $pdo = db_access();
        $sql = "INSERT INTO " . DB_PREFIX . "comment (num, lipnum, comment, author, poster, updatetime) VALUES (NULL, '" . $_POST['lipnum'] . "', '" . $_POST['comment'] . "', '" . $_POST['author'] . "', '" . $_POST['poster'] . "', current_timestamp());";
        db_prepare_sql($sql, $pdo);

        if ($category == 'tweet') {
            header('Location: ./');
        } else {
            header('Location: ./calender.php');
        }
        exit;
    } else if (isset($_POST['approval'])) {
        $approval = $_POST['approval'];
        switch ($approval) {
            case "cmap":
                $_SESSION["success"] = 'success';
                $pdo = db_access();
                $sql = "UPDATE " . DB_PREFIX . "comment SET approval = 1 WHERE " . DB_PREFIX . "comment.num = {$_POST['apnum']};";
                db_prepare_sql($sql, $pdo);

                show_success_message('コメントを承認しました！');
                break;
            case "cmno":
                $_SESSION["success"] = 'success';
                $pdo = db_access();
                $sql = "DELETE FROM " . DB_PREFIX . "comment WHERE " . DB_PREFIX . "comment.num = {$_POST['apnum']};";
                db_prepare_sql($sql, $pdo);

                show_success_message('コメントを削除しました。');
                break;
            default:
                break;
        }
        return;
    }
    return;
}

function show_success_message($msg1)
{
    if ($_SESSION["success"] == "success") {
        $success_message = "<div class='editjoined' id='editjoined'>$msg1</div>";
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

function check_admin($username)
{
    $check = false;

    $pdo = db_access();
    $sql = "SELECT admin FROM " . DB_PREFIX . "user WHERE username = '$username'";
    $result = db_prepare_sql($sql, $pdo);

    foreach ($result as $row) {
        $check = $row['admin'];
    }

    db_close($pdo);
    return $check;
}
?>
<!-- EOF -->