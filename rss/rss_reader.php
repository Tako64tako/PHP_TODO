<?php

error_reporting(E_ALL);                  // エラーを表示
ini_set('display_errors', "Off");        // 画面上のエラーを非表示設定にする


$mysqli = new mysqli("localhost", "root", "root", "rss_reader"); // (hostname, username, password, DBname)
$mysqli->set_charset("UTF-8");
// $xml = simplexml_load_file("https://news.yahoo.co.jp/pickup/computer/rss.xml");
$xml = simplexml_load_file("http://gigazine.net/news/rss_2.0/");

foreach ($xml->channel->item as $feed) {
    $stmt = $mysqli->prepare("SELECT * FROM rss_item WHERE guid = ?"); // クエリ定義
    $stmt->bind_param("s", $feed->guid);                               // 値セット
    $stmt->execute();                                                  // 実行
    $stmt->store_result();                                             // 格納

    if ($stmt->num_rows() == 0) {
        $stmt2 = $mysqli->prepare("INSERT INTO rss_item (pub_date, title, description, author, link, guid) VALUES(?, ?, ?, ?, ?, ?)");
    } else {
        $stmt2 = $mysqli->prepare("UPDATE rss_item SET pub_date = ?, title = ?, description = ?, author = ?, link = ? WHERE guid = ?");
    }
    $stmt2->bind_param("ssssss", date("Y/m/d H:i:s", strtotime($feed->pubDate)), $feed->title, $feed->description, $feed->author, $feed->link, $feed->guid);
    $stmt2->execute();
}

$query_result = $mysqli->query("SELECT * FROM rss_item ORDER BY pub_date DESC");

$rss_list = array(); // トピックを格納するための配列
while ($rss = $query_result->fetch_array()) {
    $rss_list[] = $rss;
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>サクッとRSSリーダー</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>サクッとRSSリーダー 記事一覧</h1>
    <ul>
        <?php foreach ($rss_list as $rss) { ?>
            <li>
                <a href="#no<?php echo ($rss['id']); ?>">
                    <?php echo (htmlspecialchars($rss['title'], ENT_QUOTES)); ?>
            </li>
        <?php } ?>
    </ul>
    <?php foreach ($rss_list as $rss) { ?>
        <a name="no<?php echo ($rss["id"]) ?>">
            <h2>
                <a href="<?php echo (htmlspecialchars($rss['link'])) ?>">
                    <?php echo (htmlspecialchars($rss['title'], ENT_QUOTES)); ?>
                </a>
            </h2>
            <p>
                <strong>作成：<?php echo (htmlspecialchars($rss['author'])); ?>
                    【<?php echo (htmlspecialchars($rss['pub_date'])); ?>】
                </strong>
            </p>
            <p>
                <?php echo (nl2br(strip_tags($rss['description']))); ?>
            </p>
        <?php } ?>
</body>

</html>