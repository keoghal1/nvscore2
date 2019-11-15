<?php

/**
 * @file
 * theme-settings.php
 *
 * Provides theme settings for Novartis Dolphin when admin theme is not.
 *
 * @see ./includes/settings.inc
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function dolphin_form_system_theme_settings_alter(&$form, FormStateInterface $form_state, $form_id = NULL) {
  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  // @see http://drupal.org/node/943212
  if (!isset($form_id)) {
    $search_background_options = [
      'brand-primary' => t('Brand Primary'),
      'brand-primary-light' => t('Brand Primary Light'),
      'brand-secondary' => t('Brand Secondary'),
      'brand-secondary-light' => t('Brand Secondary Light'),
      'brand-accent' => t('Brand Accent'),
      'brand-accent-light' => t('Brand Accent Light'),
      'brand-tertiary' => ('Brand Tertiary'),
      'brand-tertiary-light' => ('Brand Tertiary Light'),
      'brand-quaternary' => ('Brand Quaternary'),
      'brand-quaternary-light' => ('Brand quaternary Light'),
      'brand-quinary' => ('Brand Quinary'),
      'brand-quinary-light' => ('Brand Quinary Light'),
      'black' => t('Neutral Black'),
      'white' => t('Neutral White'),
      'neutral-dark-grey' => t('Neutral Dark Grey'),
      'neutral-light-grey' => t('Neutral Light Grey'),
    ];
    $search_background_options_border = [
      'brand-primary-border' => t('Brand Primary'),
      'brand-primary-light-border' => t('Brand Primary Light'),
      'brand-secondary-border' => t('Brand Secondary'),
      'brand-secondary-light-border' => t('Brand Secondary Light'),
      'brand-accent-border' => t('Brand Accent'),
      'brand-accent-light-border' => t('Brand Accent Light'),
      'brand-tertiary-border' => ('Brand Tertiary'),
      'brand-tertiary-light-border' => ('Brand Tertiary Light'),
      'brand-quaternary-border' => ('Brand Quaternary'),
      'brand-quaternary-light-border' => ('Brand quaternary Light'),
      'brand-quinary-border' => ('Brand Quinary'),
      'brand-quinary-light-border' => ('Brand Quinary Light'),
      'black-border' => t('Neutral Black'),
      'white-border' => t('Neutral White'),
      'neutral-dark-grey-border' => t('Neutral Dark Grey'),
      'neutral-light-grey-border' => t('Neutral Light Grey'),
    ];
    $search_background_options_text = [
      'brand-primary-text' => t('Brand Primary'),
      'brand-primary-light-text' => t('Brand Primary Light'),
      'brand-secondary-text' => t('Brand Secondary'),
      'brand-secondary-light-text' => t('Brand Secondary Light'),
      'brand-accent-text' => t('Brand Accent'),
      'brand-accent-light-text' => t('Brand Accent Light'),
      'brand-tertiary-text' => ('Brand Tertiary'),
      'brand-tertiary-light-text' => ('Brand Tertiary Light'),
      'brand-quaternary-text' => ('Brand Quaternary'),
      'brand-quaternary-light-text' => ('Brand quaternary Light'),
      'brand-quinary-text' => ('Brand Quinary'),
      'brand-quinary-light-text' => ('Brand Quinary Light'),
      'black-text' => t('Neutral Black'),
      'white-text' => t('Neutral White'),
      'neutral-dark-grey-text' => t('Neutral Dark Grey'),
      'neutral-light-grey-text' => t('Neutral Light Grey'),
    ];
    $background_color_options = [
      'default' => t('Default'),
      'brand-primary' => t('Brand Primary'),
      'brand-primary-light' => t('Brand Primary Light'),
      'brand-secondary' => t('Brand Secondary'),
      'brand-secondary-light' => t('Brand Secondary Light'),
      'brand-accent' => t('Brand Accent'),
      'brand-accent-light' => t('Brand Accent Light'),
      'brand-tertiary' => ('Brand Tertiary'),
      'brand-tertiary-light' => ('Brand Tertiary Light'),
      'brand-quaternary' => ('Brand Quaternary'),
      'brand-quaternary-light' => ('Brand quaternary Light'),
      'brand-quinary' => ('Brand Quinary'),
      'brand-quinary-light' => ('Brand Quinary Light'),
      'neutral-black' => t('Neutral Black'),
      'neutral-white' => t('Neutral White'),
      'neutral-dark-grey' => t('Neutral Dark Grey'),
      'neutral-light-grey' => t('Neutral Light Grey'),
      'transparent' => t('Transparent'),
    ];
    $background_brand_color_options = [
      'brand-primary' => t('Brand Primary'),
      'brand-secondary' => t('Brand Secondary'),
      'brand-accent' => t('Brand Accent'),
      'brand-tertiary' => ('Brand Tertiary'),
      'brand-quaternary' => ('Brand Quaternary'),
      'brand-quinary' => ('Brand Quinary'),
      'neutral-black' => t('Neutral Black'),
      'neutral-white' => t('Neutral White'),
      'neutral-dark-grey' => t('Neutral Dark Grey'),
    ];
    $text_color_options = [
      'text-theme-default' => t('Brand Color Scheme'),
      'text-theme-dark' => t('Dark Color Scheme'),
      'text-theme-light' => t('Light Color Scheme'),
    ];
    $border_width = [
      'border-width-0' => t('0'),
      'border-width-1' => t('1'),
      'border-width-2' => t('2'),
      'border-width-3' => t('3'),
      'border-width-4' => t('4'),
      'border-width-5' => t('5'),
      'border-width-6' => t('6'),
      'border-width-7' => t('7'),
      'border-width-8' => t('8'),
      'border-width-9' => t('9'),
      'border-width-10' => t('10'),
    ];
    $color_options = [
      'brand-primary-text' => t('Brand Primary'),
      'brand-secondary-text' => t('Brand Secondary'),
      'brand-accent-text' => t('Brand Accent'),
      'white-text' => t('Neutral White'),
      'black-text' => t('Neutral Black'),
    ];
    $opacity = [
      'opacity-1' => t('0.1'),
      'opacity-2' => t('0.2'),
      'opacity-3' => t('0.3'),
      'opacity-4' => t('0.4'),
      'opacity-5' => t('0.5'),
      'opacity-6' => t('0.6'),
      'opacity-7' => t('0.7'),
      'opacity-8' => t('0.8'),
      'opacity-9' => t('0.9'),
      'opacity-10' => t('1.0'),
    ];
    $background_color_options_generic = [
      'brand-primary' => t('Brand Primary'),
      'brand-secondary' => t('Brand Secondary'),
      'brand-accent' => t('Brand Accent'),
      'white' => t('Neutral White'),
      'black' => t('Neutral Black'),
    ];
    $border_color = [
      'brand-primary-border' => t('Brand Primary'),
      'brand-secondary-border' => t('Brand Secondary'),
      'brand-accent-border' => t('Brand Accent'),
      'white-border' => t('Neutral White'),
      'black-border' => t('Neutral Black'),
    ];
    $form['components']['expertcta'] = [
      '#type' => 'details',
      '#title' => t('Expert CTA'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];

    $form['components']['expertcta']['novartis_dolphin_expertcta'] = [
      '#type' => 'textfield',
      '#title' => t('Border radius for expert CTA'),
      '#default_value' => theme_get_setting('novartis_dolphin_expertcta'),
      '#description' => t('Enter the value in px e.g. 3px'),
    ];

    $form['components']['expertcta']['novartis_dolphin_expertcta_color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select Expert CTA Text color.'),
      '#default_value' => theme_get_setting('novartis_dolphin_expertcta_color'),
      '#options' => $text_color_options,
    ];

    $form['components']['header'] = [
      '#type' => 'details',
      '#title' => t('header'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['header']['utility_nav'] = [
      '#type' => 'details',
      '#title' => t('Utility Nav'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['header']['utility_nav']['novartis_dolphin_utility_nav_background_color_pre'] = [
      '#type' => 'select',
      '#title' => t('Pre Interaction Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_utility_nav_background_color_pre'),
      '#options' => $background_color_options,
    ];
    $form['components']['header']['utility_nav']['novartis_dolphin_utility_nav_background_color_post'] = [
      '#type' => 'select',
      '#title' => t('Post Interaction Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_utility_nav_background_color_post'),
      '#options' => $background_color_options,
    ];
    $form['components']['header']['utility_nav']['utility_nav_pre_color_scheme'] = [
      '#type' => 'select',
      '#title' => t('Pre Interaction Text Color'),
      '#default_value' => theme_get_setting('utility_nav_pre_color_scheme'),
      '#options' => $text_color_options,
    ];
    $form['components']['header']['utility_nav']['utility_nav_post_color_scheme'] = [
      '#type' => 'select',
      '#title' => t('Post Interaction Text Color'),
      '#default_value' => theme_get_setting('utility_nav_post_color_scheme'),
      '#options' => $text_color_options,
    ];

    $form['components']['header']['utility_nav']['utility_nav_opacity'] = [
      '#type' => 'select',
      '#title' => t('Opacity'),
      '#default_value' => theme_get_setting('utility_nav_opacity'),
      '#options' => $opacity,
    ];

    $form['components']['header']['novartis_dolphin_sticky_header'] = [
      '#type' => 'checkbox',
      '#title' => t('Sticky Header'),
      '#default_value' => theme_get_setting('novartis_dolphin_sticky_header'),
      '#description' => t('If checked, Header will become sticky.'),
    ];
    $form['components']['header']['novartis_dolphin_pre_interaction_settings'] = [
      '#type' => 'fieldgroup',
      '#title' => t('Pre-Interaction Settings'),
      '#description' => t('Utilized if the Header is not sticky. If the Header is sticky, the these settings are utilized on page load, before the user scrolls, or if user scrolls back to the top of the screen.'),
    ];
    $form['components']['header']['novartis_dolphin_post_interaction_settings'] = [
      '#type' => 'fieldgroup',
      '#title' => t('Post-Interaction Settings'),
      '#description' => t('Utilized when the user scrolls away from the top of screen (sticky header only), or in all cases if the user interacts with drop-down.'),
    ];
    $form['components']['header']['novartis_dolphin_pre_interaction_settings']['novartis_dolphin_header_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_header_background_color'),
      '#options' => $background_color_options,
    ];

    $form['components']['header']['novartis_dolphin_post_interaction_settings']['novartis_dolphin_header_background_color_post'] = [
      '#type' => 'select',
      '#title' => t('Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_header_background_color_post'),
      '#options' => $background_color_options,
    ];

    $form['components']['header']['novartis_dolphin_pre_interaction_settings']['novartis_dolphin_header_color'] = [
      '#type' => 'select',
      '#title' => t('Text Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_header_color'),
      '#options' => $text_color_options,
    ];

    $form['components']['header']['novartis_dolphin_post_interaction_settings']['novartis_dolphin_header_color_post'] = [
      '#type' => 'select',
      '#title' => t('Text Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_header_color_post'),
      '#options' => $text_color_options,
    ];
    $form['components']['header']['header_search'] = [
      '#type' => 'details',
      '#title' => t('Header Search'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['header']['header_search']['header_search_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('header_search_background_color'),
      '#options' => $search_background_options,
    ];
    $form['components']['header']['header_search']['header_search_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('header_search_border_color'),
      '#options' => $search_background_options,
    ];
    $form['components']['header']['header_search']['header_search_opacity'] = [
      '#type' => 'select',
      '#title' => t('Opacity'),
      '#default_value' => theme_get_setting('header_search_opacity'),
      '#options' => $opacity,
    ];
    $form['components']['header']['header_search']['header_search_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text Color'),
      '#default_value' => theme_get_setting('header_search_text_color'),
      '#options' => $color_options,
    ];
    $form['components']['footer'] = [
      '#type' => 'details',
      '#title' => t('Footer'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['footer']['footer_nav_title_color'] = [
      '#type' => 'select',
      '#title' => t('Footer Nav Title Color'),
      '#default_value' => theme_get_setting('footer_nav_title_color'),
      '#options' => $background_color_options,
    ];
    $form['components']['footer']['footer_tier_one_social_legal_color'] = [
      '#type' => 'select',
      '#title' => t('Footer Tier One, Social and Legal Nav Color'),
      '#default_value' => theme_get_setting('footer_tier_one_social_legal_color'),
      '#options' => $background_brand_color_options,
    ];
    $form['components']['footer']['footer_tier_two_color'] = [
      '#type' => 'select',
      '#title' => t('Footer Tier Two Color'),
      '#default_value' => theme_get_setting('footer_tier_two_color'),
      '#options' => $background_brand_color_options,
    ];
    $form['colors']['content'] = [
      '#type' => 'details',
      '#title' => t('Content container'),
      '#collapsible' => TRUE,
      '#collapsed' => FALSE,
      '#description' => t('Provide color code for these background color themes.'),
    ];
    $form['colors']['content']['novartis_dolphin_content_bg_color_default'] = [
      '#type' => 'textfield',
      '#title' => t('Default Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_content_bg_color_default'),
    ];
    $form['colors']['content']['novartis_dolphin_content_bg_color_dark'] = [
      '#type' => 'textfield',
      '#title' => t('Dark Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_content_bg_color_dark'),
    ];
    $form['colors']['content']['novartis_dolphin_content_bg_color_light'] = [
      '#type' => 'textfield',
      '#title' => t('Light Background Color'),
      '#default_value' => theme_get_setting('novartis_dolphin_content_bg_color_light'),
    ];

    // Buttons.
    $form['components']['buttons'] = [
      '#type' => 'details',
      '#title' => t('Buttons'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['buttons']['primary'] = [
      '#type' => 'details',
      '#title' => t('Primary'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['buttons']['primary']['dolphin_primary_button_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('dolphin_primary_button_background_color'),
      '#options' => $background_color_options_generic,
    ];
    $form['components']['buttons']['primary']['dolphin_primary_button_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('dolphin_primary_button_border_color'),
      '#options' => $border_color,
    ];
    $form['components']['buttons']['primary']['dolphin_primary_button_border_width'] = [
      '#type' => 'select',
      '#title' => t('Border width'),
      '#default_value' => theme_get_setting('dolphin_primary_button_border_width'),
      '#options' => $border_width,
    ];
    $form['components']['buttons']['primary']['dolphin_primary_button_border_radius'] = [
      '#type' => 'number',
      '#title' => t('Border radius'),
      '#default_value' => theme_get_setting('dolphin_primary_button_border_radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['buttons']['primary']['dolphin_primary_button_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text color'),
      '#default_value' => theme_get_setting('dolphin_primary_button_text_color'),
      '#options' => $color_options,
    ];
    $form['components']['buttons']['secondary'] = [
      '#type' => 'details',
      '#title' => t('Secondary'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['buttons']['secondary']['dolphin_secondary_button_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('dolphin_secondary_button_background_color'),
      '#options' => $background_color_options_generic,
    ];
    $form['components']['buttons']['secondary']['dolphin_secondary_button_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('dolphin_secondary_button_border_color'),
      '#options' => $border_color,
    ];
    $form['components']['buttons']['secondary']['dolphin_secondary_button_border_width'] = [
      '#type' => 'select',
      '#title' => t('Border width'),
      '#default_value' => theme_get_setting('dolphin_secondary_button_border_width'),
      '#options' => $border_width,
    ];
    $form['components']['buttons']['secondary']['dolphin_secondary_button_border_radius'] = [
      '#type' => 'number',
      '#title' => t('Border radius'),
      '#default_value' => theme_get_setting('dolphin_secondary_button_border_radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['buttons']['secondary']['dolphin_secondary_button_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text color'),
      '#default_value' => theme_get_setting('dolphin_secondary_button_text_color'),
      '#options' => $color_options,
    ];

    $form['components']['buttons']['tertiary'] = [
      '#type' => 'details',
      '#title' => t('Tertiary'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['buttons']['tertiary']['dolphin_tertiary_button_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('dolphin_tertiary_button_background_color'),
      '#options' => $background_color_options_generic,
    ];
    $form['components']['buttons']['tertiary']['dolphin_tertiary_button_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('dolphin_tertiary_button_border_color'),
      '#options' => $border_color,
    ];
    $form['components']['buttons']['tertiary']['dolphin_tertiary_button_border_width'] = [
      '#type' => 'select',
      '#title' => t('Border width'),
      '#default_value' => theme_get_setting('dolphin_tertiary_button_border_width'),
      '#options' => $border_width,
    ];
    $form['components']['buttons']['tertiary']['dolphin_tertiary_button_border_radius'] = [
      '#type' => 'number',
      '#title' => t('Border radius'),
      '#default_value' => theme_get_setting('dolphin_tertiary_button_border_radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['buttons']['tertiary']['dolphin_tertiary_button_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text color'),
      '#default_value' => theme_get_setting('dolphin_tertiary_button_text_color'),
      '#options' => $color_options,
    ];
    $form['components']['search-paragraph'] = [
      '#type' => 'details',
      '#title' => t('Search Paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['search-paragraph']['default-dark'] = [
      '#type' => 'details',
      '#title' => t('Default/Dark Variant'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_background_color'),
      '#options' => $search_background_options,
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_opacity'] = [
      '#type' => 'select',
      '#title' => t('Background color opacity'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_opacity'),
      '#options' => $opacity,
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_border_color'),
      '#options' => $search_background_options_border,
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_border_width'] = [
      '#type' => 'select',
      '#title' => t('Border width'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_border_width'),
      '#options' => $border_width,
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_border_radius'] = [
      '#type' => 'number',
      '#title' => t('Border radius'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_border_radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['search-paragraph']['default-dark']['dolphin_search_paragraph_default_dark_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_default_dark_text_color'),
      '#options' => $search_background_options_text,
    ];

    $form['components']['search-paragraph']['light'] = [
      '#type' => 'details',
      '#title' => t('Light Variant'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_background_color'] = [
      '#type' => 'select',
      '#title' => t('Background color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_background_color'),
      '#options' => $search_background_options,
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_opacity'] = [
      '#type' => 'select',
      '#title' => t('Background color opacity'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_opacity'),
      '#options' => $opacity,
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_border_color'] = [
      '#type' => 'select',
      '#title' => t('Border color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_border_color'),
      '#options' => $search_background_options_border,
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_border_width'] = [
      '#type' => 'select',
      '#title' => t('Border width'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_border_width'),
      '#options' => $border_width,
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_border_radius'] = [
      '#type' => 'number',
      '#title' => t('Border radius'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_border_radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['search-paragraph']['light']['dolphin_search_paragraph_light_text_color'] = [
      '#type' => 'select',
      '#title' => t('Text color'),
      '#default_value' => theme_get_setting('dolphin_search_paragraph_light_text_color'),
      '#options' => $search_background_options_text,
    ];

    $form['components']['paragraphs'] = [
      '#type' => 'details',
      '#title' => t('Views content block'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['paragraphs']['paragraph-views-content-border-radius'] = [
      '#type' => 'number',
      '#title' => t('Views content block border radius'),
      '#default_value' => theme_get_setting('paragraph-views-content-border-radius'),
      '#description' => t('Enter the numeric value.'),
    ];
    $form['components']['form']['input-border-radius'] = [
      '#type' => 'textfield',
      '#title' => t('Input border radius'),
      '#default_value' => theme_get_setting('input-border-radius'),
      '#description' => t('Enter the value in px e.g. 3px'),
    ];
    $form['components']['dropdown'] = [
      '#type' => 'details',
      '#title' => t('Dropdown'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['dropdown']['dropdown-border-radius'] = [
      '#type' => 'textfield',
      '#title' => t('Dropdown border radius'),
      '#default_value' => theme_get_setting('dropdown-border-radius'),
      '#description' => t('Enter the value in px e.g. 3px'),
    ];
    $form['components']['image'] = [
      '#type' => 'details',
      '#title' => t('Image paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['image']['image-border-radius'] = [
      '#type' => 'textfield',
      '#title' => t('Image paragraph border radius'),
      '#default_value' => theme_get_setting('image-border-radius'),
      '#description' => t('Enter the value in px e.g. 3px'),
    ];
    $form['components']['video-paragraph'] = [
      '#type' => 'details',
      '#title' => t('Video paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['video-paragraph']['video-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select video paragraph Text color.'),
      '#default_value' => theme_get_setting('video-text-color'),
      '#options' => $text_color_options,
    ];

    $form['components']['view-blocks-paragraph'] = [
      '#type' => 'details',
      '#title' => t('View Blocks paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['view-blocks-paragraph']['view-blocks-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select views blocks paragraph Header Text color.'),
      '#default_value' => theme_get_setting('view-blocks-text-color'),
      '#options' => $text_color_options,
    ];
    $form['components']['banner-paragraph'] = [
      '#type' => 'details',
      '#title' => t('Banner paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['banner-paragraph']['banner-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select banner paragraph Text color.'),
      '#default_value' => theme_get_setting('banner-text-color'),
      '#options' => $text_color_options,
    ];

    $form['components']['hero-slider'] = [
      '#type' => 'details',
      '#title' => t('Hero Slider'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['hero-slider']['hero-text-color'] = [
      '#type' => 'select',
      '#title' => t('Set default Text Color for Slides in Hero Sliders'),
      '#description' => t('Select Hero Slider Text color.'),
      '#default_value' => theme_get_setting('hero-text-color'),
      '#options' => $text_color_options,
    ];
    $form['components']['simple-text-paragraph'] = [
      '#type' => 'details',
      '#title' => t('simple text paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['simple-text-paragraph']['simple-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select simple text paragraph Text color.'),
      '#default_value' => theme_get_setting('simple-text-color'),
      '#options' => $text_color_options,
    ];
    $form['components']['node-experts'] = [
      '#type' => 'details',
      '#title' => t('Node expert'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['node-experts']['expert-background-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default background color'),
      '#description' => t('Select node expert Text color.'),
      '#default_value' => theme_get_setting('expert-background-color'),
      '#options' => [
        'default' => t('Brand'),
        'gradient-primary' => t('Gradient Primary'),
        'gradient-secondary' => t('Gradient Secondary'),
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
        'neutral-dark-grey' => t('Neutral Dark Grey'),
        'neutral-light-grey' => t('Neutral Light Grey'),
        'utility-positive' => t('Utility Positive'),
        'utility-positive-light' => t('Utility Positive Light'),
        'utility-negative' => t('Utility Negative'),
        'utility-negative-light' => t('Utility Negative Light'),
        'utility-caution' => t('Utility Caution'),
        'utility-caution-light' => t('Utility Caution Light'),
        'utility-neutral' => t('Utility Neutral'),
        'utility-neutral-light' => t('Utility Neutral Light'),
      ],
    ];
    $form['components']['node-experts']['expert-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select node expert Text color.'),
      '#default_value' => theme_get_setting('expert-text-color'),
      '#options' => $text_color_options,
    ];
    $form['components']['page-content-block-links'] = [
      '#type' => 'details',
      '#title' => t('Page and content block links paragraph'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['page-content-block-links']['page-content-block-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select page and content block links paragraph description Text color.'),
      '#default_value' => theme_get_setting('page-content-block-text-color'),
      '#options' => $text_color_options,
    ];
    $form['components']['content-container'] = [
      '#type' => 'details',
      '#title' => t('Content container'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    ];
    $form['components']['content-container']['content-container-text-color'] = [
      '#type' => 'select',
      '#title' => t('Choose the default text color'),
      '#description' => t('Select content container Text color. This will apply only when gradient type is selected on content container.'),
      '#default_value' => theme_get_setting('content-container-text-color'),
      '#options' => $text_color_options,
    ];
  }
}
