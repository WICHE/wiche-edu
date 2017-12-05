		//<![CDATA[
			function myOnSubmitEventHandler() {
				
				try {
					if(typeof(document.getElementById('X-editor').EscapeUnicode) == 'undefined') {
						throw "Error"
					} else {
						document.getElementById('X-editor').EscapeUnicode = true;
						var text = document.getElementById('X-editor').value;
						text = text.replace(/&lt;/g, "<");
						text = text.replace(/&gt;/g, ">");
						document.getElementById('edit-body').value = text;
						document.getElementById('copy-data').value = text;
						//alert("Try: " + text);
					}			
				}
				catch(er) {
					var text = document.getElementById('X-editor').value;
					text = text.replace(/&lt;/g, "<");
					text = text.replace(/&gt;/g, ">");
					document.getElementById('copy-data').value = text;
					//alert("Catch: " + text);
				}
			}
			
			function updateXStandard() {
	//Set the latest version
	latestVersion = '2.0.0.0';

	//Check if browser is Firefox
	if (navigator.userAgent.toLowerCase().indexOf('firefox') != -1) {
		//Go through all the object tags on the page
		var objects = document.getElementsByTagName('object');
		for (var i=0; i<objects.length; i++) {
			var obj = objects[i];
			try {
				//Check if object is XStandard
				if(typeof(obj.EscapeUnicode) == 'undefined') {
					throw "Error"
				} else {
					//Check the editor is the latest version
					if (obj.Version != latestVersion) {
						//Redirect to update page
						window.location.replace('http://xstandard.com/upgrade-firefox-version/');
						break;
					}
				}			
			}
			catch(er) {}
		}
	}
}

window.onload = updateXStandard;

 function xsButtonClicked(id, button, state) {
 //alert('Editor: ' + id + '; function: xsButtonClicked(); button: ' + button);
 }

 function xsContentChanged(id) {
 //alert('Editor: ' + id + '; function: xsContentChanged()');
 }

 function xsModeChanged(id) {
 //alert('Editor: ' + id + '; function: xsModeChanged()');
 }

 function xsContextMenuActivated(id) {
 //alert('Editor: ' + id + '; function: xsContextMenuActivated()');
 document.getElementById(id).ClearContextMenu();
 document.getElementById(id).AddToContextMenu('a', 'My item a', '');
 document.getElementById(id).AddToContextMenu('b', 'My item b', '');
 document.getElementById(id).AddToContextMenu('c', 'My item c', '');
 document.getElementById(id).AddToContextMenu('d', 'My item d', '');
 document.getElementById(id).AddToContextMenu('e', 'My item e', '');
 }

 function xsContextMenuClicked(id, menu) {
 //alert('Editor: ' + id + '; function: xsContextMenuClicked(); menu: ' + menu);
 }
		//]]>
