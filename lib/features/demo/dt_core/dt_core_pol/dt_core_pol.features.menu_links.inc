<?php

/**
 * @file
 * dt_core_pol.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function dt_core_pol_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: menu-dt-breadcrumb-menu_nodetitle:<front>.
  $menu_links['menu-dt-breadcrumb-menu_nodetitle:<front>'] = array(
    'menu_name' => 'menu-dt-breadcrumb-menu',
    'link_path' => '<front>',
    'router_path' => '',
    'link_title' => '[node:title]',
    'options' => array(
      'alter' => TRUE,
      'menu_token_link_path' => '<front>',
      'menu_token_data' => array(
        'node' => array(
          'type' => 'node',
          'plugin' => 'node_user_defined',
          'options' => array(
            'id' => 88,
          ),
        ),
      ),
      'menu_token_options' => array(
        'clear' => 0,
      ),
      'menu_token_link_uuid' => '<front>',
      'attributes' => array(
        'data-display-title' => 1,
        'data-hide-children' => 0,
        'data-remove-class' => 1,
      ),
      'item_attributes' => array(
        'data-image' => '',
        'data-display-title' => 1,
        'data-hide-children' => 0,
        'id' => '',
        'class' => '',
        'data-remove-class' => 0,
        'style' => '',
      ),
      'identifier' => 'menu-dt-breadcrumb-menu_nodetitle:<front>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => 1,
    'customized' => 1,
    'language' => 'und',
    'menu_links_customized' => 1,
  );
  // Exported menu link: menu-dt-breadcrumb-menu_sitename:menutoken/569a3b55ae73a.
  $menu_links['menu-dt-breadcrumb-menu_sitename:menutoken/569a3b55ae73a'] = array(
    'menu_name' => 'menu-dt-breadcrumb-menu',
    'link_path' => 'menutoken/569a3b55ae73a',
    'router_path' => 'menutoken/%',
    'link_title' => '[site:name]',
    'options' => array(
      'alter' => TRUE,
      'menu_token_data' => array(),
      'menu_token_options' => array(
        'clear' => 0,
      ),
      'menu_token_link_uuid' => 'menutoken/569a3b55ae73a',
      'menu_token_link_path' => 'http://ec.europa.eu/index_[dt_tokens:dt_interface_language].htm',
      'attributes' => array(
        'data-display-title' => 1,
        'data-hide-children' => 0,
        'data-remove-class' => 1,
      ),
      'item_attributes' => array(
        'data-image' => '',
        'data-display-title' => 1,
        'data-hide-children' => 0,
        'id' => '',
        'class' => '',
        'data-remove-class' => 0,
        'style' => '',
      ),
      'identifier' => 'menu-dt-breadcrumb-menu_sitename:menutoken/569a3b55ae73a',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => 0,
    'customized' => 1,
    'language' => 'und',
    'menu_links_customized' => 1,
  );
  // Exported menu link: menu-dt-menu-social-media_facebook:https://www.facebook.com/EuropeanCommission.
  $menu_links['menu-dt-menu-social-media_facebook:https://www.facebook.com/EuropeanCommission'] = array(
    'menu_name' => 'menu-dt-menu-social-media',
    'link_path' => 'https://www.facebook.com/EuropeanCommission',
    'router_path' => '',
    'link_title' => 'Facebook',
    'options' => array(
      'identifier' => 'menu-dt-menu-social-media_facebook:https://www.facebook.com/EuropeanCommission',
      'alter' => TRUE,
      'attributes' => array(
        'data-display-title' => 1,
        'class' => array(
          0 => 'icon',
          1 => 'icon--facebook',
          2 => 'footer__icon--facebook',
          3 => 'footer__icon--social',
        ),
        'data-remove-class' => 0,
      ),
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -50,
    'customized' => 1,
    'language' => 'und',
    'menu_links_customized' => 1,
  );
  // Exported menu link: menu-dt-menu-social-media_other-social-media:http://ec.europa.eu/dgs/communication/services/journalist/social-media_en.htm.
  $menu_links['menu-dt-menu-social-media_other-social-media:http://ec.europa.eu/dgs/communication/services/journalist/social-media_en.htm'] = array(
    'menu_name' => 'menu-dt-menu-social-media',
    'link_path' => 'http://ec.europa.eu/dgs/communication/services/journalist/social-media_en.htm',
    'router_path' => '',
    'link_title' => 'Other social media',
    'options' => array(
      'attributes' => array(
        'data-display-title' => 1,
        'data-remove-class' => 0,
      ),
      'identifier' => 'menu-dt-menu-social-media_other-social-media:http://ec.europa.eu/dgs/communication/services/journalist/social-media_en.htm',
      'alter' => TRUE,
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -48,
    'customized' => 1,
    'language' => 'und',
    'menu_links_customized' => 1,
  );
  // Exported menu link: menu-dt-menu-social-media_twitter:https://twitter.com/eu_commission.
  $menu_links['menu-dt-menu-social-media_twitter:https://twitter.com/eu_commission'] = array(
    'menu_name' => 'menu-dt-menu-social-media',
    'link_path' => 'https://twitter.com/eu_commission',
    'router_path' => '',
    'link_title' => 'Twitter',
    'options' => array(
      'identifier' => 'menu-dt-menu-social-media_twitter:https://twitter.com/eu_commission',
      'alter' => TRUE,
      'attributes' => array(
        'data-display-title' => 1,
        'class' => array(
          0 => 'icon',
          1 => 'icon--twitter',
          2 => 'footer__icon--twitter',
          3 => 'footer__icon--social',
        ),
        'data-remove-class' => 0,
      ),
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -49,
    'customized' => 1,
    'language' => 'und',
    'menu_links_customized' => 1,
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Facebook');
  t('Other social media');
  t('Twitter');
  t('[node:title]');
  t('[site:name]');

  return $menu_links;
}
