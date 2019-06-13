<?php

require __DIR__ . '/views/head.php';

$pdo = require __DIR__ . '/pdo.php';

$data = $pdo->query('SELECT count(*) as num_products FROM products')->fetch();
if ($data['num_products'] === 0) {
    echo 'Для начала создайте продукты в базе данных.';
}

$products = $pdo->query('SELECT * FROM products');

echo '<h4>Продукты</h4>';
echo '<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Название</th>
      <th scope="col">Описание</th>
      <th scope="col">Цена</th>
      <th scope="col">Операции</th>
    </tr>
  </thead>
  <tbody>';

while ($product = $products->fetch()) {
    echo "<tr>
      <th>{$product['id']}</th>
      <td>{$product['name']}</td>
      <td>{$product['description']}</td>
      <td>{$product['amount']}, {$product['currency']}</td>
      <td><a href='./buy.php?id={$product['id']}' class='btn btn-success btn-sm'>Купить</a></td>
</tr>";
}

echo '</tbody></table>';

require __DIR__ . '/views/foot.php';
