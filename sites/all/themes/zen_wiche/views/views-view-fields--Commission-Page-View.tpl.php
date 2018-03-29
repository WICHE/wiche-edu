<?php
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>


<div class="commish-group">
<div class="picture"><?php print $fields['field_comm_photo']->content;?></div>
<ul class="comm-lac">
<strong><?php print $fields['field_comm_fname']->content;?> <?php print $fields['field_comm_lname']->content;?></strong></li>
<li class="mem-info"><?php if (!empty($fields['field_comm_office']->content)): print $fields['field_comm_office']->content;?><?php endif ?></li>
<li class="mem-info"><em><?php print $fields['field_comm_title']->content;?></em></li>
<li class="mem-info"><?php if (!empty($fields['field_comm_org'])): print $fields['field_comm_org']->content;?></li>
<li class="mem-info"><?php endif ?> <?php print $fields['field_comm_city']->content;?></li>
</ul>
  </div>


