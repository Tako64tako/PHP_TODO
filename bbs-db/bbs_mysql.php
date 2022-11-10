<?php

error_reporting(E_ALL);           // エラーを表示
ini_set('display_errors', "Off"); // 画面上のエラーを非表示設定にする


$error_message = array();
$mysqli = new mysqli("localhost", "root", "root", "bbs"); // (hostname, username, password, DBname)
$mysqli->set_charset("UTF-8");

/*「投稿する」ボタンを押した時の処理 */
if (isset($_POST['save'])) {
    $error_message = array();

    if (!strlen($_POST['body'])) {
        $error_message[] = "本文を入力してください。";
    }

    if (!count($error_message)) {
        $stmt = $mysqli->prepare("INSERT INTO post (date, title, name, body) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", date("Y-m-d H:i:s"), $_POST['title'], $_POST['name'], $_POST['body']);
        $stmt->execute();
    }
}

/* データを検索して$bbs_list配列にセットする */
$result = $mysqli->query("SELECT * FROM post ORDER BY date DESC");
$bbs_list = array();
while ($bbs = $result->fetch_array()) {
    $bbs_list[] = $bbs;
}


require_once("smarty/Smarty.class.php");
$smarty = new Smarty();               // Smartyインスタンス（$smartyオブジェクト）を作成
$smarty->template_dir = "templates";  // テンプレートディレクトリの指定        
$smarty->compile_dir = "templates_c"; // コンパイルディレクトリの指定

/* テンプレート変数に割り当てる */
$smarty->assign("error_message", $error_message);
$smarty->assign("bbs_list", $bbs_list);
$smarty->display("bbs.html");         // テンプレートを表示

?>
