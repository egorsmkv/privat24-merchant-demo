<?php

require __DIR__ . '/views/head.php';

$pdo = require __DIR__ . '/pdo.php';
$allConfig = require __DIR__ . '/config.php';

if (isset($_POST['payment'], $_POST['signature'], $_GET['productID'])) {

    $paymentData = $_POST['payment'];
    $signature = $_POST['signature'];

    $hash = sha1(md5($paymentData . $allConfig['merchantPassword']));

    if ($hash !== $signature) {
        echo '<div class="alert alert-danger">Ошибка платежа.</div>';
        require __DIR__ . '/views/foot.php';
        exit;
    }

    $paymentItems = [];
    $items = explode('&', $paymentData);
    foreach ($items as $it) {
        list($key, $value) = explode('=', $it, 2);
        $paymentItems[$key] = $value;
    }

    $productID = $_GET['productID'];
    $orderID = $paymentItems['order'];

    // проверяем существование товара
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $productID]);
    $product = $stmt->fetch();

    if ($product === false) {
        echo '<div class="alert alert-danger">Товар не найден.</div>';
        require __DIR__ . '/views/foot.php';
        exit;
    }

    // проверяем существование оплаченного заказа
    $stmt = $pdo->prepare('SELECT * FROM payments WHERE order_id = :order_id');
    $stmt->execute(['order_id' => $orderID]);
    $product = $stmt->fetch();

    if ($product) {
        echo '<div class="alert alert-info">Товар уже оплачен.</div>';
        require __DIR__ . '/views/foot.php';
        exit;
    }

    $paymentDataJSON = json_encode($paymentItems);

    $stmt = $pdo->prepare('INSERT INTO payments SET product_id = :product_id, 
        payment_data = :payment_data, order_id = :order_id, created_at = CURRENT_TIMESTAMP()');
    $isOK = $stmt->execute(['product_id' => $productID, 'payment_data' => $paymentDataJSON, 'order_id' => $orderID]);

    if ($isOK) {
        echo '<div class="alert alert-success">Платеж совершен успешно.</div>';
    } else {
        echo '<div class="alert alert-danger">Ошибка добавления платежа в базу данных.</div>';
    }
} else {
    echo '<div class="alert alert-danger">Платеж не удался.</div>';
    require __DIR__ . '/views/foot.php';
    exit;
}

require __DIR__ . '/views/foot.php';
