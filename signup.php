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

            try {
                $pdo = db_access();
                $stmt = $pdo->prepare("INSERT INTO " . DB_PREFIX . "user(username, password, imageurl) VALUES ( ?, ?, 'init.png')");
                $stmt->execute(array($username, password_hash($password, PASSWORD_DEFAULT)));
                $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる

                $signUpMessage = '登録が完了しました。アカウント名： ' . $username . '　／　パスワード： ' . $password . ' です。';  // ログイン時に使用するIDとパスワード
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

function show_signupform()
{
    $username = "";
    if (!empty($_POST["username"])) {
        $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    }

    echo "
    <form id='signupForm' class='signupForm' name='signupForm' action='' method='POST'>
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
    ";
}
?>

<!-- EOF -->