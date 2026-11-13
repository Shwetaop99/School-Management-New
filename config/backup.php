
<?php

return [
    'password' => env('BACKUP_PASSWORD'),
    'restore_enabled' => env('BACKUP_RESTORE_ENABLED', false),
    'restore_test_database' => env('BACKUP_RESTORE_TEST_DATABASE', ''),
];
