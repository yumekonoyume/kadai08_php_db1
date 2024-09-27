<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>レッスン口コミ入力</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- カスタムスタイル -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="select.php">◀ 口コミ一覧へ</a>
                </div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <?php
    session_start(); // セッションを開始して画像パスを保持

    // POSTデータが送信され、確認画面表示フラグがセットされている場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
        // フォームから送信されたデータを取得
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $course = htmlspecialchars($_POST['course'], ENT_QUOTES, 'UTF-8');
        $score = htmlspecialchars($_POST['score'], ENT_QUOTES, 'UTF-8');
        $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');

        // 画像のアップロード処理
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $tmp_name = $_FILES['image']['tmp_name'];
            $filename = basename($_FILES['image']['name']);
            $upload_path = $upload_dir . $filename;

            if (move_uploaded_file($tmp_name, $upload_path)) {
                // 画像パスをセッションに保存
                $_SESSION['uploaded_image'] = $upload_path;
            } else {
                echo "画像のアップロードに失敗しました。";
            }
        }

        // セッションに保存した画像パスを取得
        $uploaded_image = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : null;
    ?>
        <!-- 確認画面 -->
        <div class="jumbotron">
            <div class="jumbotron">
                <!-- h4タグを使用してサイズを小さくし、下線を追加 -->
                <h4 style="border-bottom: 2px solid lightgray; padding-bottom: 5px;">入力内容をご確認ください😊</h4>
                <p></p>
                <p>お名前     : <?= $username ?></p>
                <p>コース   : <?= $course ?></p>
                <p>評価★   : <?= $score ?> </p>
                <p>コメント : <?= $comment ?></p>

                <?php if ($uploaded_image): ?>
                    <p>作品やレッスン風景画像:</p>
                    <img src="<?= $uploaded_image ?>" alt="画像プレビュー" style="max-width: 200px;">
                <?php else: ?>
                    <p><strong>画像:</strong> なし</p>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <!-- hiddenで元の入力データを保持 -->
                    <input type="hidden" name="username" value="<?= $username ?>">
                    <input type="hidden" name="course" value="<?= $course ?>">
                    <input type="hidden" name="score" value="<?= $score ?>">
                    <input type="hidden" name="comment" value="<?= $comment ?>">
                    <input type="hidden" name="uploaded_image" value="<?= $uploaded_image ?>">

                    <!-- 修正ボタン: actionは指定せず、index.phpに戻す -->
                    <button type="submit" name="modify" class="btn btn-warning">修正する</button>
                    <!-- 送信ボタン: insert.phpにデータを送信 -->
                    <button type="submit" formaction="insert.php" class="btn btn-success">送信する</button>
                </form>
            </div>

        <?php
        // 修正ボタンが押された場合の処理
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify'])) {
        // 修正用の入力画面に戻る
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
        $course = htmlspecialchars($_POST['course'], ENT_QUOTES, 'UTF-8');
        $score = htmlspecialchars($_POST['score'], ENT_QUOTES, 'UTF-8');
        $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
        $uploaded_image = isset($_SESSION['uploaded_image']) ? $_SESSION['uploaded_image'] : null;
        ?>
            <!-- 修正後の入力フォーム -->
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <div class="jumbotron">
                    <fieldset>
                        <legend>受講後の感想をお聞かせ下さい😊</legend>

                        <label>名前：<input type="text" name="username" required value="<?= $username ?>"></label><br>

                        <label>コース：
                            <select name="course" required>
                                <option value="プリザーブドフラワーアレンジメント" <?= ($course == "プリザーブドフラワーアレンジメント") ? 'selected' : '' ?>>プリザーブドフラワーアレンジメント</option>
                                <option value="季節の生花アレンジメント" <?= ($course == "季節の生花アレンジメント") ? 'selected' : '' ?>>季節の生花アレンジメント</option>
                                <option value="花束レッスン" <?= ($course == "花束レッスン") ? 'selected' : '' ?>>花束レッスン</option>
                                <option value="コサージュレッスン" <?= ($course == "コサージュレッスン") ? 'selected' : '' ?>>コサージュレッスン</option>
                                <option value="ウェディングブーケレッスン" <?= ($course == "ウェディングブーケレッスン") ? 'selected' : '' ?>>ウェディングブーケレッスン</option>
                                <option value="ハーバリウムレッスン" <?= ($course == "ハーバリウムレッスン") ? 'selected' : '' ?>>ハーバリウムレッスン</option>
                            </select>
                        </label><br>

                        <label>評価：
                            <select name="score" required>
                                <option value="5" <?= ($score == "5") ? 'selected' : '' ?>>5 ★★★★★</option>
                                <option value="4" <?= ($score == "4") ? 'selected' : '' ?>>4 ★★★★</option>
                                <option value="3" <?= ($score == "3") ? 'selected' : '' ?>>3 ★★★</option>
                                <option value="2" <?= ($score == "2") ? 'selected' : '' ?>>2 ★★</option>
                                <option value="1" <?= ($score == "1") ? 'selected' : '' ?>>1 ★</option>
                            </select>
                        </label><br>

                        <label>コメント：<textarea name="comment" rows="4" required><?= $comment ?></textarea></label><br>

                        <!-- 再度画像のアップロードを要求 -->
                        <label>画像：<input type="file" name="image" accept="image/*" id="image-input"></label><br>

                        <?php if ($uploaded_image): ?>
                            <img id="preview" src="<?= $uploaded_image ?>" alt="プレビュー画像" style="max-width: 200px;"><br>
                        <?php else: ?>
                            <img id="preview" src="#" alt="プレビュー画像" style="display:none;"><br>
                        <?php endif; ?>

                        <button type="submit" name="confirm" class="btn btn-primary">確認画面へ</button>
                    </fieldset>
                </div>
            </form>

        <?php
    } else {
        // 最初の入力画面が表示されるようにする
        ?>
            <!-- 初期の入力フォーム -->
            <form method="POST" action="index.php" enctype="multipart/form-data">
                <div class="jumbotron">
                    <fieldset>
                        <legend>受講後の感想をお聞かせ下さい😊</legend>

                        <label>名前：<input type="text" name="username" required></label><br>

                        <label>コース：
                            <select name="course" required>
                                <option value="プリザーブドフラワーアレンジメント">プリザーブドフラワーアレンジメント</option>
                                <option value="季節の生花アレンジメント">季節の生花アレンジメント</option>
                                <option value="花束レッスン">花束レッスン</option>
                                <option value="コサージュレッスン">コサージュレッスン</option>
                                <option value="ウェディングブーケレッスン">ウェディングブーケレッスン</option>
                                <option value="ハーバリウムレッスン">ハーバリウムレッスン</option>
                            </select>
                        </label><br>

                        <label>評価：
                            <select name="score" required>
                                <option value="5">5 ★★★★★</option>
                                <option value="4">4 ★★★★</option>
                                <option value="3">3 ★★★</option>
                                <option value="2">2 ★★</option>
                                <option value="1">1 ★</option>
                            </select>
                        </label><br>

                        <label>コメント：<textarea name="comment" rows="4" required></textarea></label><br>

                        <label>画像（できあがった作品やレッスン風景など）：<input type="file" name="image" accept="image/*" id="image-input"></label><br>

                        <img id="preview" src="#" alt="プレビュー画像" style="display:none;"><br>

                        <button type="submit" name="confirm" class="btn btn-primary">確認画面へ</button>
                    </fieldset>
                </div>
            </form>

        <?php
    }
        ?>

</body>

</html>