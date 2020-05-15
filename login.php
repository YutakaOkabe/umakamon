<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
</head>
<body>
<h1>ログインする</h1>
    <p>メールアドレスとパスワードを入力してログインしてください</p>
    <p>入会手続きがまだの方はこちら</p>
    <p>入会手続きをする</p>
    <form action="" method="post"><!--フォーム内でnameで名づけられたデータをaction属性のURLへmethod属性のHTTPのPOSTメソッドで送信-->
        <dl>
            <dt>メールアドレス</dt>
            <dd>
                <input type="text" name="email" value="メールアドレス">
            </dd>
            <dt>パスワード</dt>
            <dd>
                <input type="text" name="password" value="パスワード">
            </dd>
            <dt>ログイン情報の記録</dt>
            <dd>
                <input id="save" type="checkbox" name="save">
                <label for="save">次回からはログイン情報を記録する</label><!--label:ボックスの横に説明を追加--><!--for:説明を加えたいボックスのidを入力-->
            </dd>
        </dl>
        <input type="submit" value="ログインする"><!--buttonタグでも送信できるし，button使うともっと複雑なボタンコンテンツを作れるらしい-->
    </form>
    
    
</body>
</html>