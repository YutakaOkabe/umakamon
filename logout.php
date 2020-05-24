<?php
session_start();

$_SESSION = array();
// とりあえずセッションを空にする
if (ini_get('session.use_cookies')){
    // セッションの中身をひとつづつ消去
    $params = session_get_cookie_params();
    setcookie(session_name() . '', time()-1, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
// セッション自体を消してしまう

setcookie('email', '', time()-1);
// 途中で設定したクッキーも削除

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
    <p>ログアウトしました</p>
    <a href="login.php">ログインする</a>
</body>
</html>