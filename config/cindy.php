<?php

$products = [
    ['slug' => 'navy-fit-flare-dress', 'name' => 'Navy Fit & Flare Dress', 'category' => 'Dresses', 'collection' => 'workwear', 'price' => 2200, 'image' => 'product-42.jpeg', 'badge' => 'New'],
    ['slug' => 'coffee-pleated-midi', 'name' => 'Coffee Pleated Midi', 'category' => 'Dresses', 'collection' => 'occasion', 'price' => 2400, 'image' => 'product-43.jpeg', 'badge' => 'Editor Pick'],
    ['slug' => 'blush-soft-midi', 'name' => 'Blush Soft Midi Dress', 'category' => 'Dresses', 'collection' => 'occasion', 'price' => 2100, 'image' => 'product-44.jpeg', 'badge' => 'New'],
    ['slug' => 'navy-bloom-belted-dress', 'name' => 'Navy Bloom Belted Dress', 'category' => 'Dresses', 'collection' => 'prints', 'price' => 2500, 'image' => 'product-57.jpeg', 'badge' => 'Hero'],
    ['slug' => 'lilac-contour-set', 'name' => 'Lilac Contour Set', 'category' => 'Sets', 'collection' => 'weekend', 'price' => 2300, 'image' => 'product-58.jpeg', 'badge' => 'Limited'],
    ['slug' => 'black-sculpt-set', 'name' => 'Black Sculpt Set', 'category' => 'Sets', 'collection' => 'workwear', 'price' => 2300, 'image' => 'product-59.jpeg', 'badge' => 'Best Seller'],
    ['slug' => 'mint-tailored-set', 'name' => 'Mint Tailored Set', 'category' => 'Sets', 'collection' => 'weekend', 'price' => 2300, 'image' => 'product-60.jpeg', 'badge' => 'New'],
    ['slug' => 'burnt-copper-maxi', 'name' => 'Burnt Copper Maxi Dress', 'category' => 'Dresses', 'collection' => 'occasion', 'price' => 3000, 'image' => 'product-63.jpeg', 'badge' => 'Premium'],
    ['slug' => 'fuchsia-statement-dress', 'name' => 'Fuchsia Statement Dress', 'category' => 'Dresses', 'collection' => 'occasion', 'price' => 2900, 'image' => 'product-65.jpeg', 'badge' => 'Event'],
    ['slug' => 'royal-blue-wide-leg', 'name' => 'Royal Blue Wide Leg Trouser', 'category' => 'Bottoms', 'collection' => 'workwear', 'price' => 1800, 'image' => 'product-31.jpeg', 'badge' => 'Restocked'],
    ['slug' => 'ink-blue-wide-leg', 'name' => 'Ink Blue Wide Leg Trouser', 'category' => 'Bottoms', 'collection' => 'workwear', 'price' => 1800, 'image' => 'product-35.jpeg', 'badge' => 'Core'],
    ['slug' => 'ivory-gold-blouse', 'name' => 'Ivory Gold Blouse', 'category' => 'Tops', 'collection' => 'prints', 'price' => 1500, 'image' => 'product-28.jpeg', 'badge' => 'Lightweight'],
    ['slug' => 'olive-utility-jacket', 'name' => 'Olive Utility Jacket', 'category' => 'Outerwear', 'collection' => 'weekend', 'price' => 2700, 'image' => 'product-29.jpeg', 'badge' => 'Layer'],
    ['slug' => 'denim-ease-pant', 'name' => 'Denim Ease Pant', 'category' => 'Bottoms', 'collection' => 'weekend', 'price' => 1700, 'image' => 'product-30.jpeg', 'badge' => 'Everyday'],
    ['slug' => 'green-bloom-wrap', 'name' => 'Green Bloom Wrap Dress', 'category' => 'Dresses', 'collection' => 'prints', 'price' => 2400, 'image' => 'product-51.jpeg', 'badge' => 'Print'],
    ['slug' => 'coral-office-dress', 'name' => 'Coral Office Dress', 'category' => 'Dresses', 'collection' => 'workwear', 'price' => 2200, 'image' => 'product-53.jpeg', 'badge' => 'Polished'],
];

return [
    'phone' => '+254 700 000 000',
    'email' => 'orders@cindyapparel.co.ke',
    'whatsapp' => 'https://wa.me/254700000000',
    'hero_product' => 'navy-bloom-belted-dress',
    'products' => $products,
    'collections' => [
        'workwear' => [
            'name' => 'Modern Workwear',
            'summary' => 'Structured pieces for office days, meetings, and refined daily dressing.',
            'image' => 'product-59.jpeg',
        ],
        'occasion' => [
            'name' => 'Occasion Dresses',
            'summary' => 'Elegant dresses for church, ceremonies, dinners, and standout weekends.',
            'image' => 'product-63.jpeg',
        ],
        'prints' => [
            'name' => 'Soft Prints',
            'summary' => 'Fresh florals and expressive patterns balanced with clean Cindy tailoring.',
            'image' => 'product-57.jpeg',
        ],
        'weekend' => [
            'name' => 'Weekend Ease',
            'summary' => 'Comfortable sets, separates, and casual polish for off-duty plans.',
            'image' => 'product-58.jpeg',
        ],
    ],
];
