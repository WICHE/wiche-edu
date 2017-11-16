<?php
//declare includes - this gets us the functions for running the users - you have to use ../ to traverse the file tree
// and add the full bootstrap to get drupals functions
//chdir('../../../');
//require_once('./includes/bootstrap.inc');
//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

/*
* Inputs:
* $type = 'spido_policy'
* query string args()
* arg(0) = spido
* arg(1) = CO (a state) or a list of states
* arg(2) = category search string (root+child+child+root+child) and so on.
* arg(3) = 0 for single state; CA+CO string for multi-state search
* arg(4) = pageSize
* arg(5) = export value
*
*/

function get_spido_content($type='spido_policy', $export=false) {
//dpm("start get_spido_content");

	//validate
	if (arg(0) != 'spido') {
		return;
		//todo - display error page
	}

	//process state search arguments
	//arg 1 = search for a single state
	//arg 3 = search for a list of states (when user chooses view all)
	if ( arg(3) ) {
		//dpm("Have arg 3");
		//$searchState = str_replace(" ", ",",arg(3));	
		$stateArgs = explode(" ",arg(3));
		$searchState = "";
		for ($i=0; $i<count($stateArgs); $i++) {
			if ($searchState != ""){
				$searchState .=",";
			}
			$searchState .= "'" . $stateArgs[$i] . "'";
		}
		//dpm("searchState:" . $searchState);
		//$searchState = arg(1);
	}
	else if (arg(1) ) {
	    $searchState = "'".arg(1)."'";	
		//$stateArgs = explode(" ",arg(1));
	}
	
	
	//dpm("arg4:".arg(4));
	
	//process category search arguement	
	//arg2 - category ids to search for
	//$parents = root category ids (Accelerated Learning)
	//$issues = subcategory ids (a child of Accelerated Learning)
	if (arg(2)  && (arg(2) != "*")) {
		//dpm("arg2:" . arg(2));
		
		//@todo this broke when we moved to Linux.
		//$arg2_arr = explode("+",arg(2));
		$arg2_arr = explode(" ",arg(2));
		//key = index
		//value = tid
		foreach ($arg2_arr as $key => $value) {
			//print "key:" . $key . " value:" . $value .'<br />';
			$parent = taxonomy_get_parents($value);
			$parent_key = key($parent);
			$str = $parent["$parent_key"]->tid;
			//print "parent: " .$str .'<br />';
			if (empty($parent)) {
				//add parent category			
				$parents["$value"] = '';
			} else {
				//add subcategory
				$issues["$value"] = '';
			}
		}
		
	} else {
		//default search to all parent tids when none are provided.
		//TODO read policy taxonomy nodes from the db
		//print_r("all issues processing");
		
		//todo: this needs to change to all subcats of all parents!
		$issues = array(16=>'',23=>'',31=>'',45=>'',46=>'',47=>'',48=>'',49=>'',50=>'',51=>'',52=>'',53=>'');
		
		//todo: query db for these values
		$parents = array(16=>'',23=>'',31=>'',45=>'',46=>'',47=>'',48=>'',49=>'',50=>'',51=>'',52=>'',53=>'');
		
		//$vid = 5;  //SPIDO Domains vocabulary ID
		//$parent = 0;
		//$depth = -1;
		//$max_depth = 1;
		//$parents = taxonomy_get_tree($vid, $parent, $depth, $max_depth);

		//todo: get the tid for each term node in the result set
		//dpm($parents[0]->tid);
		
	}
	
	$pageSize = arg(4);
	$export = arg(5);
	//dpm($export);
	
	//build the search strings
	//then you got to get the category ids into an array
	foreach ($issues as $key=>$value) {
		$filtered_issues[] = $key;
	}
	$filtered_issues = implode(",",$filtered_issues);

	foreach ($parents as $key=>$value) {
		$parent_issues[] = $key;
	}
	$parent_issues = implode(",",$parent_issues);
	//dpm('parentissues:' . $parent_issues);	
		
//So, the arguments have to piped into here arg(0) = spido, arg(1) = state, arg(2) = the terms is is in
//The start query is always the same, find the state - then look at the
	

$sql = "SELECT * FROM ((SELECT * FROM (SELECT nr.title, nr.nid, tn.tid, td.name, th.parent, case th.parent when 0 then td.name else thd.name end as parentName, field_states_value as field_states, n.type FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid
LEFT JOIN wiche_term_node tn ON nr.nid = tn.nid
LEFT JOIN wiche_term_data td ON tn.tid = td.tid
LEFT JOIN wiche_term_hierarchy th ON th.tid = td.tid
LEFT JOIN wiche_term_data thd ON th.parent = thd.tid
LEFT JOIN wiche_content_type_spido_policy ctsp ON ctsp.nid = n.nid WHERE status = 1 AND n.type IN ('spido_policy')
AND field_states_value IN (".$searchState.") AND tn.tid IN (".$filtered_issues.") ) AS table1) UNION ALL (
SELECT * FROM (SELECT nr.title, nr.nid, tn.tid, td.name, th.parent, case th.parent when 0 then td.name else thd.name end as parentName, field_states_0_value as field_states, n.type
FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid
LEFT JOIN wiche_term_node tn ON nr.nid = tn.nid
LEFT JOIN wiche_term_data td ON tn.tid = td.tid
LEFT JOIN wiche_term_hierarchy th ON th.tid = td.tid
LEFT JOIN wiche_term_data thd ON th.parent = thd.tid
LEFT JOIN wiche_content_type_spido_summary ctss ON ctss.nid = n.nid WHERE status = 1 AND n.type IN ('spido_summary') AND field_states_0_value IN (".$searchState.") AND tn.tid IN (".$parent_issues.") ) AS table1) )
AS all_policies ORDER BY field_states, parentName, name";

	if ($export) {
		$output = get_export_data($sql, $searchState, $parent_issues, $filtered_issues, $pageSize);	
		export_doc($output);
	}else {
		$output .= display_state_data($sql, $searchState, $parent_issues, $filtered_issues, $pageSize);
		return $output;
	}
}


