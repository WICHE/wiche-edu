<?php

/**
 * Implements hook_rules_event_info().
 */
function gdpr_consent_rules_event_info() {
  return array(
    'gdpr_consent_accepted' => array(
      'label'     => t('After user accepts consent'),
      'group'     => t('User'),
      'variables' => array(
        'account'    => array(
          'type'  => 'user',
          'label' => t('updated user'),
        ),
        'conditions' => array(
          'type'  => 'text',
          'label' => t('GDPR Consent text'),
        ),
        'accepted'   => array(
          'type'  => 'text',
          'label' => t('Timestamp of acceptance'),
        ),
      ),
    ),
  );
}

