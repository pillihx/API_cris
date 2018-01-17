<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Base de datos
        'db' => [
            'servername' => '149.56.240.183',
            'port' => ':3306',
            'username' => 'railstack_jmiranda',
            'password' => 'xpressium18',
            'dbname' => 'railstack_api_cris',
            'charset' => 'utf8',
        ]
    ],
];