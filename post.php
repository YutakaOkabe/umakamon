<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
// ログインしていて1時間以内にアクションがある場合
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    // ここではまだテーブルでいっぱい呼び出されていることになるので，fetch()で1行だけ取り出す
    $member = $members->fetch();
}else{
    header('Location: login.php');
}
?>

<!DOCTYPE html> <!--HTML5に準拠したサイトならこれだけでOK　昔のにも対応したいならさらに記述が必要-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>うまかもんポスト</title>
</head>
<body>
    <h1>うまかもんポスト</h1>
    <p><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さんのおいしいお店の情報をシェアしよう</p>
    <dl>
        <dt>お店の感想を入力</dt><!--definition term-->
        <dd><!--definition description-->
            <textarea name="impression" cols="100" rows="5" placeholder="ゴマサバが絶品だった！"></textarea>
            <!--col:文字数rows:行数,textareaタグ内は入力初期値-->
            <!--placeholder:半透明の入力例，required:入力必須指定-->
            <input type="hidden" name="" value=""><!--hiddenはサイト上には表示されないが，サーバーに値を送信できる-->
        </dd>
        <dt>お店のGoogleMapのURLを入力</dt>
        <dd>
            <input type="text" name="url" size="122">
        </dd>
    </dl>
    <input type="submit" value="投稿する">

</body>
</html>