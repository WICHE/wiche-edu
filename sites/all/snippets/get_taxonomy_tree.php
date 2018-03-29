<?php
/* Enter the ID of the vocabulary which terms you want to show as headlines */
$vocabulary_id = "5";

print '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
foreach(taxonomy_get_tree($vocabulary_id,0,-1,1) as $value) {
$data .= '  <tr align="left" valign="top">
    <td width="40%"><strong>'.$value->name.'</strong><br>
      '.$value->description.'</td>
    <td>';
	if ( taxonomy_get_children($value->tid) ) {
		$data .='<strong>Subcategories
		</strong><br>';
		foreach( taxonomy_get_children($value->tid) as $child ) {
			$children[] = $child->name;
		}
	}
	$data .= implode(' - ', $children);
    $data .= '</td>
  </tr><tr><td colspan="2" ><hr /></td></tr>';
}
print $data;
print '</table>';
?>