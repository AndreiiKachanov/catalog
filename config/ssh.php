<?php

return [
    'url' => env('SSH_CONNECT_ADDRESS', ''),
    'port' => env('SSH_CONNECT_PORT', ''),
    'username' => env('SSH_CONNECT_USER', ''),
    'password' => env('SSH_CONNECT_PASSWORD', ''),
    'remote_dir' => env('SSH_CONNECT_REMOTE_DIR', ''),
];
