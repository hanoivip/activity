<?php

return [
    
    // Support: array, database
    'cfg' => 'array',
    
    // Define db table name, platfrom for each group
    // If not defined, group will be deactived
    // Common platform name: web, game:s1, game:s2 ...
    'web' => [ 
        'table' => 'activities',
        'platform' => 'web'
        ],
    's1' => [
        'table' => 's1activities',
        'platform' => 's1'
    ]
];