function export_doc($table) {

  drupal_set_header('Content-Type: application/msword');
  drupal_set_header('Content-Disposition: attachment; filename="spido_policies.doc"');
  
  //get the html for the page
  //$table = theme('views_view_table', $view, $nodes, null);
  /*$table = preg_replace('/<\/?(a|span) ?.*?>/', '', $table); // strip 'a' and 'span' tags*/
  
  //write the data
  print $table;
  module_invoke_all('exit');
  exit;
}


function display_state_header($currentState) {
	
	$last_updated = get_last_updated($currentState);
	$statute_links = get_statute_links($currentState);		
	//Carl removed this on 2 Sept, 2010
	//$governance = get_governance($currentState);

//result header		
	//$output.='<div style="width:100%;padding:15px 0px 0px 0px;"><span style="font-size:16px;float:right;color:#cb644c;">'.$governance.'<br /></span><span style="font-size:20px;"><strong>State: </strong> '.getState($currentState).'</span><br /><div style="padding:15px 0px 0px 0px;">'.$statute_links.'</div><span style="float:right;font-size:12px;color:#cb644c">Last Updated: '.$last_updated.'</span></div>';

	$output.='<div style="width:100%;padding:15px 0px 0px 0px;"><span style="font-size:20px;"><strong>State: </strong> '.getState($currentState).'</span><br /><div style="padding:15px 0px 0px 0px;">'.$statute_links.'</div><span style="float:right;font-size:12px;color:#cb644c">Last Updated: '.$last_updated.'</span></div>';

	return $output;
}


