<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$s = new App\Services\SiasnApiService();
$f = $s->getReferensiJabatanFungsional();
print_r(array_slice($f['data'], 0, 1));

$p = $s->getReferensiJabatanPelaksana();
print_r(array_slice($p['data'], 0, 1));
