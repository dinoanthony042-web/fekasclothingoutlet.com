<?php

/**
 * Size Mapping Configuration
 * 
 * This configuration file maps standard clothing sizes to their UK and Turkish equivalents.
 * Used for displaying size information to customers unfamiliar with standard sizing.
 */

return [
    'mappings' => [
        'XS' => [
            'uk' => '6',
            'turkish' => '32',
            'label' => 'XS (UK 6, TR 32)',
        ],
        'S' => [
            'uk' => '8',
            'turkish' => '34',
            'label' => 'S (UK 8, TR 34)',
        ],
        'M' => [
            'uk' => '10-12',
            'turkish' => '36-38',
            'label' => 'M (UK 10-12, TR 36-38)',
        ],
        'L' => [
            'uk' => '14',
            'turkish' => '40',
            'label' => 'L (UK 14, TR 40)',
        ],
        'XL' => [
            'uk' => '16',
            'turkish' => '42',
            'label' => 'XL (UK 16, TR 42)',
        ],
        'XXL' => [
            'uk' => '18',
            'turkish' => '44',
            'label' => 'XXL (UK 18, TR 44)',
        ],
        'XXXL' => [
            'uk' => '20',
            'turkish' => '46',
            'label' => 'XXXL (UK 20, TR 46)',
        ],
    ],

    /**
     * Raw size chart (from provided table)
     * Each entry maps Turkish, USA, UK and Africa sizes to a common label
     */
    'chart' => [
        ['label' => 'XS',  'turkey' => 36, 'usa' => 4,  'uk' => 6,  'africa' => 26],
        ['label' => 'S',   'turkey' => 38, 'usa' => 6,  'uk' => 8,  'africa' => 28],
        ['label' => 'M',   'turkey' => 40, 'usa' => 8,  'uk' => 10, 'africa' => 30],
        ['label' => 'L',   'turkey' => 42, 'usa' => 10, 'uk' => 12, 'africa' => 32],
        ['label' => 'XL',  'turkey' => 44, 'usa' => 12, 'uk' => 14, 'africa' => 34],
        ['label' => 'XXL', 'turkey' => 46, 'usa' => 14, 'uk' => 16, 'africa' => 36],
        ['label' => 'XXXL','turkey' => 48, 'usa' => 16, 'uk' => 18, 'africa' => 38],
        ['label' => '4XL', 'turkey' => 50, 'usa' => 18, 'uk' => 20, 'africa' => 40],
        ['label' => '5XL', 'turkey' => 52, 'usa' => 20, 'uk' => 22, 'africa' => 42],
    ],

    /**
     * Shoe Size Mappings
     * Stored as EU sizes (which most shoes use)
     */
    'shoe_mappings' => [
        '36' => [
            'uk' => '3.5',
            'turkish' => '36',
            'label' => '36 (UK 3.5)',
        ],
        '37' => [
            'uk' => '4.5',
            'turkish' => '37',
            'label' => '37 (UK 4.5)',
        ],
        '38' => [
            'uk' => '5.5',
            'turkish' => '38',
            'label' => '38 (UK 5.5)',
        ],
        '39' => [
            'uk' => '6',
            'turkish' => '39',
            'label' => '39 (UK 6)',
        ],
        '40' => [
            'uk' => '7',
            'turkish' => '40',
            'label' => '40 (UK 7)',
        ],
        '41' => [
            'uk' => '7.5',
            'turkish' => '41',
            'label' => '41 (UK 7.5)',
        ],
        '42' => [
            'uk' => '8',
            'turkish' => '42',
            'label' => '42 (UK 8)',
        ],
        '43' => [
            'uk' => '9',
            'turkish' => '43',
            'label' => '43 (UK 9)',
        ],
        '44' => [
            'uk' => '10',
            'turkish' => '44',
            'label' => '44 (UK 10)',
        ],
        '45' => [
            'uk' => '10.5',
            'turkish' => '45',
            'label' => '45 (UK 10.5)',
        ],
    ],

    /**
     * All available size options for selection in admin
     */
    'all_options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL'],
    'shoe_options' => ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'],

    /**
     * Get mapping for a specific size
     * Usage in code: config('sizes.mappings.M')
     */
];