/* format the output for export to word */
function get_export_data($sql, $searchState, $parent_issues, $filtered_issues) {

	//read policy and summary nodes
	$res = db_query($sql);
	
	//dpm("start display_state_data");
	//----------------------
	//display data
	//-----------------------
	$last_root = "";
	$output = "<table><tr><td>";
	$output .= "<h1>Policy Search Results</h1>";
	$last_state = "";

	while ($row = db_fetch_object($res)){
		$current_state = $row->field_states;
		
		//display state header
		if ($current_state != $last_state) {
				
			//display the state and other data for the prior states summary node.
			if ($last_root != "") {
				$output .= theme('get_state_links', $last_state, $last_root_tid, $last_root);
				//$output .= theme('get_other_links', $last_root, $last_root_tid);
				$last_root = ""; //reset last_root for the new state!
			}
		
			$last_updated = get_last_updated($row->field_states);
			$output .= "<div><h2>State:".getState($row->field_states)."</h2>";
			$output .= "<span style='float:right;font-size:12px;color:#cb644c'>Last Updated: ".$last_updated."</span></div>";

			//dpm("b4 change:" . $last_state);
			$last_state = $row->field_states;
			//dpm("after change:" . $last_state);
		}
	
		//display node data			
		$node = node_load($row->nid);
		$term = taxonomy_get_term($row->tid);
		//$parent = taxonomy_get_parents($row->tid);

		if ($node->type == 'spido_summary') {
			//write the previous summary state and other data when we read the next
			//summary record. This places it after the policy data.
			if ($term->name != $last_root && $last_root != "") {
				$output .= theme('get_state_links', $current_state, $last_root_tid, $last_root);
				//$output .= theme('get_other_links', $last_root, $last_root_tid);
			}

			if ($term->name != $last_term) {		
				//new display
			    //$output .='<h2>'.$term->name.'</h2>';
			    //$output .='<p>'.theme('get_summary',$row,$node).'</p>';
				$output.='<div style="width:100%;padding:15px 0px 0px 0px;"><hr /><span style="font-size:16px;"><strong>'.$term->name.'</strong></span></div>';
				$output.='<div style="width:100%;padding:0px 0px 0px 0px;">'.theme('get_summary',$row,$node).'</div>';
			}
			$last_root = $term->name;	
			$last_root_tid = $row->tid;
		}//get childern for this tid
		else if ($node->type == 'spido_policy') {
			if ($term->name != $last_term) {
				//$output .="<h3>".$term->name."<h3>";
				//$output .="<p>".theme('get_content',$node)."</p>";			
			$output.='<div style="width:100%;margin-left:15px;padding:15px 0px 0px 0px;"><span style="font-size:14px;color:#cb644c"><strong>'.$term->name.'</strong></span><br />'.theme('get_content',$node).'</div>';
			} else {
				//$output .="<p>".theme('get_content',$node)."</p>";			
				$output.='<div style="width:100%;margin-left:15px;padding:0px 0px 0px 0px;">'.theme('get_content',$node).'</div>';
			}
		} //if policy node						
		$last_term = $term->name;
	}
		//if we had data, display the state and other data content.
	if ($last_root != "" && $last_state) {
		$output .= theme('get_state_links', $current_state, $last_root_tid, $last_root);
		//$output .= theme('get_other_links', $last_root, $last_root_tid);
	}
	$output.="</td></tr></table>";	
	//there are multibyte characters in the spido data that do not export to word so we decode them so they display.
	//most display.
	return utf8_decode($output);
}



function display_state_data($sql, $searchState, $parent_issues, $filtered_issues, $pageSize) {

	//setup paging
	$paging = true;
	if (!$pageSize) {
		//$paging = false;
		$pageSize = 1000;
	}
	
	if ($paging) {
		$res = pager_query($sql,$pageSize);
	}else {
		$res = db_query($sql);
	}
	
	//dpm("start display_state_data");
	//----------------------
	//display data
	//-----------------------
	$last_root = "";
	$output = "";
	$last_state = "";

    //display buttons
	$output .= "<br /><INPUT TYPE='button' VALUE='Refine Search' onClick='window.history.go(-2);'>&nbsp;&nbsp;<INPUT TYPE='button' VALUE='New Search' onClick='window.location=\"/spido\";'>&nbsp;&nbsp;<INPUT TYPE='button' VALUE='Export to Word' onClick='window.location.href=\"".url($_GET['q'], NULL, NULL, TRUE)."/true\"'><br><br><br>"; 
	
	while ($row = db_fetch_object($res)){

		//display state header
		$current_state = $row->field_states;
		if ($current_state != $last_state) {
			//display the state and other data for the prior states summary node.
			if ($last_root != "") {
				$output .= theme('get_state_links', $last_state, $last_root_tid, $last_root);
				$output .= theme('get_other_links', $last_root, $last_root_tid);
				$last_root = ""; //reset last_root for the new state!
			}

			$output .= display_state_header($row->field_states);
			//dpm("b4 change:" . $last_state);
			$last_state = $row->field_states;
			//dpm("after change:" . $last_state);
		}
	
		//display node data			
		$node = node_load($row->nid);
		$term = taxonomy_get_term($row->tid);
		//$parent = taxonomy_get_parents($row->tid);

		if ($node->type == 'spido_summary') {
			//write the previous summary state and other data when we read the next
			//summary record. This places it after the policy data.
			if ($term->name != $last_root && $last_root != "") {
				$output .= theme('get_state_links', $current_state, $last_root_tid, $last_root);
				$output .= theme('get_other_links', $last_root, $last_root_tid);
			}
			if ($term->name != $last_term) {
				$output.='<div style="width:100%;padding:15px 0px 0px 0px;"><hr /><span style="font-size:16px;"><strong>'.$term->name.'</strong></span></div>';
				$output.='<div style="width:100%;padding:0px 0px 0px 0px;">'.theme('get_summary',$row,$node).'</div>';
			}
			$last_root = $term->name;	
			$last_root_tid = $row->tid;
		}//get childern for this tid
		else if ($node->type == 'spido_policy') {
			if ($term->name != $last_term) {
			$output.='<div style="width:100%;margin-left:15px;padding:15px 0px 0px 0px;"><span style="font-size:14px;color:#cb644c"><strong>'.$term->name.'</strong></span><br />'.theme('get_content',$node).'</div>';
			} else {
				$output.='<div style="width:100%;margin-left:15px;padding:0px 0px 0px 0px;">'.theme('get_content',$node).'</div>';
			}
		} //if policy node						
		$last_term = $term->name;
	}
	
	//if we had data, display the state and other data content.
	if ($last_root != "" && $last_state != "") {
		$output .= theme('get_state_links', $current_state, $last_root_tid, $last_root);
		$output .= theme('get_other_links', $last_root, $last_root_tid);
		//$output .='<div style="width:100%;margin-left:15px;padding:0px 0px 0px 0px;"><p>No Results found.</p></div>';

    	//display buttons
		$output .= "<br /><INPUT TYPE='button' VALUE='Refine Search' onClick='window.history.go(-2);'>&nbsp;&nbsp;<INPUT TYPE='button' VALUE='New Search' onClick='window.location=\"/spido\";'>&nbsp;&nbsp;<INPUT TYPE='button' VALUE='Export to Word' onClick='window.location.href=\"".url($_GET['q'], NULL, NULL, TRUE)."/true\"'><br><br><br>"; 
	}
	if ($paging) {
		$output .= theme('pager',NULL,$pageSize,0);	
	}
	return $output;
}


