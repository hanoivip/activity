<?php

return [
    'web' => [
        1 => [
            'id' => 1,
            'start' => 0,
            'end' => 0,
            'type' => 'first_recharge',
            'group' => 'web',
            'title' => '',
            'description' => '',
            'icon' => 0,
            'params' => [
                0 => [
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
        2 => [
            'id' => 2,
            'start' => 0,
            'end' => 0,
            'type' => 'shop',
            'group' => 'web',
            'title' => 'Web Shop',
            'description' => '',
            'icon' => 0,
            'params' => [
                0 => [
                    'type' => 'Items',
                    'id' => 'ItemId0',
                    'count' => 1,
                    'price' => 5000,
                    'limit' => 0
                ],
                1 => [
                    'type' => 'Items',
                    'id' => 'ItemId1',
                    'count' => 5,
                    'price' => 5000,
                    'limit' => 10
                ],
                2 => [
                    'type' => 'Items',
                    'id' => 'ItemId2',
                    'count' => 1,
                    'price' => 20000,
                    'limit' => 0
                ],
            ],
        ],
        3 => [
            'id' => 3,
            'start' => 0,
            'end' => 0,
            'type' => 'recharge',
            'group' => 'web',
            'title' => 'Accumulate Recharge',
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
                20000 => [
                    [
                        'type' => 'Box',
                        'id' => 'Recharge3000',
                        'count' => 1
                    ]
                ],
            ]
        ],
    ],
    's1' => [
        1 => [
            'id' => 1,
            'start' => 0,
            'end' => 0,
            'type' => 'recharge',
            'group' => 's1',
            'title' => 'Accumulate Recharge',
            'description' => '',
            'icon' => 0,
            'params' => [
                10000 => [
                    [
                        'type' => 'Items',
                        'id' => 'OpenVLUS1',
                        'count' => 1
                    ],
                    [
                        'type' => 'Balance',
                        'id' => '0',
                        'count' => 20000
                    ]
                ],
                30000 => [
                    [
                        'type' => 'Items',
                        'id' => 'Recharge3000',
                        'count' => 1
                    ]
                ],
                500000 => [
                    [
                        'type' => 'Box',
                        'id' => 'Recharge3000',
                        'count' => 1
                    ]
                ],
            ]
        ],
        
    ]
];