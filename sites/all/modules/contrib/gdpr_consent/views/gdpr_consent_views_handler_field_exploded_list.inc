<?php

/**
 * @file
 *  Views field displaying explanations of changes to consent.
 */

/**
 * Implementing archived field.
 */
class gdpr_consent_views_handler_field_exploded_list extends views_handler_field {

  function render($values) {
    $extras = explode("\r\n", $values->{$this->field_alias});

    return theme('item_list', $extras);
  }
}
