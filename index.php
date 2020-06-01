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

$page = $_REQUEST['page'];
if ($page == ''){
    $page = 1;
}
// 小さい数に対する処理
$page = max($page, 1);

// 大きい数に対する処理
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
// 配列cntの要素cntに件数が入ってる
$maxPage = ceil($cnt['cnt'] / 5);
// ceil:切り上げ floor:切り捨て
$page = min($page, $maxPage);


$start = ($page - 1) * 5;

// postsデータベースの情報を引き出す
// 情報を全て引き出すのでprepareがいらない -> ページネーションで5件だけ表示するのでprepareに変更
$posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id ORDER BY p.created DESC LIMIT ?,5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
// executeで渡すと文字列で入ってしまうので，数字で渡したかったらbindParamして次にexecute
$posts->execute();

if (isset($_REQUEST['res'])){
    // 返信の処理
    $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.members_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));

    $table = $response->fetch();
    $originalMessage = $table['impression'];
    $sendTo = '@' . $table['name'];

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <!--
    <link rel="stylesheet" href="css/style.css">
    -->


    <title>うまかもん</title>
</head>
<body>

<!-- ナビゲーションメニュー -->
<nav class="navbar navbar-expand-md navbar-light bg-light">
    <!-- ロゴ -->
    <a class="navbar-brand" href="index.php"><img src="image/logo_onlylogo.png" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">うまかもん</a>
    <!-- 狭い時にハンバーガーにする記述 -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <!-- ナビゲーションコンテンツ -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">タイムライン<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="post.php">ポスト</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="map.php">マップ</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                会員情報
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="account.php">会員情報確認</a>
                <a class="dropdown-item" href="login.php">別アカウントでログイン</a>
                <a class="dropdown-item" href="logout.php">ログアウト</a>
            </li>
        </ul>

        <!-- ログインアカウントの表示 -->
        <span class="navbar-text"><img src="member_picture/<?php print(htmlspecialchars($member['picture'], ENT_QUOTES)); ?>" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">&nbsp;<?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さんでログイン中</span>
    </div>
</nav>


    <?php foreach ($posts as $post): ?>
        <p>
            <?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>&nbsp;
            <span><img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" alt="画像指定なし" width="50" height="50"></span>&nbsp;
            <!-- 画像の指定がない時のアイコンどうする？ -->

            <?php print(htmlspecialchars($post['impression'], ENT_QUOTES)); ?>&nbsp;
            <a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">返信</a>&nbsp;
            <?php if ( $_SESSION['id'] === $post['members_id']): ?>
                <!--現在のログインユーザーがそのメッセージの投稿者と一致するならば削除可能-->
                <a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">削除</a>
            <?php endif; ?>
            <br>
            <a href="view.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
            <?php if ($post['reply_message_id'] > 0): ?>
                <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'], ENT_QUOTES)); ?>">返信元のメッセージ</a>
            <?php endif; ?>
        </p>
    <?php endforeach; ?>

    <!--ページネーション：次（前）のページがあればリンクを張る-->
    <ul>
        <?php if ( $page > 1 ): ?>
            <li><a href="index.php?page=<?php print($page-1); ?>">前のページ</a></li>
        <?php else: ?>
            <li>前のページ</li>
        <?php endif; ?>
        <?php if ( $page < $maxPage ): ?>
            <li><a href="index.php?page=<?php print($page+1); ?>">次のページ</a></li>
        <?php else: ?>
            <li>次のページ</li>
        <?php endif; ?>
    </ul>
    <br>

    <!--返信機能-->
    <p>返信画面</p>
    <form action="" method="post">
        <p><?php print(htmlspecialchars($originalMessage, ENT_QUOTES)); ?></p>
        <textarea name="replyMessage" id="" cols="30" rows="10"><?php print(htmlspecialchars($sendTo, ENT_QUOTES)); ?></textarea>
        <input type="hidden" name="reply_post_id" value="<?php print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES)); ?>">
        <input type="submit" value="返信する">
    </form>


<!-- BootstrapのJS読み込み <- 読み込んでないとメニュー開かない　-->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>


</body>
</html>