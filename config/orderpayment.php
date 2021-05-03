<?php

use Carbon\Carbon;

return [
    'shopDomain' => 'https://card-collector-club.myshopify.com/',

    'data' => [
        'id' => 1,
        'order_number' => 1,
        'line_items' => [
            [
                'gift_card' => true,
            ],
            [
                'gift_card' => false,
            ],
            [
                'gift_card' => true,
            ],
        ],
        'total_line_items_price' => 1000,
        'created_at' => Carbon::now()
    ],

    'customer' => [
        'id' => 1,
    ]
];