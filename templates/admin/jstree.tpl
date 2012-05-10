<div id="editor-response"> </div>
<div class="yui3-g" id="layout_inhalt">
   <div class="yui3-u" id="jsbaum"> 
      <div class="content">
			<section id="drag_it">
				<h2>New content</h2>
					<ol>
					  {foreach $google_docs as $c}
						<li class="jstree-draggable" id="drag_{$c.object_name}">{$c.title}</li>
					  {/foreach}
					</ol>
			</section>
			<h2>Navigation</h2>
			<div id="baum" class="baum"></div>
		</div> 
	</div>

	<div class="yui3-u" id="main_inhalt">
		<div class="content"> 
			<h2> Inhalt</h2>
			<form method="post" action="#" id="nestedset-editor-content-form" name="savecontent" > 
				<div id="yuieditor_tabview">
					<ul>
						<li class="yui3-tab-selected" ><a href="#yahooEditor">Editor</a></li>
						<li><a href="#left_container_tab" id="left_container">linker Container</a></li>
                  <li><a href="#tags_tab" id="content_tags">Tags</a></li>
						<li><a href="#media-management" id="loadMedia">Media Management</a></li>
						<li><a href="#uploader_tab">Uploader</a></li>
						<li><a href="#url-controller">URL Controller</a></li>
						<li><a href="#seo">SEO</a></li>
						<li><a href="#history">Versionsgeschichte</a></li>
					</ul> 
					<div>
						<div id="yahooEditor">
						
							<div id="AutoCompleteLinkId">
								<label for="ContentActive">Aktiv: </label>
								<input type="checkbox" name="ContentActive" id="ContentActive" value="1"> 
								
								<label  for="InputLinkId" >Interne Verlinkung: </label>
								<input id="InputLinkId" type="text"  name="InputLinkId" value="">
                      
                        <label for="ac-input-tags-content">Tags (Komma separiert):</label>
                        <input id="ac-input-tags-content" type="text" value="">
                        
                        <input id="uuid" type="hidden" value="" name="uuid">
								
								<input id="linkidhidden" type="hidden" value="" name="linkidhidden">
								<div id="LinkIdContainer"></div>
							</div> 
							
							<div id="editable">
								<h1>Willkommen</h1>
								Auf der linken Seite einen Navigationspunkt Auswählen. 
								(Rechte Maustaste für Contextmenu) Dragg and Drop!
								<h1>Dann Doppelklick </h1>
								auf die Inhaltsfläche für die Bearbeitung des Inhaltes.
							</div>
							
							<div id="yahooEditor-box">
								<input type="hidden" name="nestedsetid" value="" id="nestedsetid" />
								<input type="hidden" name="uuid" id="uuid" value="" >
								<textarea id="editor" name="editor" rows="20" cols="75"> </textarea> 
							</div>
							
						</div>

						<div id="left_container_tab">
						
								<textarea id="content2" name="content2" rows="20" cols="75"> </textarea> 
				
						</div>
                  
                  <div id="tags_tab">
							<div class="content" >
         
                     <h1 id="object-tags-title">zugewiesene Tags</h1>  
                        <ul id="object-tags"> </ul>     
                     
                     
                     
							<h1>Tags im System</h1>
                        <ul id="remove-tags">
                          {foreach $global_tags as $c}
                           <li class="tag">
                           {$c.name}
                           <a href="#" class="remove-tag" title="Remove this tag"><span class="tag-id hidden">{$c.id}</span></a>
                           </li>
                          {/foreach}
                        </ul>
                        
              
                     
							</div> 
						</div>
                  
                  
                  
                  

						<div id="media-management" class="yui3-skin-sam">
							<div id="doc3" class="yui-t1"> 
							 <div id="bd"> 
								 <div id="yui-main"> 
									<div class="yui-b"> 
										 <div class="yui-g"> 
											<ul>
								 
											</ul>
										</div> 
									 </div> 
								</div> 
								<div class="yui-b"> 
									 <ul id="photoList"> 
										 <li class="all selected"><a href="#">Alle Photos</a></li> 
										 <li id="album"><a href="#">Album<span>(0)</span></a></li>
										 <li id="left_side"><a href="#">Seitenbild <span>(0)</span></a></li>  
										 <li id="remove"><a href="#">Löschen<span>(0)</span></a></li> 
									 </ul>
									 
									 <a id="manual-media-load" href="#" > alle Bilder Laden </a>
									 <a id="media-reset" href="#" > Reset </a>
									 
									 <h3>Vorhande Tags</h3>
									 <ul id="pictures_tags" >
									 {foreach $bilder_tags as $tag_id => $tag_name}
										<li class="tag" >
										{$tag_name}
											<a href="#" class="get-taged-pictures" title="get taged pictures" >
												<span class="tag-id hidden">{$tag_id}</span>
											</a>
										</li>
									{/foreach}
									 </ul>
						
								 </div> 
							 </div> 
							 <div id="ft" class="loading">
							 <br>
							 <strong></strong><div class="horiz_slider"></div><em></em>
							 
							 <div id="panelContent">
								<div class="yui3-widget-hd"></div>
								<div class="yui3-widget-bd">
								<div class="tag-title">zugewiesene Tags:</div>
								<ul id="present_tags"></ul>
									<p id ="input_tag"> 
										<label for="ac-input-tag">Add Tags(komma separiert):</label><br>
										<input id="ac-input-tag" type="text" value="">
										<input id="object-name" type="hidden" value="" >
									</p>
									<div id="image_replacement"></div>
								</div>
							</div> 
							 
							 
							 
							 
							 
						</div> 
					</div>     
				</div>
						
				<div id="uploader_tab"> 
					<div class="example yui3-skin-sam">
						<div id="uploaderContainer">
							<div id="uploaderOverlay" style="position:absolute; z-index:2" ></div>
							<div id="selectFilesLink" style="z-index:1"><a id="selectLink" href="#" class="button">Select Files</a></div>  
						</div>
						<div id="uploadFilesLink"><a id="uploadLink" href="#" class="button" >Upload Files</a></div>
						<div id="files">
							<table id="filenames" style="border-width:1px; border-style:solid; padding:5px; width:540px;">
								<thead>
								   <tr><th>Filename</th><th>File size</th><th>Prozent uploaded</th></tr>
								</thead>
								<tbody></tbody>
							</table>	
						</div>
					</div>
				</div>

				<div id="url-controller">
					<div id="tableContainer"></div>
				</div>

				<div id="seo">
					<label for="rewrite">Rewrite : </label> <input id="rewrite"  type="text" name="rewrite" value=""  />
					<label for="rewrite">Keywords : </label> <input id="keywords"  type="text" name="keywords" value=""  />
				</div>

				<div id="history">
					<h2>alle Änderungen des Nodes:</h2>
				</div>
				
			 </div>        
		</div> 
	</form>  
			   
			   
	<div>
	<br>
		<button type="button" id="submitEditor">Speichern</button> 
		<div id="status"></div> 
	</div>
