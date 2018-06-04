<?php

/**
 * @file
 * Administration UI for the GDPR Consent module.
 */

/**
 * Module configuration options form.
 */
function gdpr_consent_configuration($form_state) {
  $form = array();

  $form['description'] = array(
    '#markup' => '<p>' . t('Configuration options for GDPR Data processing consent acquisition.') . '</p>',
  );

  $form['gdpr_consent_accept_every_login'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Ask to accept Data processing consent at start every login.'),
    '#description'   => t('User is merely redirected  to give consent but is not forced to do so to continue to use the site. If you want to force accepted consent select the next option.'),
    '#default_value' => variable_get('gdpr_consent_accept_every_login', '0'),
  );

  $form['gdpr_consent_disallow_without'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Force to have accepted consent'),
    '#description'   => t('Users are not allowed to use site at all without consent.'),
    '#default_value' => variable_get('gdpr_consent_disallow_without', '0'),
  );
  $form['gdpr_consent_nag_message'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Nag message for forced consent approval.'),
    '#description'   => t('Nag message shown to users when they are forced to accept consent.'),
    '#default_value' => variable_get('gdpr_consent_nag_message', 'You must give your consent to process your data in order to continue using our services.'),
  );
  $form['except_gdpr_consent'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('Exempt User Roles'),
    '#description' => t('Users with the selected roles will never be shown GDPR Consent.'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
  );

  $all_roles = array_map('check_plain', user_roles());
  $irrelevant_roles = array('anonymous user', 'authenticated user');
  $role_options = array_diff($all_roles, $irrelevant_roles);
  $except_roles = variable_get('gdpr_consent_except_roles', array());

  $form['except_gdpr_consent']['except_roles'] = array(
    '#type'          => 'checkboxes',
    '#title'         => t('Exempt user roles'),
    '#options'       => $role_options,
    '#default_value' => $except_roles,
    '#description'   => t('Do not display GDPR Consent check box for the selected user roles.'),
  );

  gdpr_consent_display_form_element($form);

  $form['save'] = array(
    '#type'  => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

function gdpr_consent_configuration_submit($form, &$form_state) {
  $values = $form_state['values'];

  variable_set('gdpr_consent_accept_every_login', $values['gdpr_consent_accept_every_login']);
  variable_set('gdpr_consent_disallow_without', $values['gdpr_consent_disallow_without']);
  variable_set('gdpr_consent_nag_message', $values['gdpr_consent_nag_message']);

  // Set the excepted user roles.
  if (!empty($values['except_roles'])) {
    $except_roles = array_filter($values['except_roles']);
    variable_set('gdpr_consent_except_roles', $except_roles);
  }

  if (variable_get('gdpr_consent_display', '1') != $values['display']) {
    variable_set('gdpr_consent_display', $values['display']);
    drupal_set_message(t('Display setting has been saved.'));
  }

  if (isset($values['link_target']) && $values['link_target'] != variable_get('gdpr_consent_link_target', '')) {
    variable_set('gdpr_consent_link_target', $values['link_target']);
    drupal_set_message(t('Link target setting has been saved.'));
  }
}

/**
 * Module settings form.
 */
function gdpr_consent_administration($form_state) {
  $form = array();

  if (module_exists('locale')) {
    $languages = locale_language_list();
    $language_default = language_default();
    $language = $language_default->language;
    $version_options = array(
      'version'  => t('All users (new version)'),
      'revision' => t('Language specific users (a revision)'),
    );
    $version_handling = 'version';

    $list = '';
    foreach ($languages as $language_id => $language_name) {
      $list .= '<a href="/' . current_path() . '?lang=' . $language_id . '">' . $language_name . '</a> ';
    }

    $form['language_links'] = array(
      '#type' => 'markup',
      '#markup' => '<strong>Switch language version</strong><br>' . $list,
      '#weight'        => -999,
    );
  }
  else {
    $languages = array('en' => t('English'));
    $language = 'en';
    $version_handling = 'version';
  }

  if(!empty($_GET['lang'])) {
    $language = check_plain($_GET['lang']);
  }
  $conditions = gdpr_consent_get_conditions($language, TRUE);

  $form = array_merge($form, gdpr_consent_display_fields($conditions));

  $form['conditions'] = array(
    '#type'          => 'text_format',
    '#title'         => t('Consent'),
    '#default_value' => $conditions['conditions'],
    '#description'   => t('Your GDPR Consent message for users to approve.'),
    '#format'        => empty($conditions['format']) ? NULL : $conditions['format'],
    '#required'      => TRUE,
    '#rows'          => 20,
    '#weight'        => -1,
  );

  $form['data_details'] = array(
    '#type'          => 'text_format',
    '#title'         => t('Data details'),
    '#default_value' => $conditions['data_details'],
    '#description'   => t("You can describe here what data you're collecting and why."),
    '#format'        => empty($conditions['format_details']) ? NULL : $conditions['format_details'],
    '#required'      => FALSE,
    '#rows'          => 20,
    '#weight'        => 0,
  );

  $form['gdpr_consent']['#tree'] = TRUE;

  $form['gdpr_consent']['gdpr_consent_accept']['#required'] = FALSE;

  if (count($languages) > 1) {
    // Language and version handling options.
    $form['language'] = array(
      '#type'        => 'fieldset',
      '#title'       => t('Language'),
      '#collapsible' => TRUE,
      '#collapsed'   => TRUE,
    );

    $form['language']['language'] = array(
      '#type'          => 'select',
      '#title'         => t('Language'),
      '#options'       => $languages,
      '#default_value' => $language,
    );

    $form['language']['version_handling'] = array(
      '#type'          => 'select',
      '#title'         => t('Ask To Re-accept'),
      '#description'   => t('<strong>All users</strong>: all users will be asked to accept the new version of the Consent, including users who accepted a previous version. This overwrites all language versions if any exists.<br />
                           <strong>Language specific</strong>: only new users, and users who accepted the Consent in the same language as this new revision will be asked to re-accept.'),
      '#options'       => $version_options,
      '#default_value' => $version_handling,
    );
  }
  else {
    $form['language']['language'] = array(
      '#type'  => 'value',
      '#value' => $language,
    );
    $form['language']['version_handling'] = array(
      '#type'  => 'value',
      '#default_value' => $version_handling,
    );

  }

  gdpr_consent_display_form_element($form);

  // Notes about changes to Consent.
  $form['changes'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('Explain Changes'),
    '#description' => t('Explain what changes were made to the data processing consent since the last version. This will only be shown to users who accepted a previous version. Each line will automatically be shown as a bullet point.'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
  );

  $form['changes']['changes'] = array(
    '#type'  => 'textarea',
    '#title' => t('Changes'),
  );

  $form['#after_build'] = array('gdpr_consent_preview');

  $form['preview'] = array(
    '#type'  => 'button',
    '#value' => t('Preview'),
  );

  $form['save'] = array(
    '#type'  => 'submit',
    '#value' => t('Save'),
  );

  if(!empty($form['gdpr_consent']['conditions']['#markup'])) {
    $form['language']['version_handling']['#default_value'] = 'revision';
  }
  return $form;
}

/**
 * Display settings form element used on Consents content and configuration
 * form pages.
 *
 * @param $form
 */
function gdpr_consent_display_form_element(&$form) {
  // Overide display setting.
  $form['display_header'] = array(
    '#type'        => 'fieldset',
    '#title'       => t('Display settings'),
    '#description' => t('How data processing consent should be displayed to users.'),
    '#collapsible' => TRUE,
    '#collapsed'   => TRUE,
  );

  $form['display_header']['display'] = array(
    '#type'          => 'radios',
    '#title'         => t('Display Style'),
    '#default_value' => variable_get('gdpr_consent_display', '1'),
    '#options'       => array(
      t('Scroll Box (CSS - can include HTML)'),
      t('HTML Text'),
      t('Link'),
    ),
    '#required'      => TRUE,
  );

  $link_target_options = array('new_window' => t('New window'));
  $link_target_value = variable_get('gdpr_consent_link_target', 'new_window');

  if (module_exists('lightbox2')) {
    $link_target_options['lightbox2'] = t('Lightbox2 overlay');
  }
  elseif ($link_target_value == 'lightbox2') {
    $link_target_value = 'new_window';
  }

  $form['display_header']['link_target'] = array(
    '#type'          => 'radios',
    '#title'         => t('Link target'),
    '#default_value' => $link_target_value,
    '#options'       => $link_target_options,
    '#description'   => t('How to display the consent when a user clicks on the link.'),
    '#required'      => TRUE,
    '#states'        => array(
      'visible' => array(   // action to take.
        ':input[name="display"]' => array('value' => 3),
      ),
    ),
  );
}

/**
 *  After build function for gdpr_consent_administration form.
 */
function gdpr_consent_preview($form, $form_values) {
  switch ($form['display_header']['display']['#value']) {
    case 1:
      $form['gdpr_consent']['conditions'] = array(
        '#markup' => check_markup($form_values['values']['conditions']['value'], $form_values['values']['conditions']['format']),
      );
      $form['gdpr_consent']['gdpr_consent_accept']['#title'] = t('I give consent to gather and process my data');
      $form['data_details']['#default_value'] = $form['data_details']['value']['#value'];
      if (module_exists('token')) {
        $form['gdpr_consent']['conditions']['#markup'] = token_replace($form['gdpr_consent']['conditions']['#markup']);
        $form['data_details']['#default_value'] = $form['data_details']['value']['#value'];
      }
      break;
    case 2:
      $form['gdpr_consent']['conditions'] = array(
        '#markup' => '',
      );
      $form['gdpr_consent']['data_details'] = array(
        '#markup' => '',
      );
      $form['gdpr_consent']['gdpr_consent_accept']['#title'] = t('I give consent to gather and process my data', array('@terms' => url('gdpr_consent')));
      break;
    default:
      $form['gdpr_consent']['conditions'] = array(
        '#markup' => check_markup($form_values['values']['conditions']['value'], $form_values['values']['conditions']['format']),
      );
      $form['gdpr_consent']['gdpr_consent_accept']['#title'] = t('I give consent to gather and process my data');
      $form['data_details']['#default_value'] = $form['data_details']['value']['#value'];
      if (module_exists('token')) {
        $form['gdpr_consent']['conditions']['#markup'] = token_replace($form['gdpr_consent']['conditions']['#markup']);
        $form['data_details']['#default_value'] = token_replace($form['data_details']['value']['#value']);
      }
      break;
  }

  return $form;
}

function theme_gdpr_consent_administration($variables) {

  $form = $variables['form'];
  $language = '';
  $output = '';

  if (empty($form['current_id']['#value'])) {
    $output .= '<p><strong>' . t('GDPR Consent will not be shown to users, as no consent has been saved.') . '</strong></p>';
  }
  else {
    if (module_exists('locale')) {
      $languages = locale_language_list();
      $language_code = $form['language_value']['#value'];
      $language_name = $languages[$language_code];
    }

    $output .= '<h4>' . t('Most recent version/revision') . '</h4>';
    $output .= '<p>';
    $output .= t('<strong>Version ID:</strong> @version', array('@version' => $form['current_id']['#value'])) . '<br />';
    if (!empty($language_name)) {
      $output .= t('<strong>Language:</strong> @language', array('@language' => $language_name)) . '<br />';
      $output .= t('<strong>Revision:</strong> @revision', array('@revision' => $form['revision_id']['#value'])) . '<br />';
    }
    $output .= t('<strong>Created:</strong> @date', array('@date' => format_date($form['current_date']['#value'], 'long')));
    $output .= '</p>';
  }

  // Preview.
  if (empty($form['gdpr_consent']['conditions']['#markup'])) {
    $output .= drupal_render($form['gdpr_consent']);
  }
  else {
    $form = theme('gdpr_consent_display', array('form' => $form));
    $output .= '<div id="preview">';
    $output .= '<h3>' . t('Preview') . '</h3>';
    $output .= drupal_render($form['gdpr_consent']);
    $output .= '</div>';
  }

  $output .= '<h4>' . t('Create New Version / Translation') . '</h4>';
  $output .= drupal_render_children($form);

  return $output;
}

function gdpr_consent_administration_submit($form, &$form_state) {
  $values = $form_state['values'];

  // Preview request, don't save anything.
  if ($form_state['triggering_element']['#value'] == t('Preview')) {
    return;
  }

  if (variable_get('gdpr_consent_display', '1') != $values['display']) {
    variable_set('gdpr_consent_display', $values['display']);
    drupal_set_message(t('Display setting has been saved.'));
  }

  if (isset($values['link_target'])) {
    variable_set('gdpr_consent_link_target', $values['link_target']);
    drupal_set_message(t('Link target setting has been saved.'));
  }


  // TODO: Add new version to all on new version.
  // If new conditions are different from current permissions, enter in database.
  if (gdpr_consent_conditions_updated($values)) {
    $version = gdpr_consent_version($values['version_handling'], $values['language']);
    if($values['version_handling'] == 'version' && module_exists('locale')) {
      $languages = locale_language_list();

      foreach ($languages as $language_id => $language_name) {
        db_insert('gdpr_consent_conditions')
          ->fields(array(
            'version'        => $version['version'],
            'revision'       => $version['revision'],
            'language'       => $language_id,
            'conditions'     => $values['conditions']['value'],
            'data_details'   => $values['data_details']['value'],
            'date'           => time(),
            'changes'        => $values['changes'],
            'format'         => $values['conditions']['format'],
            'format_details' => $values['data_details']['format'],
          ))
          ->execute();
        drupal_set_message(t('Consent has been saved for language: @language_name', array('@language_name' => $language_name)));
      }
    }
    else {
      db_insert('gdpr_consent_conditions')
        ->fields(array(
          'version'        => $version['version'],
          'revision'       => $version['revision'],
          'language'       => $values['language'],
          'conditions'     => $values['conditions']['value'],
          'data_details'   => $values['data_details']['value'],
          'date'           => time(),
          'changes'        => $values['changes'],
          'format'         => $values['conditions']['format'],
          'format_details' => $values['data_details']['format'],
        ))
        ->execute();
      drupal_set_message(t('Consent has been saved.'));
    }
  }

  // Empty all cache.
  cache_clear_all();
}

/**
 * Check if consents have been updated.
 */
function gdpr_consent_conditions_updated($new) {
  $previous_same_language = gdpr_consent_get_conditions($new['language']);
  $previous = gdpr_consent_get_conditions();

  if (($previous_same_language['conditions'] != $new['conditions']) && ($previous['conditions'] != $new['conditions'])) {
    return TRUE;
  }

  return FALSE;
}

/**
 * Determine version ID of Consent.
 * If it's new version determine next version id,
 * if it's a revision return the ID of the current version increment revision
 * ID by 1.
 */
function gdpr_consent_version($version_handling, $language) {
  $version = (int) db_select('gdpr_consent_conditions', 'lc')
    ->fields('lc', array('version'))
    ->orderBy('version', 'desc')
    ->range(0, 1)
    ->addTag('gdpr_consent_version')
    ->execute()
    ->fetchField();

  // make new version.
  if ($version_handling == 'version') {
    $versioning['version'] = empty($version) ? 1 : $version + 1;
    $versioning['revision'] = 1;
  }

  // make new revision.
  if ($version_handling == 'revision') {

    $revision = db_select('gdpr_consent_conditions', 'lc')
      ->fields('lc', array('revision'))
      ->condition('version', $version)
      ->condition('language', $language)
      ->orderBy('revision', 'DESC')
      ->addTag('gdpr_consent_revision_by_version')
      ->execute()
      ->fetchField();

    $versioning['version'] = $version;
    $versioning['revision'] = empty($revision) ? 1 : $revision + 1;
  }

  return $versioning;
}

/**
 * Languages administration form.
 */
function gdpr_consent_languages() {

  $latest_header = array(t('Language'), t('Version'), t('Revision'));
  $latest_rows = gdpr_consent_versions_latest_get();
  $rows = array();

  foreach ($latest_rows as $language_name => $language) {
    $row = array();
    $row[] = check_plain($language_name);
    $row[] = empty($language['version']) ? '-' : $language['version'];
    $row[] = empty($language['revision']) ? '-' : $language['revision'];
    $rows[] = $row;
  }

  $form['latest'] = array(
    '#type'  => 'fieldset',
    '#title' => t('Latest Version'),
  );

  $form['latest']['#value'] = theme('table', array(
    'header' => $latest_header,
    'rows'   => $rows,
  ));

  return $form;
}

/**
 * Get latest version for each language.
 */
function gdpr_consent_versions_latest_get($language = NULL) {
  $conditions = array();
  $current_version = db_select('gdpr_consent_conditions', 'lc')
    ->fields('lc', array('version'))
    ->orderBy('version', 'DESC')
    ->range(0, 1)
    ->execute()
    ->fetchField();

  // get latest version for each language
  if (empty($language)) {

    $languages = array('en' => t('English'));

    if (module_exists('locale')) {
      $languages = locale_language_list();
    }

    foreach ($languages as $language_id => $language_name) {
      $result = db_select('gdpr_consent_conditions', 'lc')
        ->fields('lc')
        ->condition('version', $current_version)
        ->condition('language', $language_id)
        ->orderBy('revision', 'DESC')
        ->range(0, 1)
        ->addTag('gdpr_consent_conditions_by_language_and_version')
        ->execute()
        ->fetchAllAssoc('tc_id');
      $row = count($result) ? (object) array_shift($result) : FALSE;
      $conditions[$language_name] = gdpr_consent_versions_latest_get_data($row);
    }

  } // get latest version for specific language
  else {
    $result = db_select('gdpr_consent_conditions', 'lc')
      ->fields('lc')
      ->condition('language', $language)
      ->groupBy('language')
      ->orderBy('version', 'DESC')
      ->range(0, 1)
      ->addTag('gdpr_consent_latest_condition_for_language')
      ->execute()
      ->fetchAllAssoc('tc_id');
    $row = count($result) ? (object) array_shift($result) : FALSE;

    $conditions[$language] = gdpr_consent_versions_latest_get_data($row);
  }

  return $conditions;
}

function gdpr_consent_versions_latest_get_data($data) {
  $row['revision'] = isset($data->revision) ? $data->revision : '';
  $row['version'] = isset($data->version) ? $data->version : '';
  $row['language'] = isset($data->language) ? $data->language : '';
  $row['conditions'] = isset($data->conditions) ? $data->conditions : '';
  $row['date'] = isset($data->date) ? $data->date : '';
  $row['changes'] = isset($data->changes) ? $data->changes : '';

  return $row;
}
