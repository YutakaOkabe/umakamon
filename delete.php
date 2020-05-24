<?php
session_start();
require('dbconnect.php');

if (isset($_SESSION['id'])){
    $id = $_REQUEST['id'];
    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if ($message['members_id'] === $_SESSION['id']){
        // 削除する人物が正しいかどうかを再チェック
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
}
header('Location: index.php');
exit();

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

</body>
</html>