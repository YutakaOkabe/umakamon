<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
<h1>会員登録する</h1>
    <p>以下のフォームに必要事項をご記入ください</p>
    <form action="" method="post"><!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
    <!--action属性がからの場合には自分自身のページに戻ってくる-->
        <dl>
        <dt>ニックネーム</dt>
            <dd>
                <input type="text" name="name" value="">
                <!--valueはボックス内の初期値-->
            </dd>
            <dt>メールアドレス</dt>
            <dd>
                <input type="text" name="email" value="">
            </dd>
            <dt>パスワード</dt>
            <dd>
                <input type="text" name="password" value="">
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