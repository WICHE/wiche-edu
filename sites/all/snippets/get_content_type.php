<?php
//declare includes - this gets us the functions for running the users - you have to use ../ to traverse the file tree
// and add the full bootstrap to get drupals functions
chdir('../../../');
require_once('./includes/bootstrap.inc');
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

function get_content($type='page') {
$str = explode(",",$type);
$type='';
foreach ($str as $item) {
	$type .= '\''.$item.'\', ';
}
$type = substr($type,0,strlen($type)-2);
		//Get The description for the issue
		$sql = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created FROM {node} n INNER JOIN {node_revisions} nr ON nr.nid = n.nid WHERE status = 1 AND type IN ($type) ORDER BY n.created DESC LIMIT 0,5 "; //this will limit it to one in the call
		$res = db_query($sql);
		$num_rows = db_num_rows($res);
		//print $num_rows;
		while ($row = db_fetch_object($res)){
			$output .= '<div style="width:100%;">'.theme('get_content',$row).'</div>';
		}
return $output;
}

function theme_get_content($row) {
$str = strip_tags(substr($row->body,0,150));
	$output = '<p><span class="orgtxtmar">'.date("m/d/Y",$row->created).'</span> <span class="txtreg"><em>'.ucwords(str_replace('_',' ',$row->type)).'</em><br /> '.$str.'... </span><br /><span class="txtsm">'.l('read more','node/'.$row->nid).'</span></p>';
	return $output;
}

$output = get_content('spido_policy');
print $output;
?>