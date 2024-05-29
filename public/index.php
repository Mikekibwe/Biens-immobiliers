<?php 
use App\NumberHelper;
use App\QueryBuilder;
use App\TableHelper;
use App\URLHelper;
use App\Table;

define('PER_PAGE', 20);

require '../vendor/autoload.php';
$pdo = new PDO("sqlite:../products.db", null, null, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$query = (new QueryBuilder($pdo))->from('products');

if(!empty($_GET['q'])){
    $query
    ->where('city LIKE :city')
    ->setParam('city', '%' .  $_GET['q'] . '%');
}
$table = (new Table($query, $_GET))
    ->sortable('id', 'name', 'city', 'price','address')
    ->format('price', function ($value){
        return NumberHelper::price($value);
    })
    ->columns([
        'id' => 'ID',
        'name' => 'Nom',
        'city' => 'Ville',
        'price' => 'Prix',
        'address' => 'Adresse'
    ]);


?><!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-5.3.2-dist/css/bootstrap.min.css">


    <title>Biens immobiliers</title>
</head>
<body class="p-4" >

    <h1>Les biens immobiliers</h1>

    <form action="" class="mb-4" >
        .<div class="form-group">
        <input type="text" class="form-control" name="q" placeholder="Rechercher par ville" value="<?= isset($_GET['q']) ? urlencode($_GET['q']) : '' ?>" >
          <button class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <?= $table->render() ?>

    <script src="bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
</body>
</html>