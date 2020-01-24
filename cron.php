<?php

define('INTERVAL', 3600);
define('TS_FILE', './.cron_last_run');

$lastRun = file_get_contents(TS_FILE);
$currentTime = time();

if ($currentTime - $lastRun > INTERVAL) {
    try {
        cron_run();
    } catch (\Exception $e) {
        // ignore
    }

    file_put_contents(TS_FILE, $currentTime);
}

function cron_run() {
    foreach(['commands.json', 'products.json', 'users.json'] as $file) {
        copy("./_defaults/$file", "./data/$file");
    }
}