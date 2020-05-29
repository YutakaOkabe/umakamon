<?php
session_start();
require('dbconnect.php');

// 前にログインしてクッキーに値がある場合
if ($_COOKIE !== ''){
    $email = $_COOKIE['email'];
}

if (!empty($_POST)) {
// ログインボタンが押されている
    // クッキーの値のままになっているのでログインが押されたら上書き
    $email = $_POST['email'];

    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    } else {
        $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
        $login->execute(array(
            $_POST['email'],
            sha1($_POST['password'])
        ));
        $member = $login->fetch();
        // 上でヒットするアカウントがあれば行で返ってくる
        if ($member) {
            // 誰がいつログインしたのかをindexページに渡す
            $_SESSION['id'] = $member['id'];
            $_SESSION['time'] = time();

            // ログイン情報をクッキーに保存
            // パスワードを暗号化状態で保存しても自動的な入力が正しくできないため意味がない
            if ($_POST['save'] === 'on'){
                setcookie('email', $_POST['email'], time()+60*60*24*14 );
            }

            header('Location: index.php');
            exit();
        } else {
            // ヒットするアカウントがなかったのでログイン失敗
            $error['login'] = 'failed';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="css/style.css">
    -->
    <title>ログイン</title>
</head>
<body>
<img src="image/logo.png" class="rounded mx-auto mt-5 d-block" alt="うまかもんロゴ">
<div class="container">
    <div class=" text-center">
        <p class="font-weight-lighter">ログイン</p>
        <form action="" method="post" class="form-group">
        <!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
            <div>
                <input type="email" class="form-control" name="email" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" placeholder="メールアドレス">
                <?php if($error['email']==='blank'): ?>
                <p class="text-left text-danger">* メールアドレスを入力してください</p>
                <?php endif; ?>
            </div>
            <div>
                <input type="password" class="form-control" name="password" value="" placeholder="パスワード">
                <?php if($error['password']==='blank'): ?>
                <p class="text-left text-danger">* パスワードを入力してください</p>
                <?php endif; ?>
                <?php if($error['login']==='failed'): ?>
                <p class="text-left text-danger">* ログインに失敗しました．情報を正しく入力してください．</p>
                <?php endif; ?>
            </div>
            <div class="text-left">
                <input id="save" type="checkbox" name="save" value="on">
                <label for="save">次回からはログイン情報を記録する</label>
                <!--label:ボックスの横に説明を追加--><!--for:説明を加えたいボックスのidを入力-->
            </div>
            <!-- <input type="submit" value="ログインする"> -->
            <!--buttonタグでも送信できるし，button使うともっと複雑なボタンコンテンツを作れるらしい-->
            <button type="submit" class="btn btn-primary mb-4">ログインする</button>
            <p>アカウントをお持ちですか？<a href="join/index.php">今すぐアカウントを作成</a></p>


            <!-- アカウントを作成せずに始めるボタンが欲しい -->


        </form>
    </div>
    
</div>
    
    
    
</body>
</html>