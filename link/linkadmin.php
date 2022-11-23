<link rel= "stylesheet"  type= "text/css"  href= "link.css"  media= "screen"  />
<?php
ini_set('display_errors', "Off"); // 警告エラーを非表示

session_cache_limiter("public");  // キャッシュ制御用のHTTPヘッダを取得
session_start();                  // セッション開始

require "../init/config.php";             // 設定ファイル読み込み

$prmarray = cnv_formstr($_POST);  // フォームデータをPOSTメソッドに変換

if (!chk_auth($prmarray)) {
    $act = DEFSCR; // デフォルト（ログイン画面）
} elseif (isset($prmarray["act"])) {
    $act = $prmarray["act"];
} else {
    $act = DEFSCR; // デフォルト（ログイン画面）
}

$dt = date("Y-m-d H:i:s");
?>

<?php $conn = db_conn(); ?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title><?= ADMINAPPNAME ?></title>
</head>

<body>
    <div align="center">
        <?php
        // 画面表示ルーチンの呼び出し
        call_user_func("screen_" . $act, $prmarray);
        ?>
    </div>
</body>

</html>
<?php db_close($conn); ?>

<?php
//----------------------------------------------------
// ログイン画面
//----------------------------------------------------
function screen_log($array)
{
?>
    <h3>ログイン画面</h3>

    <!-- ログインフォーム -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>ログインID</td>
                <td><input type="text" name="l_id"></td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td><input type="password" name="l_pass"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub1" value="ログイン"></td>
            </tr>
        </table>
        <input type="hidden" name="act" value="src">
    </form>
<?php
}

//----------------------------------------------------
// 検索画面
//----------------------------------------------------
function screen_src($array)
{
    // 検索キーワード
    $key = (isset($array["key"])) ? $array["key"] : "";
    // 表示するページ
    $p = (isset($array["p"])) ? intval($array["p"]) : 1; // 変数の整数としての値を取得
    $p = ($p < 1) ? 1 : $p;
?>
    <?php disp_menu(); ?>

    <!-- 検索フォーム -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <!-- <table border="0">
            <tr>
                <td><input type="text" name="key" value="<?= $key ?>"></td>
                <td><input type="submit" name="sub1" value="検索"></td>
            </tr>
        </table> -->
        <input type="hidden" name="act" value="src">
    </form>
    <?php disp_listdata($key, $p); ?>
<?php
}

//----------------------------------------------------
// 登録画面
//----------------------------------------------------
function screen_ent()
{
?>
    <?php disp_menu(); ?>
    <h3>登録画面</h3>

    <!-- 登録フォーム -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>開始日時</td>
                <td><input type="datetime-local" name="begin_datetime" size="100"></td>
            </tr>
            <tr>
                <td>終了日時</td>
                <td><input type="datetime-local" name="end_datetime" size="100"></td>
            </tr>
            <tr>
                <td>場所</td>
                <td><input type="text" name="place" size="100"></td>
            </tr>
            <tr>
                <td>内容</td>
                <td><input type="text" name="content" size="100"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub1" value="登録確認"></td>
            </tr>
        </table>
        <input type="hidden" name="act" value="entconf">
    </form>
<?php
}

//----------------------------------------------------
// 登録確認画面
//----------------------------------------------------
function screen_entconf($array)
{
    if (!chk_data($array)) {
        return;
    }
    extract($array);
?>
    <?php disp_menu(); ?>
    <h3>登録画面</h3>

    <!-- 登録データ確認表示 -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>開始時刻</td>
                <td><?= $begin_datetime ?></td>
            <tr>
                <td>終了時刻</td>
                <td><?= $end_datetime ?></td>
            </tr>
            <tr>
                <td>場所</td>
                <td><?= $place ?></td>
            </tr>
            <tr>
                <td>内容</td>
                <td><?= $content ?></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub1" value="登録実行"></td>
            </tr>
        </table>
        <input type="hidden" name="begin_datetime" value="<?= $begin_datetime ?>">
        <input type="hidden" name="end_datetime" value="<?= $end_datetime ?>">
        <input type="hidden" name="place" value="<?= $place ?>">
        <input type="hidden" name="content" value="<?= $content ?>">
        <input type="hidden" name="act" value="dojob">
        <input type="hidden" name="kbn" value="ent">
    </form>
<?php
}

