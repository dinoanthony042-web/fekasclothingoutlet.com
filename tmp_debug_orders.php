<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach (App\Models\Order::with('user')->get() as $o) {
    echo $o->id . ' ' . $o->order_number . ' user=' . $o->user_id . PHP_EOL;
}
