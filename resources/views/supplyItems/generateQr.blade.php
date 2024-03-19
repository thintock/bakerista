<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .qr-code-container {
            text-align: center;
        }
        .qr-code {
            width: 100px;
            height: 100px;
        }
        .item-info {
            margin-top: 10px;
            font-size: 8px; /* 文字の大きさを小さく設定 */
        }
        .item-info div {
            margin: 2px 0; /* 上下の余白を設定 */
        }
    </style>
</head>
<body>
    <div class="qr-code-container">
        {!! $qrCode !!}
        <div class="item-info">
            <div>{{ $item_code }}</div>
            <div>{{ Str::limit($item_name, 25, '...') }}</div>
            <div>{{ $location }}</div>
        </div>
    </div>
    <script>
        // ページ読み込み完了時に実行
        window.onload = function() {
            // 印刷ダイアログを表示
            window.print();

            // 印刷ダイアログが閉じられた後、ページを戻る
            window.addEventListener('afterprint', function() {
                window.history.back();
            });
        };
    </script>
</body>
</html>
