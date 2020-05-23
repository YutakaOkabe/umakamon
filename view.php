<?php
session_start();
require('dbconnect.php');

if (empty($_REQUEST['id'])){
    header('Location: index.php');
    exit();
}

$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));

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
　　<div style="text-align: right"><a href="index.php">一覧画面へ</a></div>
    <?php if( $post = $posts->fetch()): ?>
        <img src="member_picture/<?php print(htmlspecialchars($post['picture'])); ?>" alt="" width="100" height="100">
        <p><?php print(htmlspecialchars($post['name'])); ?></p>
        <p><?php print(htmlspecialchars($post['impression'])); ?></p>
        <p><?php print(htmlspecialchars($post['created'])); ?></p>
    <?php else: ?>
        <p>その投稿は削除されたか、URLが間違えています</p>
    <?php endif; ?>
    

</body>
</html>