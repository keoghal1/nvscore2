<?php

/**
 * @file
 * Lists available colors and color schemes for the Bootstrap Barrio theme.
 */

$info = [
  // Available colors and color labels used in theme.
  'fields' => [
    'brand-primary' => t('Brand Primary'),
    'brand-primary-light' => t('Brand Primary Light'),
    'brand-secondary' => t('Brand Secondary'),
    'brand-secondary-light' => t('Brand Secondary Light'),
    'brand-accent' => t('Brand Accent'),
    'brand-accent-light' => t('Brand Accent Light'),
    'brand-tertiary' => ('Brand Tertiary'),
    'brand-tertiary-light' => ('Brand Tertiary Light'),
    'brand-quaternary' => ('Brand Quaternary'),
    'brand-quaternary-light' => ('Brand Quaternary Light'),
    'brand-quinary' => ('Brand Quinary'),
    'brand-quinary-light' => ('Brand Quinary Light'),
    'neutral-black' => t('Neutral Black'),
    'neutral-dark-grey' => t('Neutral Dark Grey'),
    'neutral-light-grey' => t('Neutral Light Grey'),
    'neutral-white' => t('Neutral White'),
    'utility-positive' => t('Utility Positive'),
    'utility-positive-light' => t('Utility Positive Light'),
    'utility-negative' => t('Utility Negative'),
    'utility-negative-light' => t('Utility Negative Light'),
    'utility-caution' => t('Utility Caution'),
    'utility-caution-light' => t('Utility Caution Light'),
    'utility-neutral' => t('Utility Neutral'),
    'utility-neutral-light' => t('Utility Neutral Light'),
    'text' => t('Text Color'),
    'link' => t('Link Color'),
    'base' => t('Base Color'),
  ],
  // Pre-defined color schemes.
  'schemes' => [
    'default' => [
      'title' => t('Novartis Dolphin(default)'),
      'colors' => [
        'brand-primary' => '#0460a9',
        'brand-primary-light' => '#5092c7',
        'brand-secondary' => '#40c6c6',
        'brand-secondary-light' => '#85e4e4',
        'brand-accent' => '#ff34bb',
        'brand-accent-light' => '#ff86d7',
        'brand-tertiary' => '#023761',
        'brand-tertiary-light' => '#809bb0',
        'brand-quaternary' => '#5291de',
        'brand-quaternary-light' => '#a8c8ee',
        'brand-quinary' => '#8d1f1b',
        'brand-quinary-light' => '#c68f8d',
        'neutral-black' => '#000000',
        'neutral-dark-grey' => '#283138',
        'neutral-light-grey' => '#929292',
        'neutral-white' => '#ffffff',
        'utility-positive' => '#2ecc71',
        'utility-positive-light' => '#d5f5e3',
        'utility-negative' => '#e74c3c',
        'utility-negative-light' => '#f5b7b1',
        'utility-caution' => '#f1c40f',
        'utility-caution-light' => '#fcf3cf',
        'utility-neutral' => '#9b9b9b',
        'utility-neutral-light' => '#ebebeb',
        'text' => '#000000',
        'link' => '#0460a9',
        'base' => '#ffffff',
      ],
    ],
  ],

  // CSS files (excluding @import) to rewrite with new color scheme.
  'css' => [
    'pattern-lab/public/css/styles.css',
    'pattern-lab/public/css/styles-rtl.css',
  ],

  // Files to copy.
  'copy' => [
    'pattern-lab/public/css/styles.css',
    'pattern-lab/public/css/styles-rtl.css',
    'logo.svg',
  ],
];
