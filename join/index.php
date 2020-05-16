<?php
session_start();

if (!empty($_POST)) {
    if ($_POST['name'] === '') {
        // sessionを使う関係で文中にphpを書けないので，変数に記録して後から変数配列の内容を表示
        $error['name'] = 'blank';
    }
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if (empty($error)) {
        $_SESSION['join'] = $_POST;
        header('Location: check.php');
        exit();
        // $_SESSIONは2次元配列になる
        // header:これの前にHTMLが記述されるとリダイレクトが失敗する
        // exit():このページでの処理を終わらせる
    }
}
if ($_REQUEST['action'] === 'rewrite' && isset($_SESSION['join'])){
    $_POST = $_SESSION['join'];
}    
// $_REQUEST: $_GET、$_POST、$_COOKIEの内容をまとめた変数


?>
<!--＊＊使える文字の指定などを後で書く＊＊-->



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
<h1>会員登録</h1>
    <p>以下のフォームに必要事項をご記入ください</p>
    <form action="" method="post"><!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
    <!--action属性がからの場合には自分自身のページに戻ってくる-->
        <dl>
            <dt>ニックネーム　必須</dt>
            <dd>
                <input type="text" name="name" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)) ?>">
                <!--valueはボックス内の初期値-->
                <!--htmlspecialchars:文字列のエンティティ化（記号なども文字列として認識する）-->
                <?php if($error['name']==='blank'): ?>
                <p>* ニックネームを入力してください</p>
                <?php endif; ?>
            </dd>
            <dt>メールアドレス　必須</dt>
            <dd>
                <input type="email" name="email" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)) ?>">
                <?php if($error['email']==='blank'): ?>
                <p>* メールアドレスを入力してください</p>
                <?php endif; ?>
            </dd>
            <dt>パスワード　必須</dt>
            <dd>
                <input type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)) ?>">
                <?php if($error['password']==='blank'): ?>
                <p>* パスワードを入力してください</p>
                <?php endif; ?>
                <?php if($error['password']==='length'): ?>
                <p>* パスワードは4文字以上で設定してください</p>
                <?php endif; ?>
            </dd>
            <dt>写真など</dt>
            <dd>
                <input type="file" name="image">
            </dd>
        </dl>
        <input type="submit" value="ログインする">
    </form>
    
    
</body>
</html>