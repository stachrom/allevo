{*config_load file=allevo.conf section="usermanagement"*}
{include file="admin/header.tpl" title="Allevo Admin"}

{if $liveuser['loggedIn'] eq 1}
 
 <div class="yui3-g">
	<div class="yui3-u-1">
		<div id="admin_allevo">
			<ul>
				<li class="selected" ><a href="?modul=jstree#jstree"><em>Inhalt</em></a></li>
				<li><a href="#permissions"><em>Premissions</em></a></li>
				<li><a href="#liveuseres"><em>Benutzer</em></a></li>
				<li><a href="#tab-groups"><em>Gruppen</em></a></li>
				<li><a href="#register"><em>Register</em></a></li>
			</ul>

			<div>
				<div id="jstree">
				  {include file="admin/jstree.tpl"}
				</div>
	   
				<div id="permissions">
				  {include file="admin/liveuser/permission/index_permission.tpl"}
				</div>

				<div id="liveuseres">
				  {include file="admin/liveuser/user/index_user.tpl"}
				</div>
				  
				<div id="tab-groups">
				  {*include file="admin/liveuser/user/groups.tpl"*}
				</div>

				<div id="register">
				  {include file="admin/liveuser/user/register_user.tpl"}
				</div>
				  
			</div>
		</div>
	</div>
</div>      
      
{/if}

{literal}
<script>

