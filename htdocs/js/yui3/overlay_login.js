// Overlay Login implementation for the finishers.ch.
YUI.add('overlay-login', function (Y) {

Y.on('domready', function(){ 

	var loginOverlay = new Y.Panel({
  		srcNode: "#panelContent",
  		width: 350,
  		centered: true,
  		visible: false,
  		modal:true,
  		zIndex:5,
  		headerContent: "Authentifizierung",
  		plugins: [Y.Plugin.Drag]

	}),

	nestedPanel;

	loginOverlay.addButton(
	  {
			value: "Reset",
			action: function(e) { 
			  e.preventDefault(); 
				f.populateForm();	// default_value_map + values in markup	
				f.enableForm();
			},
			section: Y.WidgetStdMod.FOOTER
	  }
	);


	loginOverlay.addButton(
	  {
			value: "Cancel",
			action: function(e) { 
			  e.preventDefault(); 
			  loginOverlay.hide(); 
			},
			section: Y.WidgetStdMod.FOOTER
	  }

	);


	loginOverlay.addButton(
	  {
			value: "Login",
			action: function(e) { 
			  e.preventDefault(); 
			  send();
			},
			section: Y.WidgetStdMod.FOOTER
	  }
	);


	loginOverlay.render();


	Y.one('#show-loginOverlay').on('click', function(e){
																	 
		Y.one('#panelContent').setStyle('display', 'block');															 
		loginOverlay.show(); 
		f.initFocus();		// only do this for one form on a page
	});

						
	// create form
	var f = new Y.FormManager('login',{ status_node: '#form-status'});
	//f.registerButton('#hideloginOverlay');   
	//f.registerButton('#send');
	//f.registerButton('#reset');

	f.prepareForm();
	f.initFocus();		// only do this for one form on a page


	Y.FormManager.Strings ={
		validation_error:     'Bitte überprüfen sie die rot markierten Pflichtfelder.',
		required_string:      ' ',
		required_menu:        'This field is required. Choose a value from the pull-down list.',
		length_too_short:     'Enter text that is at least {min} characters or longer.',
		length_too_long:      'Enter text that is up to {max} characters long.',
		length_out_of_range:  'Enter text that is {min} to {max} characters long.',
		integer:              'Zahl eingeben: ',
		integer_too_small:    'Enter a number that is {min} or higher (no decimal point).',
		integer_too_large:    'Enter a number that is {max} or lower (no decimal point).',
		integer_out_of_range: 'Postleitzahl {min} und {max}.',
		decimal:              'Enter a number.',
		decimal_too_small:    'Enter a number that is {min} or higher.',
		decimal_too_large:    'Enter a number that is {max} or lower.',
		decimal_out_of_range: 'Enter a number between or including {min} and {max}.'
	};


	//########################################
	// YUI ASYNC REQUEST --> yui3.3 IO
	//########################################

		var handleStart = function(id, a) {
			//Y.log("io:start firing.", "info", "example");
			//output.set("innerHTML", "<li>Loading news stories via Yahoo! Pipes feed...</li>");
		}

	

		var handleSuccess = function(id, o, a) {

				var id = id; // Transaction ID.
				var data = o.responseText; // Response data.

				try {
					 var json_data = Y.JSON.parse(data);
				}
				catch (o) {

					
				}

			 if (json_data.status != 200) {

					f.enableForm();
					f.displayFormMessage('Passwort oder Benutzername sind falsch', true, true );
					f.displayMessage('#passwd', '', 'error');
					f.displayMessage('#handle', '', 'error');

			 }else{
				  loginOverlay.hide();
				  f.populateForm();

					window.setTimeout(function() {
						window.location = "admin.php"
					}, 500);
			 } 
		}


		var handleComplete = function(id, o, a) {

		}

		var handleFailure = function(id, o, a) {

		}


		var cfg = {
			method: 'GET',
			data: 'action=authentication',
			form: {
				id: 'login',
				useDisabled: true
			},
			on: {
					start: handleStart,
					complete: handleComplete,
					success: handleSuccess,
					failure: handleFailure
				},
			arguments: {
				start:    'foo',
				complete: 'bar',
				end:      'baz'
			}
		};
		

		function send() {
			f.validateForm();
				
			if(f.hasErrors()){

			}else{
					Y.io('index.php', cfg);
					f.disableForm();
			}	
		}
		

 });

}, '1.0.0', {requires: [
    'gallery-formmgr', 'panel', 'dd-plugin', 'io-form', 'json'
]});

