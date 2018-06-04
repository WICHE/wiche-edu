<?php

/**
 * @file
 * Page output.
 */

function gdpr_consent_page() {
  $form = drupal_get_form('gdpr_consent_accept_form');
  $output = drupal_render($form);

  return $output;
}