</div> 

</div>     
</div>







 
{literal}




<script> 

YUI({
	gallery: 'gallery-2012.03.28-20-16',
	insertBefore: 'styleoverrides',
	modules : {
		'gallery-effects' : {
			fullpath : 'js/yui3-gallery/gallery-effects.js',
			requires : ['node','anim','async-queue']
			},
		'multi-uploader' : {
			fullpath : '/js/yui3/uploader.js',
			requires : ['uploader', 'gallery-progress-bar', 'cookie']
			},		
		'photo':{
			fullpath : '/js/photos.js',
			requires : [ 'node', 'event',  'json-parse', 'io-form',  'json', 'dd-plugin', 'datasource-io', 'transition', 'event-hover', 'event-custom', 'autocomplete', 'autocomplete-highlighters', 'autocomplete-filters', 'anim', 'dd', 'dd-plugin', 'dd-drop-plugin', 'slider', 'stylesheet', 'event-delegate', 'dump', 'json-stringify', 'io', 'panel']
			},
		'editor':{
			fullpath : '/js/editor.js',
			requires : ['event', 'dump', 'json', 'node', 'event-delegate', "yui2-animation", "yui2-autocomplete", "yui2-button", "yui2-connection", "yui2-container", "yui2-dom", "yui2-editor", "yui2-element", "yui2-event", "yui2-menu"]
			},
		'data-table':{
			fullpath : '/js/data_table_query_string.js',
			requires : ['node', 'event-custom', "yui2-animation", "yui2-autocomplete", "yui2-button", "yui2-connection", "yui2-container", "yui2-dom", "yui2-editor", "yui2-element", "yui2-event", "yui2-menu", "yui2-datatable", "yui2-dom", "yui2-dragdrop", "event-custom", "yui2-calendar"]
			}	
		}
	}).use(
		'gallery-lazy-load',
		'tabview',
		'editor',
		'autocomplete', 
		'autocomplete-highlighters', 
		'autocomplete-filters',
      'event-hover', 
      'anim',
		'gallery-yquery',
		'event-custom',
		'node', 
		'json',
	 	'plugin',
	 	'datatype',
		'io-base',
		'datasource-io',
		'dump', 
		'console',  
		'gallery-notify',
	function (Y) {
   
   
   var n = new Y.Notify({prepend:true});
	n.render();
   
   
Y.on('domready', function () {

   var searchContentTags = Y.one('#ac-input-tags-content').plug(Y.Plugin.AutoComplete, {
    activateFirstItem: true,
    allowTrailingDelimiter: true,
    minQueryLength: 0,
    queryDelay: 0,
    queryDelimiter: ',',
	 resultHighlighter: 'startsWith',
    resultListLocator: 'data.tags',
    resultTextLocator: 'name',
    source: 'request/jstree.php?action=search_tags&q={query}',
    resultFilters: ['startsWith', function (query, results) {
      var selected = searchContentTags.ac.get('value').split(/\s*,\s*/);
      selected.pop();
      selected = Y.Array.hash(selected);
      return Y.Array.filter(results, function (result) {
        return !selected.hasOwnProperty(result.text);
      });
    }]
  });
 
   searchContentTags.on('focus', function () {
      searchContentTags.ac.sendRequest('');
   });
 
  // After a tag is selected, send an empty query to update the list of tags.
   searchContentTags.ac.after('select', function () {
      searchContentTags.ac.sendRequest('');
      searchContentTags.ac.show();
   });
  
   var handleSucces = function(id, o, a) {

  		var data = o.responseText; 
	
		try {
			var json_data = Y.JSON.parse(data);
		}catch (o) {
			n.addMessage('Invalid json data', 'error');
			json_data= [];
		}			

		if (json_data.status != 200) {
			n.addMessage(Y.dump(json_data.statusmsg), 'error');
		}else{
			n.add({'message': Y.dump(json_data.statusmsg) + Y.dump(json_data.data) });
		}
			
			
		if (json_data.status == 200 && (json_data.action == "remove_tag" || json_data.action == "remove_tag_from_object") ) {
			// remove the tag from the dom before do animation
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
		}

		if (json_data.status == 200 && json_data.action == "add_tags" ) {
         searchContentTags.set('value', "" );
		}

  }
    
   function remove_tag() {
		var tag_id = this.get('children').get('text');
		var click_id = this.get('id');
		var cfg = {
					method: 'POST',
					data:   {
								'action': 'remove_tag',
								'tag-id': tag_id
					},
					on:     {success: handleSucces},
					arguments: click_id 
		};
		Y.io('request/jstree.php', cfg);
	};	 
  
   function over(e) {
      e.currentTarget.addClass('tag-hover');
   };
       
   function out(e) {
      e.currentTarget.removeClass('tag-hover');;
   };
   
   function remove_tag_from_object(e) {
	
		Y.log("target: " + Y.dump(e.target) );
		
		var tag_id = e.target.get('children').get('text');	
		var click_id = e.target.get('id');
		
		Y.log("click_id: " + Y.dump(click_id) );
		Y.log("tag_id: " + Y.dump(tag_id) );

		var cfg = {
					method: 'POST',
					data:   {
							'action': "remove_tag_from_object",
							'tag-id': tag_id,
							'type': 'content',
							'object_name': Y.one("#uuid").get("value")
					},
					on:     {success: handleSucces},
					arguments: click_id 
		};
		
		Y.io('/request/jstree.php', cfg);

	};
   
   Y.one('#remove-tags').delegate('hover', over, out, '.tag');
   Y.one('#remove-tags').delegate('click', remove_tag, 'a'); 
   
   Y.one('#object-tags').delegate('hover', over, out, '.tag');
	Y.one('#object-tags').delegate('click', remove_tag_from_object, 'a');

});

   
		 
		var tabview_content = new Y.TabView({
			srcNode: '#yuieditor_tabview'
		});
 
		tabview_content.render(); 
		
		
		tabview_content.after('selectionChange', function (e) {

			//Y.log('new val '+  Y.dump(e.newVal.get('label')));
			//Y.log('prev val '+  Y.dump(e.prevVal.get('index')));

			if(e.newVal.get('label') == 'Uploader'){
				loadMultiUploader(function (success) {
					//alert('get the fuck out of here!');
				});
			}
			
			if(e.newVal.get('label') == 'Media Management'){
				loadModulMediaManagement(function (success) {
					//alert('get the fuck out of here!');
				});
			}
			
			if(e.newVal.get('label') == 'URL Controller'){
				loadModulURLController(function (success) {
					//alert('get the fuck out of here!');
				});
			}


		});
		
		
		
		
		var loadModulMediaManagement = function (callbackFunction) {
			Y.lazyLoad('photo', 'slider',   function (errors, attached) {
				if (errors) {
					callbackFunction(false);
					return;
				}
				if (attached['photo'] && attached['slider'] ) {
					Y.log('Media Manager');
				}
				callbackFunction(true);
			});
		};
		

		var loadMultiUploader = function (callbackFunction) {
			Y.lazyLoad('multi-uploader',  function (errors, attached) {
				if (errors) {
					callbackFunction(false);
					return;
				}
				if (attached['multi-uploader']) {
					Y.log('load multiupload ');
					
					var overlayRegion = Y.one("#selectLink").get('region');
					Y.one("#uploaderOverlay").set("offsetWidth", overlayRegion.width);
					Y.one("#uploaderOverlay").set("offsetHeight", overlayRegion.height);
					
				}
				callbackFunction(true);
			});
		};
		
		
		
		var loadModulURLController = function (callbackFunction) {
			Y.lazyLoad('data-table',  function (errors, attached) {
				if (errors) {
					callbackFunction(false);
					return;
				}
				if (attached['data-table']) {
					Y.log('data table');
				}
				callbackFunction(true);
			});
		};
		
		
		



    var jq = Y.YQuery();
 

 	var jQueryPlugins = [
		'js/jstree/jquery.jstree.js', 
		'js/jstree/jquery.cookie.js',
		'js/jstree/jquery.hotkeys.js'
	];
					
							 
	jq.version = '1.7.1';

	jq.use( jQueryPlugins, function(e) {
			  
		$("#baum")
		.jstree({ 
			"plugins" : [ "themes", "json_data", "ui", "crrm", "cookies", "dnd", "search", "types", "hotkeys", "contextmenu" ],
			"json_data" : {
				"data" : [{
					"attr" : { "id" : "1" }, 
					"data" : "Home", 
					"state" : "closed"
				}],	
				"ajax" : {
					"url"   : "/request/jstree.php",
					"data"  : function (n) {
						return { 
							"type" : "list", 
							"server" : "true", 
							"id" : n.attr ? n.attr("id").replace("node_","") : 1 
							}; 
					}
				}
			},		
			"ui" : {
				"initially_select" : [ "1" ]
			},
			"dnd" : {
				"drop_finish" : function () { 
					//alert("DROP"); 
				},
			"drag_check" : function (data) {
				return { 
					after : false, 
					before : false, 
					inside : true 
				};
			},
			"drag_finish" : function (data) { 
				var node = 	data.r.attr("id").replace("node_","");
				var uuid = 	data.o.id.replace("drag_","");
				var drag_id = 	"#drag_"+uuid;
				var node_id = 	"#node_"+node;
				var node_to_remove = Y.one(drag_id);
				var text = node_to_remove.get('text');
				
				Y.log("create dump :  " + Y.dump(data) );
				Y.log("uuid :  " + Y.dump(uuid) );
									
								
				var data = {                                  
					"state" : "open",
					"data" : {
						"attr" : { "uuid" : uuid }, 
						"title" : node_to_remove.get('text')
					}			
				};				

				//node.get('parentNode').removeChild(node); // node is an instance of Node
				//Y.log("create dump :  " + Y.dump($.data.o.text() ) );
				function callback () {			
					node_to_remove.get('parentNode').removeChild(node_to_remove); 
				}
							
							
				$("#baum").jstree("create", node_id, "first", data, callback, true   );
				//$.text(data.o)
						
			}
		}			
	}).bind("create.jstree", function (e, data) {
	
		if( Y.Lang.isUndefined(data.args[2]) ){

			var post_data = { 
				"server" : "true",
				"type" : "create", 
				"target_id" : data.rslt.parent.attr("id").replace("node_",""),
				"move_type" : 'inside',
				"name" : data.rslt.name
			}
		
		}else{
		
			var post_data = { 
				"server" : "true",
				"type" : "create", 
				"target_id" : data.rslt.parent.attr("id").replace("node_",""),
				"move_type" : 'inside',
				"uuid" : data.args[2].data[0].attr.uuid,
				"name" : data.rslt.name
			}
						
		}

		Y.log("post data :  " + Y.dump(post_data));

					$.post(
						"/request/jstree.php", 
						post_data, 
		
					function (r) {
							if(r.status == 200) {
								$(data.rslt.obj).attr("id",  r.result);
								n.add({'message': r.statusmsg});
							}else{
								Y.log("create dump :  " + Y.dump(r));
								n.add({'message': r.statusmsg +' for '+  r.request, });
								$.jstree.rollback(data.rlbk);
							}
		
						}
		
					);
	}).bind("move_node.jstree", function (e, data) {
					data.rslt.o.each(function (i) {
						$.ajax({
							async : false,
							type: 'POST',
							url: "/request/jstree.php",
							data : {
								"server" : "true", 
								"type" : "move", 
								"src_id" : $(this).attr("id").replace("node_",""),  
								"target_id" : data.rslt.r.attr("id").replace("node_",""),  
								"move_type" : data.rslt.p,
								"title" : data.rslt.name,
								"copy" : data.rslt.cy ? 1 : 0
							},
		
							success : function (r) {
		
							n.add({'message': r.statusmsg});
							
								if(!r.status == 200) {
									$.jstree.rollback(data.rlbk);
								}else{
									$(data.rslt.oc).attr("id", "node_" + r.id);
									if(data.rslt.cy && $(data.rslt.oc).children("UL").length) {
										data.inst.refresh(data.inst._get_parent(data.rslt.oc));
									}
								}
								$("#analyze").click();
							}
						});
					});
	}).bind("rename.jstree", function (e, data) {
					$.post(
						"/request/jstree.php", 
						{ 
							"server" : "true", 
							"type" : "rename", 
							"target_id" : data.rslt.obj.attr("id").replace("node_",""),
							"name" : data.rslt.new_name
		
						}, 
		
						function (r) {
						
							n.add({'message': r.statusmsg});
							
							if(r.status != 200) {
								$.jstree.rollback(data.rlbk);
							}
							
						}
		
					);
	}).bind("remove.jstree", function (e, data) {
					data.rslt.obj.each(function () {
						$.ajax({
							async : false,
							type: 'POST',
							url: "/request/jstree.php",
							data : { 
								"server"    : "true", 
								"type"      : "delete", 
								"target_id" : this.id.replace("node_","")
							}, 
							success : function (r) {
								n.add({'message': Y.dump(r.statusmsg)});
								data.inst.refresh();
							}
						});
					});
	}).bind("select_node.jstree", function (e, data) {
		
		var node_id = data.rslt.obj.attr("id").replace("node_","");
		
		Y.log("id : " + Y.dump(node_id));
		{/literal}
		var uri  = "http://{$smarty.server.SERVER_NAME}{$pfad}/request/jstree.php";
		{literal}
		
		var query_string  = 'server=server';
			query_string += '&id=' + node_id;
			query_string += '&type=loadcontent';
			
		var cfg = {
			method: 'GET',
			data: query_string,
			on: {
				success: loadconten
				},
			arguments: {
				id: node_id,
				type: 'loadcontent'
			}
		};

		function loadconten(id, response) {
		
			try {
				var data = Y.JSON.parse(response.responseText);
			}
			catch (ex) {
				 n.add({'message': 'could not recive data'});
			}
		  
			n.add({'message': data.statusmsg});
		  
			if(data.status == 200){
		  
				Y.log(" Link : " + Y.dump(data.result.link));	
		  
				var LinkData = data.result.tree;
									
					for(var i=0, len=LinkData.length; i<len; i++){

						if(LinkData[i].value === data.result.link){
							var LinkName = LinkData[i].text;
							i = len; // exit the loop
						}
					}
               
               if (LinkName){
                  Y.one('#InputLinkId').setAttrs({"value": LinkName});
               }else{
                  Y.one('#InputLinkId').setAttrs({"value": ""});
               }

				
				
				Y.log(" LinkData : " + Y.dump(LinkData));	
			
			
				var inputNodeLinkID = Y.one('#InputLinkId').plug(Y.Plugin.AutoComplete, {
						 resultHighlighter: 'phraseMatch',
						 maxResults: 10,
						 resultFilters    : 'phraseMatch',
						 resultTextLocator: 'text',
						 source: LinkData
  					});
			
				inputNodeLinkID.ac.on('select', function (e) {
					var result = e.result;
					
					Y.log("inputNodeLinkID result click : " + Y.dump(result.raw.value));	
					Y.one('#linkidhidden').setAttrs({"value": result.raw.value});
				});

		  
			

					Y.one('#editor-response').set('innerHTML', '');
					Y.one('#editor-response').setStyle('border', 0);

					var myEditor = YUI.allevo.editor,
						EditorContent2 = YUI.allevo.editor2,
						buttonLable = "None",
						i = 0,
						LinkName = "";
						backgroundPicture ="",
						LinkData = "",
						oMenu = "";
   
									 
						Y.on('domready', function() {			 

									 Y.one('#nestedsetid').setAttrs({"value": data.id});	
									 Y.one('#uuid').setAttrs({"value": data.uuid});
									

									 myEditor._undoCache = null;
									 myEditor._undoLevel = null;
                            
                            var contentActive = Y.one('#ContentActive');
                            
                            contentActive.on("click", function (e) {

                                 Y.log(e);

                              });
                            
                            
									 
									 if( typeof data.result.active  != 'undefined' && data.result.active != null && data.result.active == 1){
									 	contentActive.set('checked', 1);
										Y.one('#yahooEditor').addClass('active');
										Y.one('#yahooEditor').removeClass('inactive');
									 }else{
									 	contentActive.set('checked', 0);
										Y.one('#yahooEditor').addClass('inactive');
										Y.one('#yahooEditor').removeClass('active');
									 }
									 
									//Y.log("editor: " + Y.dump(data));
									//myEditor.toolbar.destroyButton('fontname');

									if (typeof data.result.content  != 'undefined' && data.result.content != null) {
											Y.one('#editable').setContent(data.result.content);
											//Y.log("editor: " + Y.dump(myEditor));
											myEditor.setEditorHTML(data.result.content);
									}else{
											Y.one('#editable').setContent("");
											//Y.log("editor: " + Y.dump(data.result.content));	
											myEditor.setEditorHTML("");
									}
									
									if (typeof data.result.content2  != 'undefined' && data.result.content2 != null) {
											EditorContent2.setEditorHTML(data.result.content2);
									}else{
											EditorContent2.setEditorHTML("");
									}
                           

               Y.one("#uuid").set("value", data.result.uuid);
            
               var objectTags = Y.one('#object-tags');
               objectTags.setContent("");
               
               //YUI.searchContentTags.ac.results = "";
               
               Y.one('#ac-input-tags-content').setAttrs({"value": ""});
               Y.one('#ac-input-tags-content').ac.set("results", "");

  
            	Y.Object.each(data.result.tags, function (value, index){
                  var item = Y.Node.create('<li class="tag" >'+value
                                          +'<a title="Remove this tag" class="remove-tag" href="#">'
                                          +'<span class="tag-id hidden">'+index+'</span>'
                                          +'</a>'
                                          +'</li>');
                  objectTags.append(item);
               });

					if ( Y.Lang.type(YUI.allevo.publisher) != "undefined"  ) {	
									
						if (typeof data.result.query_string  != 'undefined' && data.result.query_string != null &&  Y.Lang.type(YUI.allevo.publisher.querystring) != "undefined"  ) {
							YUI.allevo.publisher.querystring.fire("global_notification:on_new_data_querystring", ({data: data.result.query_string}) );	
						}

						if (typeof data.result.media  != 'undefined' && data.result.media != null &&  Y.Lang.type(YUI.allevo.publisher.media) != "undefined" ) {
							var photos =  data.result.media;
							Y.log("photos slider id " + Y.dump(id));
							YUI.allevo.publisher.media.fire("global_notification:on_new_photo", ({photos: data.result.media, id: id}) ); 
							id = id +1;
						}
					}		
				});	
			}
		}	

		Y.log("uri : " + Y.dump(uri));
		Y.log("query_string : " + Y.dump(query_string));

		Y.io(uri, cfg);

	});

		});

			
	
			

	

 
});
	
</script>

 




	
{/literal}
