<?php

return [
    'web' => [
        'first_recharge' => [
            'id' => 1,
            'start' => 0,
            'end' => 0,
            'type' => 'first_recharge',
            'title' => '',
            'description' => '',
            'icon' => 0,
            'params' => [
                [
                    'type' => 'Balance',
                    'id' => 0,
                    'count' => 10000
                ],
                [
                    'type' => 'Items',
                    'id' => 'FirstCharge',
                    'count' => 2
                ],
            ]
        ]
    ],
    's1' => [
        'recharge' => [
            'id' => 1,
            'start' => 0,
            'end' => 0,
            'type' => 'recharge',
            'title' => '',
            'description' => '',
            'icon' => 0,
            'params' => [
                1000 => [
                    [
                        'type' => 'Items',
                        'id' => 'Recharge1000',
                        'count' => 1
                    ],
                    [
                        'type' => 'Balance',
                        'id' => '0',
                        'count' => 1000
                    ]
                ],
                3000 => [
                    [
                        'type' => 'Items',
                        'id' => 'Recharge3000',
                        'count' => 1
                    ]
                ],
                5000 => [
                    [
                        'type' => 'Box',
                        'id' => 'Recharge3000',
                        'count' => 1
                    ]
                ],
            ]
        ]
    ]
];