//----------------------------------------------------
// 更新画面
//----------------------------------------------------
function screen_upd($array)
{
    if (!isset($array["id_int"])) {
        return;
    }
    $row = get_data($array["id_int"]);
?>
    <?php disp_menu(); ?>
    <h3>更新画面</h3>

    <!-- 更新フォーム -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>id_int</td>
                <td><?= $row["id_int"] ?></td>
            </tr>
            <tr>
                <td>開始時刻</td>
                <td><input type="datetime-local" name="begin_datetime" value="<?= $row["begin_datetime"] ?>" size="100"></td>
            </tr>
            <tr>
                <td>終了時刻</td>
                <td><input type="datetime-local" name="end_datetime" value="<?= cnv_dispstr($row["end_datetime"]) ?>" s ize="50"></td>
            </tr>
            <tr>
                <td>場所</td>
                <td><input type="text" name="place" value="<?= cnv_dispstr($row["place"]) ?>" size="100"></td>
            <tr>
                <td>内容</td>
                <td><input type="text" name="content" value="<?= cnv_dispstr($row["content"]) ?>" size="100"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub1" value="更新確認"></td>
            </tr>
        </table>
        <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
        <input type="hidden" name="act" value="updconf">
    </form>
<?php
}

//----------------------------------------------------
// 更新確認画面
//----------------------------------------------------
function screen_updconf($row)
{
    if (!chk_data($row)) {
        return;
    }
?>
    <?php disp_menu(); ?>
    <h3>更新確認画面</h3>

    <!-- 更新データ確認表示 -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>id_int</td>
                <td><?= $row["id_int"] ?></td>
            </tr>
            <tr>
                <td>開始時刻</td>
                <td><?= $row["begin_datetime"] ?></td>
            </tr>
            <tr>
                <td>終了時刻</td>
                <td><?= $row["end_datetime"] ?></td>
            </tr>
            <tr>
                <td>場所</td>
                <td><?= $row["place"] ?></td>
            <tr>
                <td>内容</td>
                <td><?= $row["content"] ?></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" name="sub1" value="更新実行"></td>
            </tr>
        </table>
        <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
        <input type="hidden" name="begin_datetime" value="<?= $row["begin_datetime"] ?>">
        <input type="hidden" name="end_datetime" value="<?= $row["end_datetime"] ?>">
        <input type="hidden" name="place" value="<?= $row["place"] ?>">
        <input type="hidden" name="content" value="<?= $row["content"] ?>">
        <input type="hidden" name="act" value="dojob">
        <input type="hidden" name="kbn" value="upd">
    </form>
<?php
}

//----------------------------------------------------
// 削除確認画面
//----------------------------------------------------
function screen_delconf($array)
{
    if (!isset($array["id_int"])) {
        return;
    }
    $row = get_data($array["id_int"]);
?>
    <?php disp_menu(); ?>
    <h3>削除確認画面</h3>

    <!-- 削除データ確認表示 -->
    <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
        <table border="1">
            <tr>
                <td>id_int</td>
                <td><?= $row["id_int"] ?></td>
            </tr>
            <tr>
                <td>開始時刻</td>
                <td><?= $row["begin_datetime"] ?></td>
            </tr>
            <tr>
                <td>終了時刻</td>
                <td><?= cnv_dispstr($row["end_datetime"]) ?></td>
            </tr>
            <tr>
                <td>場所</td>
                <td><?= cnv_dispstr($row["place"]) ?></td>
            <tr>
                <td>内容</td>
                <td><?= cnv_dispstr($row["content"]) ?></td>
            </tr>
            <!-- <tr>
                <td>登録日時</td>
                <td><?= $row["l_date"] ?></td>
            </tr> -->
            <td></td>
            <td><input type="submit" name="sub1" value="削除実行"></td>
            </tr>
        </table>
        <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
        <input type="hidden" name="act" value="dojob">
        <input type="hidden" name="kbn" value="del">
    </form>
<?php
}

//----------------------------------------------------
// 処理完了画面
//----------------------------------------------------
function screen_dojob($array)
{
    $res_mes = db_update($array);
?>
    <?php disp_menu(); ?>
    <h3>処理完了画面</h3>

    <!-- 処理結果表示 -->
    <table border="0" bgcolor="pink">
        <tr>
            <td>処理結果</td>
            <td><?= $res_mes; ?></td>
        </tr>
    </table>
<?php
}

