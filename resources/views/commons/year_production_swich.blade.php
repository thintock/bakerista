<?php
// millPurchaseMaterials.createで生産年度のinput valueにデフォルトで年度を表示させる。
// 10月を超えると、今年度さんが入ってくるのでその制御をする。

// 現在の月を取得
$month = date("m");

// 10月以降なら今年の西暦、それ以前なら去年の西暦を出力
if ($month >= 10) {
    echo date("y"); // 今年の西暦
} else {
    echo date("y", strtotime("last year")); // 去年の西暦
}
?>