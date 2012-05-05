<div class="wrapper">
		<p id="form-status"></p>
      <form id="registerForm" name="registerForm" action="?action=create_new_user" >
      <fieldset><legend><b>Register</b></legend>
      
      
      	<div class="formmgr-row"> 
         	<label for="username">Benutzername <em>*</em></label>
            <span class="formmgr-message-text"></span> 
            <input type="text" id="username" name="username" class="yiv-required">
      	</div>
          
      	<div class="formmgr-row"> 
               <label for="pass1">Passwort 1 <em>*</em></label>
                <span class="formmgr-message-text"></span>
               <input type="pass1" id="pass1" name="pass1" class="yiv-required"> 
      	</div>
         
         
         <div class="formmgr-row"> 
               <label for="pass2">Passwort 2<em>*</em></label>
                <span class="formmgr-message-text"></span>
               <input type="pass2" id="pass2" name="pass2" class="yiv-required"> 
      	</div> 

      </fieldset>
      </form> 
 
      		<span id="ft-note"> <em>*</em> Pflichtfelder </span>
            <button id="reset" >reset</button>   
				<button id="send" >register</button>
            
  </div>          
 
<script type="text/javascript"> 

{literal}
YUI({
		 gallery: 'gallery-2011.01.26-20-33'
	}).use(
		'node', 
	   'io-form', 
		'gallery-formmgr', 
		'json', 
		'widget-anim', 
		'plugin',
		'event-focus',
		'dump', 
		'console',
	function(Y)
	{

  
	Y.on('click', function(){
			f.populateForm();	// default_value_map + values in markup	
			f.enableForm();
	},
	'#reset');

	
	// create form
 
	var f = new Y.FormManager('registerForm',{ status_node: '#form-status'});
	
	
	
	f.prepareForm();
	f.initFocus();		// only do this for one form on a page
	
	
  
	f.registerButton('#send');
	f.registerButton('#reset');
	
	

	
	
	
	
	
	f.setFunction('pass1', function(form)
	{
		if (form.pass1.value != form.pass2.value)
		{
			f.displayMessage(form.pass1, 'Your password entries did not match.', 'error');
			f.displayMessage(form.pass2, '', 'error');
			return false;
		}
 
		return true;
	});
	
	
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
					 alert("Invalid json data");
				}
			  
			 
			 if (json_data.status != 200) {
		   		
					
					f.enableForm();
					
					
					
					f.displayFormMessage(json_data.statusmsg, true, true );
					f.displayMessage('#pass1', '', 'error');
					f.displayMessage('#pass2', '', 'error');
					f.displayMessage('#username', '', 'error');

						
			 }
			 

			  
			  if(json_data.status == 200 ){
			  			  
				f.displayFormMessage(json_data.statusmsg, true, true );
				f.enableForm();
				f.populateForm(); 
			
				
				 Y.log("form result: " + Y.dump(json_data)); 

			var htmlData  = '<li id="PermUserId_'+ json_data.result +'"> ';
             htmlData += '<img src="img/admin/icon.inactive.gif"> ';
             htmlData += '<span style="display:inline-block; min-width:8em;" id="'+ json_data.username +'" >'+ json_data.username +'</span> '; 
             htmlData += '<a href="#" id="showUser_'+ json_data.result +'" >Bearbeiten</a> '; 
             htmlData += '<a href="#" id="deleteUser_'+ json_data.result +'" >Löschen</a> '; 
             htmlData += '</li>';
				 
				
		      var liNode = Y.Node.create(htmlData);
				var ulNode = Y.one('#authUsers ul');
		
				Y.log("node to add : " + Y.dump(ulNode));
				Y.log("node to add : " + Y.dump(liNode));
		
			   ulNode.appendChild(liNode);	  
			  }
			  

				//Y.log("test "+ Y.dump(json_data));  
		}
	
	
		var handleComplete = function(id, o, a) {
		
		}
		
		var handleFailure = function(id, o, a) {
		
		}
	
	   var uri = "/admin.php";
		var cfg = {
			method: 'POST',
			data: 'action=create_new_user',
			form: {
				id: 'registerForm',
				useDisabled: true
			},
			on: {
					start: handleStart,
					complete: handleComplete,
					success: handleSuccess,
					failure: handleFailure
				}
			
	
		};
	

			function send() {

				f.validateForm();

				if(f.hasErrors()){
				
				}else{

					Y.io(uri, cfg);
					f.disableForm();
				
				}	

			}

 
			// example actions
 			Y.on('click', send, "#send", this);	
			Y.on('click', function(){ f.validateForm()}, '#validate');
 			Y.on('click', function(){ f.populateForm()}, '#reset');
 			Y.on('click', function(){ f.clearForm()}, '#clear');
 	

			
});
{/literal}
 </script> 
 
 

 
 