//------------------------------
function get_governance($searchState) {
		//governance
$qryGovernance = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created, ctsg.* FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid INNER JOIN wiche_content_type_spido_governance ctsg ON nr.nid = ctsg.nid WHERE status = 1 AND type IN ('spido_governance') AND field_state_2_value IN ('".$searchState."') "; //this has to interact with the pager, so there are 60 nodes set right now

	$governance = "";
	//print $qryGovernance.'<br />';
	$rsGovernance = db_query($qryGovernance);
	$num_rows_gov = db_num_rows($rsGovernance);
	if ($num_rows_gov>0) {
		//put in the flag and point to governance
		$state_gov = str_replace(" ","",strtolower(getState($searchState)));
		//print $state_gov;
		//$pic = theme('image','misc/state_icons/flag_'.$state_gov.'.gif');
		//print $pic;
		$governance = l(getState($searchState).' State Governance >>>','spido/governance/'.$searchState,array('target'=>'_blank','style'=>'color:#cb644c;'));
		//print $governance;
	}
	return $governance;

}

//------------------------------
function get_statute_links($searchState) {

$qryStatuteLinks = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created, ctsl.* FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid INNER JOIN wiche_content_type_spido_statute_links ctsl ON nr.nid = ctsl.nid WHERE status = 1 AND type IN ('spido_statute_links') AND field_state_1_value IN ('".$searchState."') "; 
	
	$statute_links = "";
	$rsStatutes = db_query($qryStatuteLinks);
	while ($statutes = db_fetch_object($rsStatutes)){
		$statute_links .= '&#9679; '. l($statutes->title,$statutes->field_link_url_url,array('target'=>'_blank')) .'<br />';
	}
	return $statute_links;
}

//------------------------------
function get_last_updated($searchState) {

	$qryState = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created, ctss.* FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid INNER JOIN wiche_content_type_spido_states ctss ON nr.nid = ctss.nid WHERE status = 1 AND type IN ('spido_states') AND field_short_name_value IN ('".$searchState."') "; 
	$last_updated = "";
	$rsStates = db_query($qryState);
	while ($states = db_fetch_object($rsStates)){
		$last_updated = $states->field_update_year_value .' ';
		$last_updated .= 'Legislative Session';
	}

	return $last_updated;
}



//------------------------------
function theme_get_state_links($state, $tid, $term) {
			$output = "";
			
			//display links
			$sql3 = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created, ctsp.*, tn.*, td.* FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid INNER JOIN wiche_content_type_spido_links ctsp ON nr.nid = ctsp.nid LEFT JOIN wiche_term_node tn ON nr.nid = tn.nid LEFT JOIN wiche_term_data td ON tn.tid = td.tid WHERE status = 1 AND type IN ('spido_links') AND field_state_0_value IN ('".$state."') AND tn.tid IN (".$tid.")"; //
			//print $sql3.'<br />';
			//dpm($sql3);
			$rsLinks = db_query($sql3);
			$num_rows2 = db_num_rows($rsLinks);
			if ($num_rows2>0) {
			$output.='<div style="width:100%;margin-left:15px;padding:0px 0px 0px 0px;"><br /><span style="font-size:14px;color:#cb644c"><strong>'.getState($node->field_states[0]['value']).getState($node->field_states_0[0]['value']).' State Links for '.$term.'</strong></span>';
			}
			while ($link = db_fetch_object($rsLinks)){
				$node3 = node_load($link->nid);
				//dpm($node3);
					$output.=''.theme('clearinghouse',$link,$node3).'';
			}
			if ($num_rows2>0) {
			$output.='</div>';
			}
			
			return $output;
}

