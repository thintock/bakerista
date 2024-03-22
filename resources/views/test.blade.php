<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品ラベル</title>
    <style>
        @media print {
            body {
                width: 80mm;
                height: 130mm;
                margin: 0;
                padding: 0;
                /* 印刷時のページ設定。余白なしに設定 */
                page-break-after: always;
            }
            .label {
                font-size: 10pt; /* テキストのサイズを調整 */
                /* 必要に応じて他のスタイルを追加 */
            }
        }
        /* 画面表示用のスタイル */
        .label {
            box-sizing: border-box;
            width: 80mm;
            height: 130mm;
            border: 1px solid #000; /* 境界線でラベルのサイズを確認 */
            padding: 10mm;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <div class="label">
        <!-- 商品の内容をここに表示 -->
        <p>商品名: Sample Item</p>
        <p>価格: ¥1,000</p>
        <p>説明: これはサンプルの商品です。</p>
        <!-- 他の情報も同様に -->
    </div>
</body>
</html>
