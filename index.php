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


// postsデータベースの情報を引き出す
// 情報を全て引き出すのでprepareがいらない
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id ORDER BY p.created DESC');

if (isset($_REQUEST['res'])){
    // 返信の処理
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $originalMessage = $table['impression'];
    $sendTo = '@' . $table['name'] ;

}

if (!empty($_POST)){
    if ($_POST['replyMessage'] !== ''){
        $reply = $db->prepare('INSERT INTO posts SET members_id=?, impression=?, reply_message_id=?, created=NOW()');
        $reply->execute(array(
            $member['id'],
            $_POST['replyMessage'],
            $_POST['reply_post_id']
        ));
        // $_POSTの値を消してタイムラインを再読み込みする
        header('Location: index.php');
        exit();
    }
}

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
        <p>
            <?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>&nbsp;
            <span><img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" alt="$post['name']" width="50" height="50"></span>&nbsp;
            <?php print(htmlspecialchars($post['impression'], ENT_QUOTES)); ?>&nbsp;
            <a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
            <a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">返信</a>
        </p>
    <?php endforeach; ?>
    <br>
    <p>返信画面</p>
    <form action="" method="post">
        <p><?php print(htmlspecialchars($originalMessage, ENT_QUOTES)); ?></p>
        <textarea name="replyMessage" id="" cols="30" rows="10"><?php print(htmlspecialchars($sendTo, ENT_QUOTES)); ?></textarea>
        <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>">
        <input type="submit" value="返信する">
    </form>
</body>
</html>