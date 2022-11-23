<?php
    require "../init/config.php";    // 設定ファイル読み込み

    $prmarray = cnv_formstr($_POST); // フォームデータをPOSTメソッドに変換
?>

<?php $conn = db_conn(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?= APPNAME ?></title>
</head>
<body>
    <div align="center">
    <h3><?= APPNAME ?></h3>

    <?php
    // 検索キーワードを取得
    $key = (isset($prmarray["key"]))? $prmarray["key"] : "";
    // 表示するページ番号を取得
    $p = (isset($prmarray["p"]))? intval($prmarray["p"]) : 1;
    $p = ($p < 1)? 1 : $p;
    ?>

    <form action="<?= $_SERVER["SCRIPT_NAME"] ?>" method="POST">
        <table>
            <tr>
                <td><input type="text" name="key" value="<?= $key ?>"></td>
                <td><input type="submit" name="sub1" value="検索"></td>
            </tr>
        </table>
        <input type="hidden" name="act" value="src">
    </form>

    <?php disp_listdata($key, $p); ?>
    </div>
</body>
</html>
<?php db_close($conn); ?>

<?php
//----------------------------------------------------
// 配列データを一括変換
//----------------------------------------------------
function cnv_formstr($array) {
    foreach ($array as $k => $v) {
        // 「magic_quotes_gpc = On」のときはエスケープ解除
        if (get_magic_quotes_gpc()) {
            $v = stripslashes($v); // 文字列のクォート部分を取り除く
        }
        $v = htmlspecialchars($v);
        $array[$k] = $v;
    }
    return $array;
}

//----------------------------------------------------
// データをSQL用に変換
//----------------------------------------------------
function cnv_sqlstr($string) {
    // 文字コードを変換する
    $det_enc = mb_detect_encoding($string, "UTF-8, EUC-JP, SJIS");
    if ($det_enc and $det_enc != ENCDB) {
        $string = mb_convert_encoding($string, ENCDB, $det_enc);
    }
    $string = addslashes($string);
    return $string;
}

//----------------------------------------------------
// 表示する文字コードに変換
//----------------------------------------------------
function cnv_dispstr($string) {
    $det_enc = mb_detect_encoding($string, "UTF-8, EUC-JP, SJIS");
    if ($det_enc and $det_enc != ENCDISP) {
        return mb_convert_encoding($string, ENCDISP, $det_enc);
    } else {
        return $string;
    }
}

//----------------------------------------------------
// リンク先のURLとタイトルをリンクに変換 
//----------------------------------------------------
function cnv_link($url, $title) {
    $string = "<a href=\"$url\">" . $title . "</a>";
    return $string;
} 

//----------------------------------------------------
// データ一覧表示
//----------------------------------------------------
function disp_listdata($key,$p) {
    global $conn;
    $st = ($p - 1) * intval(ADMINPAGESIZE); // 表示するデータの位置

    $sql = "SELECT * FROM linkdata";
    if (strlen($key) > 0) {
        $sql .= " WHERE (l_begin_time     LIKE '% ". cnv_sqlstr($key) ." %')";
        $sql .= " OR    (l_end_time   LIKE '% ". cnv_sqlstr($key) ." %')";
        $sql .= " OR    (l_content LIKE '% ". cnv_sqlstr($key) ." %')";
    }
    $sql .= " ORDER BY l_date DESC LIMIT $st, " . intval(ADMINPAGESIZE);

    $res = db_query($sql, $conn);
    if ($res->num_rows <= 0) {
        echo "<p>データは登録されていません。</p>";
        return;
    }
?>
    <table border="0" width="600" style="line-height: 150%">
        <tr>
            <td>
                <ul>
                    <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) { ?>
                        <li><?= cnv_link($row["l_begin_time"], cnv_dispstr($row["l_end_time"])) ?>
                        --- <?= cnv_dispstr($row["l_content"]) ?>
                        (<?= date("Y/m/d", strtotime($row["l_date"])) ?>)
                    <?php }?>
                </ul>
            </td>
        </tr>
    </table>
    <?php disp_pagenav($key, $p); ?>
<?php
}

//----------------------------------------------------
// 前後ページへのリンク表示
//----------------------------------------------------
function disp_pagenav($key, $p=1) {
    global $conn;

    // 前後のページ番号を取得
    $prev = $p - 1;
    $prev = ($prev < 1)? 1 : $prev;
    $next = $p + 1;

    // 全件取得
    $sql = "SELECT COUNT(*) AS count FROM linkdata";
    if (isset($key)) {
        if (strlen($key) > 0) {
            $sql .= " WHERE (l_begin_time     LIKE '% ". cnv_sqlstr($key) ." %')";
            $sql .= " OR    (l_end_time   LIKE '% ". cnv_sqlstr($key) ." %')";
            $sql .= " OR    (l_content LIKE '% ". cnv_sqlstr($key) ." %')";
        }
    }
    $res = db_query($sql, $conn);
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $recordcount = $row["count"];
?>
    <table>
        <tr>
            <?php if ($p > 1) { ?>
                <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                    <td><input type = "submit" value = "<< 前へ"></td>
                    <input type = "hidden" name = "act" value = "src">
                    <input type = "hidden" name = "p" value = "<?= $prev ?>">
                    <input type = "hidden" name = "key" value = "<?= $key ?>">
                </form>
            <?php } ?>
            <?php if ($recordcount > ($next - 1) * intval(ADMINPAGESIZE)) { ?>
                <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                    <td width ="50%"><input type = "submit" value = "次へ >>"></td>
                    <input type = "hidden" name = "act" value = "src">
                    <input type = "hidden" name = "p" value = "<?= $next ?>">
                    <input type = "hidden" name = "key" value = "<?= $key ?>">
                </form>
            <?php } ?>
        </tr>
    </table>
<?php
}

//----------------------------------------------------
// DB接続
//----------------------------------------------------
function db_conn() {
    $conn = new mysqli(DBSV, DBUSER, DBPASS, DBNAME);
    if ($conn->connect_error) {
        error_log($conn->connect_error);
        exit;
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}

//----------------------------------------------------
// SQL実行
//----------------------------------------------------
function db_query($sql,$conn) {
    $res = $conn->query($sql);
    return $res;
}

//----------------------------------------------------
// DB接続解除
//----------------------------------------------------
function db_close($conn){
    $conn->close(); // 接続を解除
}

?>