//----------------------------------------------------
// ユーザ権限チェック
//----------------------------------------------------
function chk_auth($array)
{
    if (isset($_POST["l_id"]) and isset($_POST["l_pass"])) {
        if ($_POST["l_id"] == LOGINID and $_POST["l_pass"] == LOGINPASS) {
            $_SESSION["auth"] = AUTHADMIN;
            return true;
        } else {
            return false;
        }
    } else {
        if (!isset($_SESSION["auth"])) {
            return false;
        } else {
            if ($_SESSION["auth"] == AUTHADMIN) {
                return true;
            } else {
                return false;
            }
        }
    }
}

//----------------------------------------------------
// 登録データチェック
//----------------------------------------------------
function chk_data($array)
{
    $strerr = "";
    if ($array["begin_datetime"] == "") {
        echo "<p>開始時刻が入力されていません。</p>";
        $strerr = "1";
    }
    if ($array["end_datetime"] == "") {
        echo "<p>終了時刻が入力されていません。</p>";
        $strerr = "1";
    }
    if ($array["place"] == "") {
        echo "<p>場所が入力されていません。</p>";
        $strerr = "1";
    }
    if ($array["content"] == "") {
        echo "<p>内容入力されていません。</p>";
        $strerr = "1";
    }
    if ($strerr == "1") {
        return false;
    } else {
        return true;
    }
}

