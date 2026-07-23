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
        '18' => [
            'uk' => '2',
            'turkish' => '18',
            'label' => '18 (UK 2, TR 18)',
        ],
        '19' => [
            'uk' => '2.5',
            'turkish' => '19',
            'label' => '19 (UK 2.5, TR 19)',
        ],
        '20' => [
            'uk' => '3',
            'turkish' => '20',
            'label' => '20 (UK 3, TR 20)',
        ],
        '21' => [
            'uk' => '3.5',
            'turkish' => '21',
            'label' => '21 (UK 3.5, TR 21)',
        ],
        '22' => [
            'uk' => '4',
            'turkish' => '22',
            'label' => '22 (UK 4, TR 22)',
        ],
        '23' => [
            'uk' => '4.5',
            'turkish' => '23',
            'label' => '23 (UK 4.5, TR 23)',
        ],
        '24' => [
            'uk' => '5',
            'turkish' => '24',
            'label' => '24 (UK 5, TR 24)',
        ],
        '25' => [
            'uk' => '5.5',
            'turkish' => '25',
            'label' => '25 (UK 5.5, TR 25)',
        ],
        '26' => [
            'uk' => '6',
            'turkish' => '26',
            'label' => '26 (UK 6, TR 26)',
        ],
        '27' => [
            'uk' => '6.5',
            'turkish' => '27',
            'label' => '27 (UK 6.5, TR 27)',
        ],
        '28' => [
            'uk' => '7',
            'turkish' => '28',
            'label' => '28 (UK 7, TR 28)',
        ],
        '29' => [
            'uk' => '7.5',
            'turkish' => '29',
            'label' => '29 (UK 7.5, TR 29)',
        ],
        '30' => [
            'uk' => '8',
            'turkish' => '30',
            'label' => '30 (UK 8, TR 30)',
        ],
        '31' => [
            'uk' => '8.5',
            'turkish' => '31',
            'label' => '31 (UK 8.5, TR 31)',
        ],
        '32' => [
            'uk' => '9',
            'turkish' => '32',
            'label' => '32 (UK 9, TR 32)',
        ],
        '33' => [
            'uk' => '9.5',
            'turkish' => '33',
            'label' => '33 (UK 9.5, TR 33)',
        ],
        '34' => [
            'uk' => '10',
            'turkish' => '34',
            'label' => '34 (UK 10, TR 34)',
        ],
        '35' => [
            'uk' => '10.5',
            'turkish' => '35',
            'label' => '35 (UK 10.5, TR 35)',
        ],
        '36' => [
            'uk' => '3.5',
            'turkish' => '36',
            'label' => '36 (UK 3.5, TR 36)',
        ],
        '37' => [
            'uk' => '4.5',
            'turkish' => '37',
            'label' => '37 (UK 4.5, TR 37)',
        ],
        '38' => [
            'uk' => '5.5',
            'turkish' => '38',
            'label' => '38 (UK 5.5, TR 38)',
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
    'adult_shoe_options' => ['38', '39', '40', '41', '42', '43', '44'],
    'kids_shoe_options' => ['18', '19', '20', '21', '22', '23', '24', '25', '26', '27',
    '28', '29', '30', '31', '32', '33', '34', '35', '36', '37'],
    'shoe_options' => ['38', '39', '40', '41', '42', '43', '44'],

    /**
     * Get mapping for a specific size
     * Usage in code: config('sizes.mappings.M')
     */
];
