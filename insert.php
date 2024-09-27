<?php
require_once 'config.php';
session_start();

// POSTデータが送信されたかどうかチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから送信されたデータを取得
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $course = htmlspecialchars($_POST['course'], ENT_QUOTES, 'UTF-8');
    $score = (int)$_POST['score'];
    $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
    
    // 画像パスを取得
    $image_path = isset($_POST['uploaded_image']) ? $_POST['uploaded_image'] : null;

    try {
        // データベースにデータを挿入する準備
        $stmt = $pdo->prepare("INSERT INTO lessons_feedback (username, course, score, comment, image_path, indate) 
                               VALUES (:username, :course, :score, :comment, :image_path, NOW())");

        // パラメータをバインド
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':course', $course, PDO::PARAM_STR);
        $stmt->bindValue(':score', $score, PDO::PARAM_INT);
        $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindValue(':image_path', $image_path, PDO::PARAM_STR);  // アップロードした画像パスを保存

        // 実行
        $stmt->execute();

        // データベースにデータが正常に挿入された場合、送信完了メッセージを表示
        ?>

        <!DOCTYPE html>
        <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>送信完了</title>
            <!-- Bootstrap CSS -->
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <!-- カスタムスタイル -->
            <link href="css/style.css" rel="stylesheet">
        </head>
        <body>
            <!-- ナビゲーションバー -->
            <header>
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="index.php">レッスン口コミ入力</a>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- 送信完了メッセージ -->
            <div class="container mt-5">
                <div class="jumbotron text-center">
                    <h2 class="display-4">送信完了しました！</h2>
                    <p class="lead">口コミを投稿いただき、ありがとうございます😊</p>
                    <hr class="my-4">
                    <p>またの参加を心からお待ちしています！</p>
                    <a href="index.php" class="btn btn-primary btn-lg">口コミ入力に戻る</a>
                    <a href="select.php" class="btn btn-success btn-lg">口コミ一覧を見る</a>
                </div>
            </div>

            <!-- Bootstrap JS -->
            <script src="js/bootstrap.min.js"></script>
        </body>
        </html>

        <?php

        // セッションをクリアして、次の投稿に備える
        unset($_SESSION['uploaded_image']);
    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
