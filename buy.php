<?php

require __DIR__ . '/views/head.php';

if (empty($_GET['id'])) {
    echo 'Укажите ID товара.';
    require __DIR__ . '/views/foot.php';
    exit;
}

$pdo = require __DIR__ . '/pdo.php';
$allConfig = require __DIR__ . '/config.php';

$stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute(['id' => $_GET['id']]);
$product = $stmt->fetch();

if ($product === false) {
    echo 'Товар не найден.';
    require __DIR__ . '/views/foot.php';
    exit;
}

$orderID = uniqid($product['id'], true);
$returnURL = 'http://127.0.0.1:4455/payment.php?productID=' . $product['id'];
//$serverURL = 'http://127.0.0.1:4455/handler.php?productID=' . $product['id'];

echo "<h4>Покупка товара: {$product['name']}</h4>";

echo 'Нажмите на кнопку, что перейти на страницу оплаты товара';

echo '<form method="POST" action="https://api.privatbank.ua/p24api/ishop">';

echo '
<input type="hidden" name="amt" value="' . $product['amount'] . '"/>
<input type="hidden" name="ccy" value="' . $product['currency'] . '"/>
<input type="hidden" name="merchant" value="' . $allConfig['merchantID'] . '"/>
<input type="hidden" name="order" value="' . $orderID . '"/>
<input type="hidden" name="details" value="Покупка товара: ' . $product['name'] . '"/>
<input type="hidden" name="ext_details" value="' . $product['description'] . '"/>
<input type="hidden" name="return_url" value="' . $returnURL . '"/>
<input type="hidden" name="server_url" value=""/>
<input type="hidden" name="pay_way" value="privat24"/>

<button type="submit" class="btn btn-success">Купить</button>
';

echo '</form>';

require __DIR__ . '/views/foot.php';
