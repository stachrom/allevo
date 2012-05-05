		<div id="user-profile" >
      
          <ul class="yui-nav">
              <li class="selected" ><a href="#userdaten"><em>Userdaten</em></a></li>
              <li><a href="#permission_user"><em>Permission</em></a></li>
              <li><a href="#gruppen_user"><em>Gruppen</em></a></li>
          </ul>
                     
      	<div>
              <div id="userdaten">
                 <div id="formContainer"></div> 
              </div>
     
              <div id="permission_user">
					{include file="admin/liveuser/user/user_permission.tpl"}
              </div>
   
              <div id="gruppen_user">
					{include file="admin/liveuser/user/groups.tpl"}
              </div>

          </div>
		</div>
{literal}
<script> 

YUI({
		 gallery: 'gallery-2011.10.06-19-55'
	}).use(
		'gallery-form', 
		'gallery-overlay-extras',  
		'dd-constrain',  
		'dd-plugin', 
		'io-form',
		'io-base', 
		'json', 
		'node', 
		'overlay', 
		'widget-anim', 
		'plugin',
		'event-focus',
		'event-delegate',
		'history', 
		'tabview',
		'dump', 
		'console',
		
	function(Y)
	{

 new Y.Console().render( '#logger' );
 
 
 
   var history = new Y.HistoryHash(),
       tabview = new Y.TabView({srcNode: '#user-profile'});
 
  tabview.render();
 
 

	//########################################
	// YUI ASYNC REQUEST --> yui3.4 IO
	//########################################
	
	
	

	
	
 

	
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
		   		
		
			 }
			 

			  
			  if(json_data.status == 200 ){
			  
			   Y.log("form result: " + Y.dump(json_data.result));
				Y.log("arguments: " + Y.dump(a));


				if(a.action == 'deleteUser'){
						Y.one('#PermUserId_'+a.perm_user_id).remove();
				}

		

			  if(a.action == 'showUser'){
			  
			
			  
	       Y.one('#formContainer').get('children').remove();

			  
			  var f = new Y.Form({
					  boundingBox: '#formContainer',
					  action : '/admin.php',
					  method : 'post',
					  children  : json_data.result
					});
			  
			  	f.render();
		

					
					
					f.subscribe('success', function (args) {
					
					////////////////////////
					// Update User /////////
					////////////////////////
					
					var data = args.response.responseText;
								
					try {
					 				var json_data = Y.JSON.parse(data);
						}
								catch (args) {
					 			 	Y.log("error update user data response" + Y.dump(args.response));
						}
					
					
				
					 Y.log("update user successfull " + Y.dump(json_data));
		
					if(json_data.status == 200 ){
		
						//Y.one('#formContainer').get('children').remove();
						f.destructor();
		
						var liNode = Y.one("#PermUserId_"+ json_data.perm_user_id)
						var imgNode = liNode.one('img');
						
						if(json_data.is_active==1){
							imgNode.set("src", "img/admin/icon.active.gif");
						}
						if(json_data.is_active==0){
							imgNode.set("src", "img/admin/icon.inactive.gif");
						}
	

			      }
		  

    });
					
					
					
				}
			  
 
			  
			  }
			  

				//Y.log("test "+ Y.dump(json_data));  
		}
	
	

			
			
	
 
 
 Y.delegate("click", function(e) {
 
        Y.log("Default scope: " + this.get("id"));
        Y.log("Clicked list item: " + e.currentTarget.get("id").split("_") );
        Y.log("Event target: " + e.target);
        Y.log("Delegation container: " + e.container.get("id"));
		  
		  var action = e.currentTarget.get("id").split("_");

		  
		  var cfg = {
			method: 'GET',
			data: 'action='+ action[0]+'&perm_user_id='+ action[1],
			arguments: {
						action: action[0],
						target: e.currentTarget.get("id"),
						perm_user_id:action[1]	
			},
			
			
			on: {
					success: handleSuccess
				}
	
		};

		  Y.io('/admin.php', cfg);
		  
		  
 
 
    }, "#authUsers", "a");






});


</script> 
{/literal}