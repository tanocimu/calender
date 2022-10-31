<?php
// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

function signup_submit_recieve()
{
    if (isset($_POST["signUp"])) {
        // 1. ユーザIDの入力チェック
        if (empty($_POST["username"])) {  // 値が空のとき
            $errorMessage = 'ユーザーIDが未入力です。';
        } else if (empty($_POST["password"])) {
            $errorMessage = 'パスワードが未入力です。';
        } else if (empty($_POST["password2"])) {
            $errorMessage = 'パスワードが未入力です。';
        }

        if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
            // 入力したユーザIDとパスワードを格納
            $username = $_POST["username"];
            $password = $_POST["password"];
            // 入力したIDが重複したら登録しない

            try {
                $pdo = db_access();
                $sql = "select count(*) from " . DB_PREFIX . "user where username = '$username'";
                // SQL実行
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION["success"] = "success";
                if ($result['count(*)'] > 0) {
                    show_success_message("ユーザーが存在します。");
                    return;
                }

                $stmt = $pdo->prepare("INSERT INTO " . DB_PREFIX . "user(username, password, imageurl) VALUES ( ?, ?, 'init.png')");
                $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));
                $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

                $signUpMessage = '登録が完了しました。アカウント名： ' . $username . '　／　パスワード： ' . $password . ' です。';  // ログイン時に使用するIDとパスワード
                $_SESSION["success"] = "success";
                show_success_message("ユーザーを追加しました。");
            } catch (PDOException $e) {
                $errorMessage = 'データベースエラー';
                // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
                // echo $e->getMessage();
            }
        } else if ($_POST["password"] != $_POST["password2"]) {
            $errorMessage = 'パスワードに誤りがあります。';
        }
    }
}

function userdelete_recieve()
{
    if (!empty($_POST["udelsubmit"])) {
        $pdo = db_access();
        // アカウント削除
        $sql = "DELETE FROM " . DB_PREFIX . "user WHERE " . DB_PREFIX . "user.username = '" . $_POST['udelselect'] . "';";
        db_prepare_sql($sql, $pdo);

        // アイテム削除
        $sql = "DELETE FROM " . DB_PREFIX . "tweet WHERE " . DB_PREFIX . "tweet.author = '" . $_POST['udelselect'] . "'";
        db_prepare_sql($sql, $pdo);

        // ポスターがしたコメント削除
        $sql = "DELETE FROM " . DB_PREFIX . "comment WHERE " . DB_PREFIX . "comment.poster = '" . $_POST['udelselect'] . "'";
        db_prepare_sql($sql, $pdo);

        // アカウントに投稿されたコメントの削除
        $sql = "DELETE FROM " . DB_PREFIX . "comment WHERE " . DB_PREFIX . "comment.author = '" . $_POST['udelselect'] . "'";
        db_prepare_sql($sql, $pdo);

        db_close($pdo);
        $_SESSION["success"] = "success";
        show_success_message("ユーザーを削除しました。");

        //header('Location: ./login.php');
        //exit;
    }
}

function show_signupform()
{
    $username = "";
    if (!empty($_POST["username"])) {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    }
    $pdo = db_access();
    $sql = "SELECT num, username FROM " . DB_PREFIX . "user WHERE 1";
    $result = db_prepare_sql($sql, $pdo);
    db_close($pdo);

    $userlist = "";
    foreach ($result as $row) {
        $userlist .= "<option value='" . $row['username'] . "'>" . $row['username'] . "</option>";
    }

    echo "
    <div class='signupForm'>
    <form id='signupForm' name='signupForm' action='' method='POST'>
        <fieldset>
            <h2>ユーザーを追加する</h2>
            <label for='username'>ユーザー名</label>
            <input type='text' id='username' name='username' placeholder='ユーザー名を入力' value='{$username}'>
            <label for='password'>パスワード</label>
            <input type='password' id='password' name='password' value='' placeholder='パスワードを入力'>
            <label for='password2'>パスワード(確認用)</label>
            <input type='password' id='password2' name='password2' value='' placeholder='再度パスワードを入力'>
            <input type='submit' id='signUp' name='signUp' value='追加'>
        </fieldset>
    </form>
    <hr />
    <h2>登録済みのユーザー</h2>
    <form id='udelForm' name='udelForm' action='' method='POST'>
    <select id='udelselect' name='udelselect'>
    $userlist
    </select>
    <input type='submit' id='udelsubmit' name='udelsubmit' value='ユーザー削除'>
    </form>
    </div>
    ";
}
?>

<!-- EOF -->