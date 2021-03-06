<?php 

/** Get Views Breadcrumbs
ID: Views Breadcrumbs
Created: 09/2/2008 Paul Huntsberger 
Version: 1.0 
NOTES: Checks views for breadcrumbs, and then modifies the breadcrumb for it. Needs to have groups support.
This has to translate the information from the url to the view, and make a breadcrumb out of it.
Revisions: none.

*/


//chdir('../../../');
//chdir('../../../');
//require_once('./includes/bootstrap.inc');
//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

//Add jquery to view
//$path = path_to_theme() .'/jquery.etopics.js';
//drupal_add_js($path);

//get state from URL
function get_views_breadcrumbs() {
//print_r("start get_views_breadcrumbs");

//get arg(0) info - the formula for all this needs to be /%name of department, unit, or initiative %/
if (arg(0) && arg(0)!='node' && !$_GET['filter1'] && arg(1)) {
//$start[] = l('Reset Search',arg(0));
}

/* deprocated - state data removed from breadcrumb 9/23/09
$state = getState(arg(1));
//print $state;
if (!empty($state)) {
$states["arg(1)"] = $state;
}

//print_r($_GET['filter0']);
//print $_GET['q'];

if (count($_GET['filter0'])>0) {
	foreach ($_GET['filter0'] as $key => $value) {
		$state = getState($value);
		//print $state;
		if (!empty($state)) {
			$states["$value"] = $state;
		}	
	}
}
*/
//get domain argument and translate that to the url
$terms = explode("+",arg(2));
//print_r($terms);
//find out if arg(2) has multiple values
foreach ($terms as $key => $value) {
	$domain = taxonomy_get_term($value);
	//print_r($domain);
	if (!empty($domain)) {
		$domains[] = $domain->name;
	}
}

	if (count($_GET['filter1'])>0) {
	$filtered = array_filter($_GET['filter1'],"is_numeric");
		if (count($filtered)>0) {
			foreach ($filtered as $key => $value) {
				$domain = taxonomy_get_term($value);
				//print_r($domain);
				if (!empty($domain)) {
					$domains[] = $domain->name;
				}
			}
		}
	}

//set breadcrumb

//This generates the information for the filter && when you drill down
if (count($start)>0) {
	print '<strong><a href="#" onclick="javascript:history.back();return false;"><< Go Back</a></strong> | <strong>'.implode($start,'').'</strong>';
}
/*
if (count($states)>0) {
	//this has to turn into session variables
	print '<br /><br /><strong>Searching State(s):</strong> '.implode($states,' :: ').'<br />';
}
*/
if (count($domains)>0) {
	//this has to turn into session variables
	print '<strong>Searching Policies(s):</strong> '.implode($domains,' :: ');
}
}

/* deprocated 9/23/09 */
function getState($url) {
	//find the 
	if (!empty($url)) {
		//print arg(1);
		$states = array('AL'=>"Alabama", 
							'AK'=>"Alaska", 
							'AZ'=>"Arizona", 
							'AR'=>"Arkansas", 
							'CA'=>"California", 
							'CO'=>"Colorado", 
							'CT'=>"Connecticut", 
							'DE'=>"Delaware", 
							'DC'=>"District Of Columbia", 
							'FL'=>"Florida", 
							'GA'=>"Georgia", 
							'HI'=>"Hawaii", 
							'ID'=>"Idaho", 
							'IL'=>"Illinois", 
							'IN'=>"Indiana", 
							'IA'=>"Iowa", 
							'KS'=>"Kansas", 
							'KY'=>"Kentucky", 
							'LA'=>"Louisiana", 
							'ME'=>"Maine", 
							'MD'=>"Maryland", 
							'MA'=>"Massachusetts", 
							'MI'=>"Michigan", 
							'MN'=>"Minnesota", 
							'MS'=>"Mississippi", 
							'MO'=>"Missouri", 
							'MT'=>"Montana",
							'NE'=>"Nebraska",
							'NV'=>"Nevada",
							'NH'=>"New Hampshire",
							'NJ'=>"New Jersey",
							'NM'=>"New Mexico",
							'NY'=>"New York",
							'NC'=>"North Carolina",
							'ND'=>"North Dakota",
							'OH'=>"Ohio", 
							'OK'=>"Oklahoma", 
							'OR'=>"Oregon", 
							'PA'=>"Pennsylvania", 
							'RI'=>"Rhode Island", 
							'SC'=>"South Carolina", 
							'SD'=>"South Dakota",
							'TN'=>"Tennessee", 
							'TX'=>"Texas", 
							'UT'=>"Utah", 
							'VT'=>"Vermont", 
							'VA'=>"Virginia", 
							'WA'=>"Washington", 
							'WV'=>"West Virginia", 
							'WI'=>"Wisconsin", 
							'WY'=>"Wyoming");
		
		
		return $states[$url];
	}
}



?>