//----------------------------------------------------
// 配列データを一括変換
//----------------------------------------------------
function cnv_formstr($array)
{
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
function cnv_sqlstr($string)
{
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
function cnv_dispstr($string)
{
    $det_enc = mb_detect_encoding($string, "UTF-8, EUC-JP, SJIS");
    if ($det_enc and $det_enc != ENCDISP) {
        return mb_convert_encoding($string, ENCDISP, $det_enc);
    } else {
        return $string;
    }
}

//----------------------------------------------------
// リンク先のURLと終了時刻をリンクに変換 
//----------------------------------------------------
function cnv_link($url, $title)
{
    $string = "<a href=\"$url\">" . $title . "</a>";
    return $string;
}

//----------------------------------------------------
// 指定データ抽出
//----------------------------------------------------
function get_data($id_int)
{
    global $conn;

    // 指定データ数を抽出する
    $sql = "SELECT * FROM todo_list";
    $sql .= " WHERE (id_int = " . cnv_sqlstr($id_int) . ")";
    $res = db_query($sql, $conn);
    $row = $res->fetch_array(MYSQLI_ASSOC);

    return $row;
}

//----------------------------------------------------
// データ一覧表示
//----------------------------------------------------
function disp_listdata($key, $p)
{
    global $conn;
    $st = ($p - 1) * intval(ADMINPAGESIZE); // 表示するデータの位置

    $sql = "SELECT * FROM todo_list";
    // $now = new Datetime();
    // $now =  str_replace(" ", "T", $now_time->format('Y-m-d H:i:s'));
    // $week = $now()->modify('+1 week');
    //$sql .= " WHERE (begin_datetime >= " . cnv_sqlstr($now) . " and begin_datetime <= " . cnv_sqlstr($week) . ")";
    //$sql .= " WHERE (begin_datetime BETWEEN '2022-11-20T19:42' AND '2022-11-30T19:42')";
    // $sql = "SELECT * FROM schedule_data WHERE begin >= NOW() AND begin < (NOW() + INTERVAL 6 DAY) ORDER BY begin";
    $sql .= " WHERE begin_datetime >= NOW() AND begin_datetime < (NOW() + INTERVAL 6 DAY)";
    //$sql .= " WHERE begin_datetime >= (NOW() - INTERVAL 1 WEEK)";
    if (strlen($key) > 0) {
        $sql .= " AND (begin_datetime     LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (end_datetime   LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (place LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (content LIKE '% " . cnv_sqlstr($key) . " %')";
    }
    // $sql .= " ORDER BY begin_datetime ASC LIMIT $st, " . intval(ADMINPAGESIZE);

    $res = db_query($sql, $conn);
    if ($res->num_rows <= 0) {
        echo "<p>データは登録されていません。</p>";
        return;
    }
?>
    <h4>直近1週間のToDo</h4>
    <table border="1">
        <tr>
            <td></td>
            <td>開始時刻</td>
            <td>終了時刻</td>
            <td>場所</td>
            <td>内容</td>
        </tr>
        <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) { ?>
            <tr>
                <td>
                    <table>
                        <tr>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td><input type="submit" value="更新"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="upd">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td width="50%"><input type="submit" value="削除"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="delconf">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                        </tr>
                    </table>
                </td>
                <td><?= cnv_dispstr($row["begin_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["end_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["place"]) ?></td>
                <td><?= cnv_dispstr($row["content"]) ?></td>
            </tr>
        <?php } ?>
    </table>

    <?php
    $sql = "SELECT * FROM todo_list";
    $sql .= " WHERE begin_datetime >= NOW() AND begin_datetime < (NOW() + INTERVAL 1 MONTH)";
    if (strlen($key) > 0) {
        $sql .= " WHERE (begin_datetime LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR (end_datetime LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR (place LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR (content LIKE '% " . cnv_sqlstr($key) . " %')";
    }
    $sql .= " ORDER BY begin_datetime DESC LIMIT $st, " . intval(ADMINPAGESIZE);

    $res = db_query($sql, $conn);
    if ($res->num_rows <= 0) {
        echo "<p>データは登録されていません。</p>";
        return;
    } ?>
    <h4>直近1ヶ月のToDo</h4>
    <table border="1">
        <tr>
            <td></td>
            <td>開始時刻</td>
            <td>終了時刻</td>
            <td>場所</td>
            <td>内容</td>
        </tr>
        <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) { ?>
            <tr>
                <td>
                    <table>
                        <tr>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td><input type="submit" value="更新"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="upd">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td width="50%"><input type="submit" value="削除"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="delconf">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                        </tr>
                    </table>
                </td>
                <td><?= cnv_dispstr($row["begin_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["end_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["place"]) ?></td>
                <td><?= cnv_dispstr($row["content"]) ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php
    $sql = "SELECT * FROM todo_list";
    if (strlen($key) > 0) {
        $sql .= " WHERE (begin_datetime     LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (end_datetime   LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (place LIKE '% " . cnv_sqlstr($key) . " %')";
        $sql .= " OR    (content LIKE '% " . cnv_sqlstr($key) . " %')";
    }
    $sql .= " ORDER BY begin_datetime ASC LIMIT $st, " . intval(ADMINPAGESIZE);

    $res = db_query($sql, $conn);
    if ($res->num_rows <= 0) {
        echo "<p>データは登録されていません。</p>";
        return;
    }
    ?>
    <h4>すべてのToDo</h4>
    <table border="1">
        <tr>
            <td></td>
            <td>開始時刻</td>
            <td>終了時刻</td>
            <td>場所</td>
            <td>内容</td>
        </tr>
        <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) { ?>
            <tr>
                <td>
                    <table>
                        <tr>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td><input type="submit" value="更新"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="upd">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                                <td width="50%"><input type="submit" value="削除"></td>
                                <!-- 管理 -->
                                <input type="hidden" name="act" value="delconf">
                                <!-- キー -->
                                <input type="hidden" name="id_int" value="<?= $row["id_int"] ?>">
                            </form>
                        </tr>
                    </table>
                </td>
                <td><?= cnv_dispstr($row["begin_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["end_datetime"]) ?></td>
                <td><?= cnv_dispstr($row["place"]) ?></td>
                <td><?= cnv_dispstr($row["content"]) ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php disp_pagenav($key, $p); ?>
<?php
}

//----------------------------------------------------
// メニュー表示
//----------------------------------------------------
function disp_menu()
{
?>
    <table>
        <tr>
            <td><b><?= ADMINAPPNAME ?></b></td>
            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                <td><input type="submit" value="登録画面へ"></td>
                <input type="hidden" name="act" value="ent">
            </form>
            <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                <td><input type="submit" value="検索画面へ"></td>
                <input type="hidden" name="act" value="src">
            </form>
        </tr>
    </table>
<?php
}

//----------------------------------------------------
// 前後ページへのリンク表示
//----------------------------------------------------
function disp_pagenav($key, $p = 1)
{
    global $conn;

    // 前後のページ番号を取得
    $prev = $p - 1;
    $prev = ($prev < 1) ? 1 : $prev;
    $next = $p + 1;

    // 全件取得
    $sql = "SELECT COUNT(*) AS count FROM todo_list";
    if (isset($key)) {
        if (strlen($key) > 0) {
            $sql .= " WHERE (begin_datetime     LIKE '% " . cnv_sqlstr($key) . " %')";
            $sql .= " OR    (end_datetime   LIKE '% " . cnv_sqlstr($key) . " %')";
            $sql .= " OR    (place LIKE '% " . cnv_sqlstr($key) . " %')";
            $sql .= " OR    (content LIKE '% " . cnv_sqlstr($key) . " %')";
        }
    }
    $res = db_query($sql, $conn) or die("データ抽出エラー");
    $row = $res->fetch_array(MYSQLI_ASSOC);
    $recordcount = $row["count"];
?>
    <table>
        <tr>
            <?php if ($p > 1) { ?>
                <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                    <td><input type="submit" value="<< 前へ"></td>
                    <input type="hidden" name="act" value="src">
                    <input type="hidden" name="p" value="<?= $prev ?>">
                    <input type="hidden" name="key" value="<?= $key ?>">
                </form>
            <?php } ?>
            <?php if ($recordcount > ($next - 1) * intval(ADMINPAGESIZE)) { ?>
                <form action="<?= $_SERVER['SCRIPT_NAME'] ?>" method="POST">
                    <td width="50%"><input type="submit" value="次へ >>"></td>
                    <input type="hidden" name="act" value="src">
                    <input type="hidden" name="p" value="<?= $next ?>">
                    <input type="hidden" name="key" value="<?= $key ?>">
                </form>
            <?php } ?>
        </tr>
    </table>
<?php
}

//----------------------------------------------------
// DB接続
//----------------------------------------------------
function db_conn()
{
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
function db_query($sql, $conn)
{
    $res = $conn->query($sql);
    return $res;
}

//----------------------------------------------------
// DB更新
//----------------------------------------------------
function db_update($array)
{
    global $conn;
    global $dt;

    if (!isset($array["kbn"])) {
        return "パラメータエラー";
    }
    if ($array["kbn"] != "del") {
        if (!chk_data($array)) {
            return "パラメータエラー";
        }
    }
    if ($array["kbn"] != "ent") {
        if (!isset($array["id_int"])) {
            return "パラメータエラー";
        }
    }
    extract($array);

    /*** データ追加 ***/
    if ($kbn == "ent") {
        $sql = "INSERT INTO todo_list (";
        $sql .= "begin_datetime, ";
        $sql .= "end_datetime, ";
        $sql .= "place, ";
        $sql .= "content ";
        $sql .= ") VALUES (";
        $sql .= "'" . cnv_sqlstr($begin_datetime)     . "',";
        $sql .= "'" . cnv_sqlstr($end_datetime)   . "',";
        $sql .= "'" . cnv_sqlstr($place) . "',";
        $sql .= "'" . cnv_sqlstr($content) . "'";
        $sql .= ")";

        $res = db_query($sql, $conn);
        if ($res) {
            return "登録完了";
        } else {
            return "登録失敗";
        }
    }

    /*** データ変更 ***/
    if ($kbn == "upd") {
        $sql = "UPDATE todo_list SET ";
        $sql .= "begin_datetime = '" . cnv_sqlstr($begin_datetime) . "',";
        $sql .= "end_datetime = '" . cnv_sqlstr($end_datetime) . "',";
        $sql .= "place = '" . cnv_sqlstr($place) . "',";
        $sql .= "content = '" . cnv_sqlstr($content) . "'";
        $sql .= "WHERE id_int = " . cnv_sqlstr($id_int);

        $res = db_query($sql, $conn);
        if ($res) {
            return "登録完了";
        } else {
            return "登録失敗";
        }
    }

    /*** データ削除 ***/
    if ($kbn == "del") {
        $sql = "DELETE FROM todo_list ";
        $sql .= "WHERE id_int = " . cnv_sqlstr($id_int);

        $res = db_query($sql, $conn);
        if ($res) {
            return "削除完了";
        } else {
            return "削除失敗";
        }
    }
}

//----------------------------------------------------
// DB接続解除
//----------------------------------------------------
function db_close($conn)
{
    $conn->close(); // 接続を解除
}

?>