//------------------------------
function theme_get_other_links($term, $tid) {
			$output = "";
			//vid, nid, field_link_0_url, field_link_0_title, field_link_0_attributes, field_summary_year_value, field_summary_author_value
			//display - Other Resources (Clearninghouse summaries)
			$sql4 = "SELECT n.type, nr.nid, nr.title, nr.teaser, nr.body, n.created, ctsp.*, tn.*, td.* FROM wiche_node n INNER JOIN wiche_node_revisions nr ON nr.nid = n.nid INNER JOIN wiche_content_type_clearinghouse_summary ctsp ON nr.nid = ctsp.nid LEFT JOIN wiche_term_node tn ON nr.nid = tn.nid LEFT JOIN wiche_term_data td ON tn.tid = td.tid WHERE status = 1 AND type IN ('clearinghouse_summary') AND td.name LIKE '%".$term."%'  order by ctsp.field_summary_year_value"; //
			//print $sql4.'<br />';
			//dpm($sql4);
			$rsClearinghouse = db_query($sql4);
			$num_rows3 = db_num_rows($rsClearinghouse);
			if ($num_rows3>0) {
			$output.='<div style="width:100%;margin-left:15px;padding:0px 0px 0px 0px;"><br /><span style="font-size:14px;color:#cb644c"><strong>Other '.$term. ' Resources</strong></span>';
			}
			while ($chs = db_fetch_object($rsClearinghouse)){
				$node4 = node_load($chs->nid);
				//print_r($node4);
					$output.=''.theme('clearinghouse',$chs,$node4).'';
			}
			if ($num_rows3>0) {
			$output.='</div>';
			}
			
			return $output;

}


//------------------------------
function theme_get_content($node) {
	$output = '<div style="margin:5px 0px 5px 50px;"><span style="font-size:12px;">'.l($node->title,$node->field_link[0]['url'].$node->field_link_0[0]['url'].$node->field_link_1[0]['url'],array('target'=>'_blank')).' - ';
	//$output .= $node->field_states[0]['value'] .'<br />';
	$output .= ''.$node->field_policy_description[0]['value'] .'</span>';
	//$output .= '<strong>Policy Source: </strong>'.$node->field_source[0]['value'] .'<br />';
	$output .=  '</div>';
	//$term = taxonomy_get_term($key);
	//$output .= '<strong>Domain: </strong>'.$term->name.'<br />';
	return $output ;
}


//------------------------------
function theme_get_summary($row,$node) {
	$output = '<div style="margin:5px 0px 5px 0px;"><span style="font-size:12px;">';
	//$output .= $node->field_states[0]['value'] .'<br />';
	$output .= ''.$node->field_summary[0]['value'] .'</span>';
	//$output .= '<strong>Policy Source: </strong>'.$node->field_source[0]['value'] .'<br />';
	$output .=  '</div>';
	//$term = taxonomy_get_term($key);
	//$output .= '<strong>Domain: </strong>'.$term->name.'<br />';
	return $output ;
}


//------------------------------
function theme_clearinghouse($row,$node) {
	$output = '<div style="margin:5px 0px 5px 50px;"><span style="font-size:12px;">'.$node->title.'<br />'.l($node->field_link[0]['url'].$node->field_link_0[0]['url'].$node->field_link_1[0]['url'],$node->field_link[0]['url'].$node->field_link_0[0]['url'].$node->field_link_1[0]['url'],array('target'=>'_blank')).'</span><br />';
	//$output .= $node->field_states[0]['value'] .'<br />';
	$output .= ''.$node->field_policy_description[0]['value'] .'';
	//$output .= '<strong>Policy Source: </strong>'.$node->field_source[0]['value'] .'<br />';
	$output .=  '</div>';
	//$term = taxonomy_get_term($key);
	//$output .= '<strong>Domain: </strong>'.$term->name.'<br />';
	return $output ;
}

//$output = get_content('spido_policy');
//print $output;
?>