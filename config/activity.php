<?php

return [
    
    // Support: array, database
    'cfg' => 'array',
    
    // Define db table name, platfrom for each group
    // If not defined, group will be deactived
    // Common platform name: web, s1, s2 ...
    'web' => [ 
        'table' => 'activities',
        'platform' => 'web'
        ]
];