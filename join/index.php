<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
    // 必須項目のチェック
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
    // ファイル種類のチェック
    $fileName = $_FILES['image']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        // 拡張子を取り出す
        if ( $ext != 'jpg' && $ext != 'png' && $ext != 'gif' ){
            $error['image'] = 'type';
        }
    }
    // アカウント重複のチェック
    if (empty($error)){
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
        $member->execute(array($_POST['email']));
        $record = $member->fetch();
        if ( $record['cnt'] > 0){
            $error['email'] = 'duplicate';
        }
    }
    // セッションに情報を記録
    if (empty($error)) {
        $image = date('YmdHis') . $fileName;
        // 「年月日秒＋ファイル名」としてファイルを保存することで重複を防ぐ
        // ファイルがアップロードされていなくても年月日秒は付加されることに注意
        move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
        // $_FILES['image']['tmp_name']にあるファイルを第2引数の場所に移動
        // この段階で移動しているので書き直しで使われなくなってもmember_pictureに保存はされる
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['image'] = $image;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="css/style.css">
    -->

    <!-- 画像を表示するCSS -->
    <style>
    .imagePreview {
        width: 180px;
        height: 180px;
        background-position: center center;
        background-size: cover;
        -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
        display: inline-block;
    }
    </style>

    <title>アカウント作成</title>
</head>
<body>
<img src="../image/logo.png" class="rounded mx-auto mt-5 d-block" alt="うまかもんロゴ">
<div class="container">
    <div class="text-center">
        <p class="font-weight-lighter">アカウント作成</p>
        <form action="" method="post" enctype="multipart/form-data"  class="form-group">
        <!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
        <!--action属性がからの場合には自分自身のページに戻ってくる-->
        <!--enctype="multipart/form-data":フォームでファイルを送信するときに必要になる-->
            <div class="text-right text-danger">*必須</div>
            <div>
                <input type="text" class="form-control" name="name" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)) ?>" placeholder="ニックネーム">
                <!--valueはボックス内の初期値-->
                <!--htmlspecialchars:文字列のエンティティ化（記号なども文字列として認識する）-->
                <small class="form-text text-left text-muted mb-2">アカウント名になります</small>
                <?php if($error['name']==='blank'): ?>
                <p class="text-left text-danger">* ニックネームを入力してください</p>
                <?php endif; ?>
            </div>
            <div class="text-right text-danger">*必須</div>
            <div>
                <input type="email" class="form-control" name="email" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)) ?>" placeholder="メールアドレス">
                <small class="form-text text-left text-muted mb-2">登録時にメールを送信します</small>
                <?php if($error['email']==='blank'): ?>
                <p class="text-left text-danger">* メールアドレスを入力してください</p>
                <?php endif; ?>
                <?php if($error['email']==='duplicate'): ?>
                <p class="text-left text-danger">* このメールアドレスは既に登録されています</p>
                <?php endif; ?>
            </div>
            <div class="text-right text-danger">*必須</div>
            <div>
                <input type="password" class="form-control" name="password" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)) ?>" placeholder="パスワード">
                <small class="form-text text-left text-muted mb-2">4文字以上で設定してください</small>
                <?php if($error['password']==='blank'): ?>
                <p class="text-left text-danger">* パスワードを入力してください</p>
                <?php endif; ?>
                <?php if($error['password']==='length'): ?>
                <p class="text-left text-danger">* パスワードは4文字以上で設定してください</p>
                <?php endif; ?>
            </div>
            <div>
                <!-- 元々 -->
                    <!-- <input type="file" name="image">
                    <?php if($error['image']==='type'): ?>
                    <p>* 写真は「jpg」「png」「gif」のいづれかの形式でアップロードしてください</p>
                    <?php endif; ?> -->

                <!-- Bootstrapでつくったfile_inputでアップロードした画像をその場で表示 https://qiita.com/gsk3beta/items/46d44793827920282f75　-->
                    <div class="container page-header col-7">
                        <div class="imagePreview"></div>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                    ファイルを選択<input type="file" style="display:none" class="uploadFile" name="image">
                                </span>
                            </label>
                            <input type="text" class="form-control" readonly="">
                            <?php if($error['image']==='type'): ?>
                                <p class="text-left text-danger">* 写真は「jpg」「png」「gif」のいづれかの形式でアップロードしてください</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                    <script>
                    $(document).on('change', ':file', function() {
                        var input = $(this),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                        input.parent().parent().next(':text').val(label);

                        var files = !!this.files ? this.files : [];
                        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
                        if (/^image/.test( files[0].type)){ // only image file
                            var reader = new FileReader(); // instance of the FileReader
                            reader.readAsDataURL(files[0]); // read the local file
                            reader.onloadend = function(){ // set image data as background of div
                                input.parent().parent().parent().prev('.imagePreview').css("background-image", "url("+this.result+")");
                            }
                        }
                    });
                    </script>
            </div>
            <!-- <input type="submit" value="登録内容を確認する"> -->
            <button type="submit" class="btn btn-primary mt-3 mb-4">登録内容を確認する</button>
        </form>
    </div>
    
</div>
    
</body>
</html>