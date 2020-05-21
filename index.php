<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
    // ログインしていて1時間以内にアクションがある場合
        $_SESSION['time'] = time();
        // ユーザー情報を引き出す
        $members = $db->prepare('SELECT * FROM members WHERE id=?');
        $members->execute(array($_SESSION['id']));
        // ここではまだテーブルでいっぱい呼び出されていることになるので，fetch()で1行だけ取り出す
        $member = $members->fetch();
    }else{
        header('Location: login.php');
    }
    
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id ORDER BY p.created DESC');

?>

<!DOCTYPE html> <!--HTML5に準拠したサイトならこれだけでOK　昔のにも対応したいならさらに記述が必要-->
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>うまかもん</title>
</head>
<body>
　　<div style="text-align: right"><a href="post.php">投稿画面へ</a></div>
    <h2><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さんのタイムライン</h2>

    <?php foreach ($posts as $post): ?>
        <p><?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>&nbsp;<span><img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" alt="" width="50" height="50"></span>&nbsp;<?php print(htmlspecialchars($post['impression'], ENT_QUOTES)); ?>&nbsp;<?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
    <?php endforeach; ?>
</body>
</html>