<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "ENV DB_HOST=".env('DB_HOST')."\n";
echo "ENV DB_DATABASE=".env('DB_DATABASE')."\n";
echo "ENV DB_USERNAME=".env('DB_USERNAME')."\n";
echo "ENV DB_PASSWORD=".env('DB_PASSWORD')."\n";
echo "CONFIG DB_HOST=".config('database.connections.pgsql.host')."\n";
echo "CONFIG DB_DATABASE=".config('database.connections.pgsql.database')."\n";
echo "CONFIG DB_USERNAME=".config('database.connections.pgsql.username')."\n";
echo "CONFIG DB_PASSWORD=".config('database.connections.pgsql.password')."\n";
?>
