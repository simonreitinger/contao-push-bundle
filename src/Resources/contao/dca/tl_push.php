<?php

declare(strict_types=1);

/*
 * This file is part of the Contao Push Bundle.
 * (c) Werbeagentur Dreibein GmbH
 */

$table = 'tl_push';

$GLOBALS['TL_DCA'][$table] = [
    'config' => [
        'dataContainer' => 'Table',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'authToken' => 'index',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{general_legend},endpoint,publicKey,authToken,contentEncoding',
    ],
    'fields' => [
        'id' => [
            'label' => ['ID'],
            'sql' => ['type' => 'integer', 'autoincrement' => true, 'unsigned' => true],
        ],
        'endpoint' => [
            'sql' => ['type' => 'string', 'default' => ''],
        ],
        'publicKey' => [
            'sql' => ['type' => 'string', 'default' => ''],
        ],
        'authToken' => [
            'sql' => ['type' => 'string', 'default' => ''],
        ],
        'contentEncoding' => [
            'sql' => ['type' => 'string', 'default' => ''],
        ],
    ],
];
