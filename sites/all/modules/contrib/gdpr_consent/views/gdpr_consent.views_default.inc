<?php

/**
 * @file
 * Default views for the gdpr_consent module.
 */

/**
 * Implements hook_views_default_views().
 */
function gdpr_consent_views_default_views() {
  /*
   * View 'gdpr_consent_acceptances'
   */

  $view               = new view;
  $view->name         = 'gdpr_consent_terms';
  $view->description  = 'GDPR Consent';
  $view->tag          = 'Consent';
  $view->view_php     = '';
  $view->base_table   = 'gdpr_consent_conditions';
  $view->is_cacheable = FALSE;
  $view->api_version  = 2;
  $view->disabled     = FALSE; /* Edit this to true to make a default view disabled initially */
  $handler            = $view->new_display('default', 'Defaults', 'default');
  $handler->override_option('fields', array(
    'version'    => array(
      'label'        => 'Version',
      'alter'        => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'        => '',
      'hide_empty'   => 0,
      'empty_zero'   => 0,
      'exclude'      => 0,
      'id'           => 'version',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'version',
      'relationship' => 'none',
    ),
    'language'   => array(
      'label'        => 'Language',
      'alter'        => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'        => '',
      'hide_empty'   => 0,
      'empty_zero'   => 0,
      'exclude'      => 0,
      'id'           => 'language',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'language',
      'override'     => array(
        'button' => 'Override',
      ),
      'relationship' => 'none',
    ),
    'revision'   => array(
      'label'        => 'Revision',
      'alter'        => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'        => '',
      'hide_empty'   => 0,
      'empty_zero'   => 0,
      'exclude'      => 0,
      'id'           => 'revision',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'revision',
      'relationship' => 'none',
    ),
    'conditions' => array(
      'label'        => 'Consent',
      'alter'        => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'        => '',
      'hide_empty'   => 0,
      'empty_zero'   => 0,
      'exclude'      => 0,
      'id'           => 'conditions',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'conditions',
      'override'     => array(
        'button' => 'Override',
      ),
      'relationship' => 'none',
    ),
    'changes'    => array(
      'label'        => 'Changes',
      'alter'        => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'        => '',
      'hide_empty'   => 0,
      'empty_zero'   => 0,
      'exclude'      => 0,
      'id'           => 'changes',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'changes',
      'override'     => array(
        'button' => 'Override',
      ),
      'relationship' => 'none',
    ),
    'date'       => array(
      'label'              => 'Date Added',
      'alter'              => array(
        'alter_text'    => 0,
        'text'          => '',
        'make_link'     => 0,
        'path'          => '',
        'link_class'    => '',
        'alt'           => '',
        'prefix'        => '',
        'suffix'        => '',
        'target'        => '',
        'help'          => '',
        'trim'          => 0,
        'max_length'    => '',
        'word_boundary' => 1,
        'ellipsis'      => 1,
        'html'          => 0,
        'strip_tags'    => 0,
      ),
      'empty'              => '',
      'hide_empty'         => 0,
      'empty_zero'         => 0,
      'date_format'        => 'small',
      'custom_date_format' => '',
      'exclude'            => 0,
      'id'                 => 'date',
      'table'              => 'gdpr_consent_conditions',
      'field'              => 'date',
      'override'           => array(
        'button' => 'Override',
      ),
      'relationship'       => 'none',
    ),
  ));
  $handler->override_option('sorts', array(
    'version'  => array(
      'order'        => 'DESC',
      'id'           => 'version',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'version',
      'relationship' => 'none',
    ),
    'language' => array(
      'order'        => 'ASC',
      'id'           => 'language',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'language',
      'relationship' => 'none',
    ),
    'revision' => array(
      'order'        => 'DESC',
      'id'           => 'revision',
      'table'        => 'gdpr_consent_conditions',
      'field'        => 'revision',
      'relationship' => 'none',
      'override'     => array(
        'button' => 'Override',
      ),
    ),
  ));
  $handler->override_option('access', array(
    'type' => 'perm',
    'perm' => 'administer GDPR Consent',
  ));
  $handler->override_option('cache', array(
    'type' => 'none',
  ));
  $handler->override_option('title', 'Consent');
  $handler->override_option('empty', 'No GDPR Consent have been added yet.');
  $handler->override_option('empty_format', '1');
  $handler->override_option('style_plugin', 'table');
  $handler->override_option('style_options', array(
    'grouping' => '',
    'override' => 1,
    'sticky'   => 1,
    'order'    => 'asc',
    'columns'  => array(
      'version'  => 'version',
      'revision' => 'revision',
    ),
    'info'     => array(
      'version'  => array(
        'sortable'  => 1,
        'separator' => '',
      ),
      'revision' => array(
        'sortable'  => 1,
        'separator' => '',
      ),
    ),
    'default'  => '-1',
  ));
  $handler = $view->new_display('page', 'Page', 'page_1');
  $handler->override_option('path', 'admin/config/people/gdpr_consent/consent_history');
  $handler->override_option('menu', array(
    'type'        => 'tab',
    'title'       => 'Consent History',
    'description' => 'Consent',
    'weight'      => '3',
    'name'        => 'navigation',
  ));
  $handler->override_option('tab_options', array(
    'type'        => 'none',
    'title'       => '',
    'description' => '',
    'weight'      => 0,
    'name'        => 'navigation',
  ));

  $view = new view();
  $view->name = 'gdpr_consent_users';
  $view->description = '';
  $view->tag = '';
  $view->base_table = 'users';
  $view->human_name = '';
  $view->core = 0;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Defaults */
  $handler = $view->new_display('default', 'Defaults', 'default');
  $handler->display->display_options['title'] = 'Accepted';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['access']['perm'] = 'administer GDPR Consent';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = 50;
  $handler->display->display_options['style_plugin'] = 'table';
  $handler->display->display_options['style_options']['grouping'] = '';
  $handler->display->display_options['style_options']['columns'] = array(
    'name' => 'name',
    'version' => 'version',
    'language_1' => 'language_1',
    'revision' => 'revision',
    'accepted' => 'accepted',
    'revoked' => 'revoked',
  );
  $handler->display->display_options['style_options']['default'] = '-1';
  $handler->display->display_options['style_options']['info'] = array(
    'name' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'version' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'language_1' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'revision' => array(
      'sortable' => 1,
      'separator' => '',
    ),
    'accepted' => array(
      'sortable' => 1,
      'separator' => '',
    ),
  );
  $handler->display->display_options['style_options']['sticky'] = TRUE;
  $handler->display->display_options['style_options']['order'] = 'desc';
  /* No results behavior: Global: Text area */
  $handler->display->display_options['empty']['text']['id'] = 'area';
  $handler->display->display_options['empty']['text']['table'] = 'views';
  $handler->display->display_options['empty']['text']['field'] = 'area';
  $handler->display->display_options['empty']['text']['content'] = 'No users have accepted GDPR Consent yet.';
  $handler->display->display_options['empty']['text']['format'] = '1';
  /* Field: User: Name */
  $handler->display->display_options['fields']['name']['id'] = 'name';
  $handler->display->display_options['fields']['name']['table'] = 'users';
  $handler->display->display_options['fields']['name']['field'] = 'name';
  $handler->display->display_options['fields']['name']['label'] = 'User';
  /* Field: Consent: Version */
  $handler->display->display_options['fields']['version']['id'] = 'version';
  $handler->display->display_options['fields']['version']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['fields']['version']['field'] = 'version';
  /* Field: Consent: Language */
  $handler->display->display_options['fields']['language_1']['id'] = 'language_1';
  $handler->display->display_options['fields']['language_1']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['fields']['language_1']['field'] = 'language';
  /* Field: Consent: Revision */
  $handler->display->display_options['fields']['revision']['id'] = 'revision';
  $handler->display->display_options['fields']['revision']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['fields']['revision']['field'] = 'revision';
  /* Field: Consent: Time Accepted */
  $handler->display->display_options['fields']['accepted']['id'] = 'accepted';
  $handler->display->display_options['fields']['accepted']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['fields']['accepted']['field'] = 'accepted';
  /* Field: Consent: Time Revoked */
  $handler->display->display_options['fields']['revoked']['id'] = 'revoked';
  $handler->display->display_options['fields']['revoked']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['fields']['revoked']['field'] = 'revoked';
  /* Sort criterion: User: Uid */
  $handler->display->display_options['sorts']['uid']['id'] = 'uid';
  $handler->display->display_options['sorts']['uid']['table'] = 'users';
  $handler->display->display_options['sorts']['uid']['field'] = 'uid';
  $handler->display->display_options['sorts']['uid']['order'] = 'DESC';
  /* Sort criterion: Consent: Version */
  $handler->display->display_options['sorts']['version']['id'] = 'version';
  $handler->display->display_options['sorts']['version']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['sorts']['version']['field'] = 'version';
  $handler->display->display_options['sorts']['version']['order'] = 'DESC';
  /* Sort criterion: Consent: Language */
  $handler->display->display_options['sorts']['language']['id'] = 'language';
  $handler->display->display_options['sorts']['language']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['sorts']['language']['field'] = 'language';
  /* Sort criterion: Consent: Revision */
  $handler->display->display_options['sorts']['revision']['id'] = 'revision';
  $handler->display->display_options['sorts']['revision']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['sorts']['revision']['field'] = 'revision';
  /* Filter criterion: Consent: Version */
  $handler->display->display_options['filters']['version']['id'] = 'version';
  $handler->display->display_options['filters']['version']['table'] = 'gdpr_consent_accepted';
  $handler->display->display_options['filters']['version']['field'] = 'version';
  $handler->display->display_options['filters']['version']['operator'] = 'not empty';
  $handler->display->display_options['filters']['version']['group'] = '0';
  $handler->display->display_options['filters']['version']['expose']['label'] = 'Accepted';
  $handler->display->display_options['filters']['version']['expose']['operator'] = 'version_op';
  $handler->display->display_options['filters']['version']['expose']['identifier'] = 'accepted';
  $handler->display->display_options['filters']['version']['expose']['remember'] = TRUE;

  /* Display: Users */
  $handler = $view->new_display('page', 'Users', 'page_1');
  $handler->display->display_options['path'] = 'admin/config/people/gdpr_consent/users';
  $handler->display->display_options['menu']['type'] = 'tab';
  $handler->display->display_options['menu']['title'] = 'Accepted';
  $handler->display->display_options['menu']['description'] = 'Accepted';
  $handler->display->display_options['menu']['weight'] = '5';

  $views[$view->name] = $view;

  return $views;
}