YUI({
	gallery: 'gallery-2012.04.04-17-55',
	modules : {
		'right-manager' : {
			fullpath : 'js/yui3/right-manager.js',
			requires : ['dd-constrain', 'sortable', 'attribute', 'datasource-io',  'event-hover', 'json-parse', 'json-stringify', 'event', 'anim', 'json']
			},
		'toggle-div' : {
			fullpath : 'js/yui3/toggle-div.js',
			requires : ['anim', 'node']
			}
		}

}).use('history', 'tabview', 'gallery-lazy-load', 'dump', function(Y){

  var history = new Y.HistoryHash(),
      tabview = new Y.TabView({srcNode: '#admin_allevo'});

  tabview.render();

  tabview.selectChild(history.get('tab') || 0);
 

  tabview.after('selectionChange', function (e) {

    history.addValue('tab', e.newVal.get('index') || null);
	
		Y.log('new val '+  Y.dump(e.newVal.get('label')));
		Y.log('prev val '+  Y.dump(e.prevVal.get('index')));
		
		if(e.newVal.get('label') == 'Benutzer'){
			loadModulBenutzer(function (success) {
				//alert('get the fuck out of here!');
			});
		};
		
		if(e.newVal.get('label') == 'Premissions'){
			loadModulPermissions(function (success) {
				//Y.log('we are ready ');
			});
		}

  });
 

  Y.on('history:change', function (e) {

    if (e.src === Y.HistoryHash.SRC_HASH) {
 
      if (e.changed.tab) {
        tabview.selectChild(e.changed.tab.newVal);
      } else if (e.removed.tab) {
        tabview.selectChild(0);
      }
 
    }
  });
  
	var loadModulPermissions = function (callbackFunction) {
  
		Y.lazyLoad( 'anim', 'toggle-div', 'right-manager', function (errors, attached) {
		
            // If there was a problem, deal with it.
            if (errors) {
                callbackFunction(false);
                return;
            };
			
			if (attached['toggle-div']) {
			
				Y.one('#toggle-users').togglediv();
				Y.one('#toggle-group').togglediv();
				Y.one('#toggle-areas').togglediv();

				//module3.addClass('yui3-closed');
				//content3.setStyle("height", "1px");
				//content3.fx.set('reverse', true);

			};
			
			if (attached['right-manager']) {
			
			Y.log('manager is ready');


			};

			callbackFunction(true);
			
        });
	};
  
var loadModulBenutzer = function (callbackFunction) {
   // Lazy load model.
   Y.lazyLoad( 'autocomplete', 'autocomplete-highlighters', 'datasource-io','gallery-form', "json-parse", 'event-mouseenter', 'event',  'overlay','dump', 'anim',  function (errors, attached) {

      // If there was a problem, deal with it.
      if (errors) {
         callbackFunction(false);
         return;
      }
            
            Y.log("alle attached ? " + Y.dump(attached));
            

      if (attached['gallery-form'] && attached['json-parse'] && attached['datasource-io']  ) {
      
              Y.log("aber hallo ");
      
      Y.on('domready', function () {
       
         function over(e) {
            e.currentTarget.addClass('live-user-hover');
         }
         
         function out(e) {
            e.currentTarget.removeClass('live-user-hover');    
         }
      

         function delete_user() {

               var perm_user_id = this.get('children').get('text');
               var click_id = this.get('id');

               var cfg = {
                  method: 'POST',
                  data:   {
                           'action': 'deleteUser',
                           'perm_user_id': perm_user_id
                  },
                  on:     {success: handleSuccessEditUserForm},
                  arguments: click_id 
               };
               Y.log("cfg : " + Y.dump(cfg) );
               
               Y.io('admin.php', cfg);

         };   

        
            Y.one('#manage-live-user').delegate('hover', over, out, '.live-user');
            Y.one('#manage-live-user').delegate('click', delete_user, '.live-user-hover');
            
    
      
      });
      
      

         var editBtn   = Y.one('#editBtn'),
				deleteBtn  = Y.one('#deleteBtn'),
				loader    = Y.Node.create('<img src="/img/waiting.gif">'),
					 results   = Y.one('#results'),
					 overlay,
					 f,
					 OVERLAY_TEMPLATE = '<div id="edit_user_overlay">'+
													'<div class="yui3-widget-hd"></div>'+
													'<div id="widget" class="yui3-widget-bd"></div>'+
													'<div class="yui3-widget-ft"></div>'+
												'</div>';



				  var LuUserTemplate =
					 '<div class="LuUser">' +
							  '<div class="hd">' +
								 '<img src="img/admin/{is_active_icon}">' +
								   '<span class="handle">{highlighted}</span>' +
								'</div>' +
								'<div class="bd">' +
								   '<span class="UserInfo"> {vorname} </span>' +
								   '<span class="UserInfo"> {nachname} </span>' +
								   '<span class="UserInfo">({perm_type})</span>' +
								'</div>' +
						  '</div>';
 
				  function LuUserFormatter(query, results) {
					 return Y.Array.map(results, function (result) {
						 var user = result.raw;
					 
						 return Y.Lang.sub(LuUserTemplate, {
							highlighted    : result.highlighted,
							is_active_icon : user.is_active_icon,
							vorname        : user.vorname,
							nachname       : user.nachname,
							perm_type      : user.perm_type_text
						 });
					  });
					}
		  
		  
				  var dsLuUser = new Y.DataSource.IO({
					source: '/request/search.php?action=search_liveuser_user'
					});
		  

		  var LuUserSearch = Y.one('#liveuser-input').plug(Y.Plugin.AutoComplete, {
		  
			 resultTextLocator: 'handle',
			 maxResults: 10,
			 resultHighlighter: 'wordMatch',
			 resultFormatter: LuUserFormatter,
			 requestTemplate: '&q={query}',
			 source: dsLuUser,
         resultListLocator: function (response) {

            var data = response[0].responseText; // Response data.
            
            data = Y.Lang.trim(data);

				try {
					var json_data = Y.JSON.parse(data);
				}
				catch (e) {
               alert("not json sorry...");
				}

				var results = json_data.result;
				
				if (results && !Y.Lang.isArray(results)) {
					 results = [results];
				}
					 return results || [];
			}
			  
		  });
		  
		  
		  LuUserSearch.ac.after('select', function (a) {
		  
				Y.log("perm_user_id : " + Y.dump(a.result.raw.perm_user_id));
				
				var perm_user_id = a.result.raw.perm_user_id;
	
				var cfg = {
					method: 'GET',
					data:   'action=showUser&perm_user_id='+ perm_user_id,
					on:     {success: handleSuccessEditUserForm}
				};
	
			  Y.io('/admin.php', cfg);

		  });


		 var handleSuccessEditUserForm = function(id, o, a) {
		 // Create an overlay to edit a user (Form).
       
         var data = o.responseText; 
         data = Y.Lang.trim(data);
	
		      try {
					 var json_data = Y.JSON.parse(data);
				}
				catch (o) {
					 alert("Invalid json data");
					 json_data= [];
				}


         if (json_data.status == 200 && json_data.action == "deleteUser" ) {

				Y.log("sucess argument: " + Y.dump('#'+a));

				var parent_node = Y.one('#'+a).get('parentNode');

			   var anim = new Y.Anim({
					  node: parent_node,
					  to: { opacity: 0 }
				 });

    			var onEnd = function() {
					  var node = this.get('node');
					  node.get('parentNode').removeChild(node);
				};

				anim.on('end', onEnd);
				anim.run();
            
            return;

			}



            

				destroyOverlay();
			  
			   createOverlay(); 
			  
				  f = new Y.Form({
								  boundingBox: '#widget',
								  action : '/admin.php',
								  method : 'post',
								  children  : json_data.result, 
								});
	
				  f.subscribe('success', function (args) {
							
							////////////////////////
							// Update User /////////
							////////////////////////
							
							var data = args.response.responseText;
                     data = Y.Lang.trim(data);
										
							try {
									var json_data = Y.JSON.parse(data);
							}
							catch (args) {
									Y.log("error update user data response" + Y.dump(args.response));
							}
		
							   Y.log("update user successfull " + Y.dump(json_data));
							 
							 if(json_data.status != 200 ){
							 
								overlay.set("footerContent", json_data.statusmsg);
		
							 }else{
							 
								destroyOverlay();
							 
							 }
	
					});
               
               f.subscribe('submit', function (args) {
               
                  Y.log("submit"+ Y.dump(args));
               
               });
               
               
               f.subscribe('onclickChange', function (args) {
               
                  Y.log("onclickChange "+Y.dump(args));

               });
               
               f.render();
               
               

		 }
		 
		 
		 
		 function createOverlay() {
		 
			overlay = new Y.Overlay({
				 srcNode      : "#edit_user_overlay",
				 width        : '400px',
             height       : '600px',
				 zIndex       : 100,
				 headerContent: '<a title="hide panel" id="hideOverlay" ><em>hide</em></a>',
             centered     : true,

			});
			
			overlay.render();
			
			Y.one('#hideOverlay').on('click', function(e){ 
							  destroyOverlay(); 
			});

         Y.one('#edit_user_overlay').setStyle('display', 'block');
		}
		


		function destroyOverlay() {

			if (f) {
				f.destructor();
			}
			
			if (overlay) {
				overlay.destroy();
			}
			
			Y.one('#results').insertBefore(OVERLAY_TEMPLATE,'replace');
			Y.one('#edit_user_overlay').setStyle('display', 'none');
			Y.one('#liveuser-input').setAttrs({value: ''});
			
		}



   }

            callbackFunction(true);
			
        });
    };
  
  

  
});
</script> 
{/literal}

{include file="admin/footer.tpl"}