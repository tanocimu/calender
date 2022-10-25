<?php
function login()
{
    if ($_SESSION['user_name'] == null) {
        return false;
    }
    return true;
}

function show_login_form()
{

    if (login()) {
        $username = $_SESSION['user_name'];
        $usericon = icon_get($username);
        echo  "
        <h1 class='login_logo'>ユーザーログアウト</h1>
        <div class='login_box'>
        <img src='./images/$usericon' class='login_usericon' />
        <label>{$username}さんはログイン中です。</label>
            <form method='post' action='login.php'>
                <input type='submit' name='logout' value='ログアウト'>
            </form>
        </div>";
    } else {
        echo  '
<h1 class="login_logo">ユーザーログイン</h1>
<div class="login_box">
    <form method="post" action="login.php">
        <input id="userid" type="text" name="userid" value="" placeholder="IDを入力してね">
        <input id="password" type="password" name="password" value="" placeholder="パスワードを入力してね">
        <input type="submit" name="login" value="ログイン">
    </form>
</div>';
    }
}

function take_submit()
{
    // エラーメッセージの初期化
    $errorMessage = "";

    // ログインボタンが押された場合
    if (isset($_POST["login"])) {
        // 1. ユーザIDの入力チェック
        if (empty($_POST["userid"])) {  // emptyは値が空のとき
            $errorMessage = 'ユーザーIDが未入力です。';
        } else if (empty($_POST["password"])) {
            $errorMessage = 'パスワードが未入力です。';
        }

        if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
            $userid = $_POST["userid"];
            try {
                $pdo = db_access();

                $stmt = $pdo->prepare('SELECT * FROM kana_user WHERE username = ?');
                $stmt->execute(array($userid));

                $password = $_POST["password"];

                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (password_verify($password, $row['password'])) {
                        // 入力したIDのユーザー名を取得
                        $id = $row['username'];
                        $sql = "SELECT * FROM kana_user WHERE username = '$id'";
                        $stmt = $pdo->query($sql);
                        foreach ($stmt as $row) {
                            $row['username'];
                        }
                        $_SESSION['user_name'] = $id;
                        $_SESSION['user_num'] = $row['num'];
                        header('Location: ./index.php');
                        exit();
                    } else {
                        $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                    }
                } else {
                    $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            } catch (PDOException $e) {
                $errorMessage = 'データベースエラー';
            }
        }
    }

    if (isset($_POST['logout'])) {
        $_SESSION['user_name'] = null;
        $_SESSION['user_num'] = null;
        $_SESSION = array();
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
        session_destroy();
        header('Location: ./login.php');
        exit();
    }

    if (isset($_POST['iconchange']) && !empty($_FILES['image']['tmp_name'][0])) {
        $usernum = $_POST['num'];
        $imageurl = "";

        for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
            $imageurl = uniqid(mt_rand(), true); //ファイル名をユニーク化
            $imageurl .= '.' . substr(strrchr($_FILES['image']['name'][$i], '.'), 1); //アップロードされたファイルの拡張子を取得
            move_uploaded_file($_FILES['image']['tmp_name'][$i],  DIR_IMAGES . $imageurl); //imagesディレクトリにファイル保存
        }

        $pdo = db_access();
        $sql = "UPDATE kana_user SET imageurl = '$imageurl' WHERE kana_user.num = $usernum;";
        db_prepare_sql($sql, $pdo);
        db_close($pdo);
        $_SESSION["success"] = "success";

        header('Location: ./login.php');
        exit();
    }
}

function usericon_change()
{
    if ($_SESSION['user_name'] != null) {
        $username = $_SESSION['user_name'];
        $usernum = $_SESSION['user_num'];
        echo "
        <div class='iconchange_form'>
        ユーザーアイコンを変更しよう！
    <form id='form' method='post' action='login.php' enctype='multipart/form-data'>
        <input id='num' type='hidden' name='num' value='$usernum '>
        <input id='author' type='hidden' name='author' value='$username'>
        <input class='inputimage' id='image' type='file' name='image[]' accept='image/*'>
        <div id='preview'></div>
        <button class='iconchange' id='iconchange' name='iconchange' value='submit'>変更する</button>
    </form>
</div>";
    }
}
?>

<!-- EOF -->