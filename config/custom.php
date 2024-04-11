<?php

return [

    'status' => [
        'brandNew' => '1',
        'assigned' => '2',
        'damaged' => '2',
    ],

    // Define expected headers
    'expectedFileHeaders' => [
        'asset_tag',
        'serial_no',
        'type_id',
        'hardware_standard_id',
        'technical_specification_id',
        'purchase_order',
        'location_id',
        'status'
    ],

    //MySQl Error Codes
    'sqlErrorCodes' => [
        'integrityConstraintViolation' => '23000',
    ],
    
];

