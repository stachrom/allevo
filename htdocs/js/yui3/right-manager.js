YUI.add('right-manager', function (Y) {

	var list1 = new Y.Sortable({
        container: '#list1',
        nodes: 'li',
        opacity: '0.1'
    });

    var list2 = new Y.Sortable({
        container: '#list2',
        nodes: 'li',
        opacity: '0.1'
    });
	
	 var list3 = new Y.Sortable({
        container: '#list3',
        nodes: 'li',
        opacity: '0.1'
    });
	
	var listUsers = new Y.Sortable({
        container: '#users',
        nodes: 'li',
        opacity: '1',
		moveType: 'copy'
    });
	
	var listGroups = new Y.Sortable({
        container: '#groups',
        nodes: 'li',
        opacity: '1',
		moveType: 'copy'
    });
	
    list1.join(list2);
	list3.join(listGroups);
	
	var preventDarg = function(e) {
		// Titles are not dragable 
		if( e.target.get('node').hasClass('group-hd') ){
		 e.preventDefault();
		};
	};
	
	var checkDrop = function(e) {
		// node will be removed from Dom 
		if( e.drag.get('node').hasClass('group-is-added') ){
			e.drag.get('node').remove();
			Y.one('#groups').removeClass('highlight');
		};
	};
	
	var checkForContainer = function(e) {
		// Check if we drag from outside in the group container.
		if(e.same === false){
			if( e.drag.get('node').hasClass('ready-to-add') ){
				e.drag.get('node').replaceClass('ready-to-add', 'group-is-added ');
			};
		};
	};
	
	
	var lableDropTarget = function(e) {
		// node will be removed from Dom 
		
		var dropzone = Y.one('#groups');

		if( e.drag.get('node').hasClass('group-is-added') ){
			dropzone.addClass('highlight');
		};
	};
	
	var removeLableDropTarget = function(e) {
		// remove highlight class from the dom 
		var dropzone = Y.one('#groups');
		if( dropzone.hasClass('highlight') ){
			dropzone.removeClass('highlight');
		};

	};
	
	
	listGroups.on('copy', checkForContainer);
	Y.DD.DDM.on('drag:mouseDown', preventDarg);
	Y.DD.DDM.on('drop:hit', checkDrop);
	Y.DD.DDM.on('drag:over', lableDropTarget);
	Y.DD.DDM.on('drag:exit', removeLableDropTarget);
	
	
	

	
	
	
	var handleSucces = function(id, o, a) {
	  
		var data = o.responseText; 
				
		try {
			var json_data = Y.JSON.parse(data);
		}catch (o) {
			json_data= [];
		}

		var displayAreaRights  = function (areas, rights, node ){

			Y.Array.each(areas, function (value, index){
				var item = Y.Node.create('<li class="areas group-hd '+value.area_define_name+'">'+value.area_define_name+' : '+value.area_id
										+'<span class="hidden user-id">'+value.area_id+'</span></li>');
				node.append(item);

				Y.Array.each(rights[value.area_define_name], function (value2, index2s){
					var item = Y.Node.create('<li class="rights '+value.area_define_name+' ">'
											+'<div class=" '+value2.right_define_name+'">'+value2.right_define_name+' : '+value2.area_id
											+'<span class="hidden right_id">'+value2.right_id+'</span>'
											+'</div></li>');
					node.append(item);
				});
				
			});
		};		

		Y.log("empfangene Daten : " + Y.dump(json_data));
		

		if( json_data.status == 200 ){
		
			var action = json_data.action;

			var liste1 = Y.one('#list1');
			var liste2 = Y.one('#list2');
			var liste3 = Y.one('#list3');
			var liste4 = Y.one('#list4');
		
			// clear liste
			liste1.setContent("");
			liste2.setContent("");
			liste3.setContent("");
			liste4.setContent("");
		
		
			if( action == 'initial-load' ){
			
				var user_container = Y.one('#users');
				var group_container = Y.one('#groups');
				var area_container = Y.one('#areas');

				var users  = json_data.data.users;
				var groups = json_data.data.groups;
				var areas  = json_data.data.areas;
				
				Y.Array.each(users, function (value, index){
					user_container.append('<li class="users" >'+ value.handle +' <span class="id hidden" >'+value.perm_user_id+'</span><span class="action hidden" >get-user</span></li>');	
				});	
				
				Y.Array.each(groups, function (value, index){
					group_container.append('<li class="group ready-to-add">'+value.group_define_name+'<span class="hidden id">'+value.group_id+'</span><span class="action hidden" >get-group</span></li>');
				});	
				
				Y.Array.each(areas, function (value, index){
					area_container.append('<li class="areas">'+value.area_define_name+'<span class="hidden id">'+value.area_id+'</span><span class="action hidden" >get-area</span></li>');
				});	

			}
		


			if( action == 'get-user' ){
		
				var rights = json_data.data.rights;
				var areas = json_data.data.areas;
				var users = json_data.data.users;
				var groups = json_data.data.groups;

				displayAreaRights (areas, rights, liste1);
				
				
				var item = Y.Node.create('<li class=" group-hd userrights ">ist in der Gruppe: '
										+'<span class="hidden id">group_ids</span>'
										+'<span class="hidden which-id">group_ids</span>'
										+'<span class="hidden type">user_is_in_group</span>'
										+'</li>');
				liste3.append(item);

				
				Y.Array.each(users, function (value, index){
					var item = Y.Node.create('<li class=" group-hd userrights ">Benutzerrechte von '+value.handle
											+'<span class="hidden id">'+value.perm_user_id+'</span>'
											+'<span class="hidden which-id">perm_user_id</span>'
											+'<span class="hidden type">user-right</span>'
											+'</li>');
					liste2.append(item);
					
					Y.Array.each(value.grantet_user_rights, function (value, index){
						var item = Y.Node.create('<li class="rights '+value.area_define_name+' ">'
												+'<div class=" '+value.right_define_name+'">'+value.right_define_name+' : '+value.area_id
												+'<span class="hidden right_id">'+value.right_id+'</span>'
												+'</div></li>');
						liste2.append(item);
					});	

					if(value.group_ids){
						 Y.Array.each(value.group_ids, function (value, index){
							 Y.log('group ids:  '+ value); 
							 Y.log('group :  '+ groups); 
							 Y.Array.each(groups, function (group, index){
								 if(value == group.group_id){
									 var item = Y.Node.create('<li class="group-is-added group '+group.group_define_name+' ">'
															+'<div class=" '+group.group_define_name+'">'+group.group_define_name 
															+'<span class="hidden id">'+group.group_id+'</span>'
															+'</div></li>');
									liste3.append(item);
								 }
							 });
						});  
					}

				});

			};	
		
			if( action == 'get-group' ){
			
				var rights = json_data.data.rights;
				var areas = json_data.data.areas;
				var groups = json_data.data.groups;
				var group_rights = json_data.data.group_rights;

				displayAreaRights (areas, rights, liste1);

				Y.Array.each(groups, function (value, index){
					var item = Y.Node.create('<li class=" group-hd grouprights ">Gruppenrechte: '+value.group_define_name
											+'<span class="hidden id">'+value.group_id+'</span>'
											+'<span class="hidden which-id">group_id</span>'
											+'<span class="hidden type">group-right</span>'
											+'</li>');
					liste2.append(item);
				});

				Y.Object.each(group_rights, function (value, index){
					var item = Y.Node.create('<li class="rights '+value.area_define_name+' ">'
											+'<div class=" '+value.right_define_name+'">'+value.right_define_name+' : '+value.area_id
											+'<span class="hidden right_id">'+index+'</span>'
											+'</div></li>');
					liste2.append(item);
				});
			};
		
			
			if( action == 'get-area' ){
			
				var rights = json_data.data.rights;
				var areas = json_data.data.areas;
				
				Y.log('daten'+ Y.dump(areas));

				Y.Object.each(areas, function (value, index){

					var item = Y.Node.create('<li class=" group-hd grouprights ">Area: '+value.area_define_name+'</li>');
						liste1.append(item);

					Y.Object.each(rights[value.area_define_name], function (value, index){
						var item = Y.Node.create('<li class="rights '+value.area_define_name+' ">'
												+'<div class=" '+value.right_define_name+'">'+value.right_define_name+' : '+value.area_id
												+'<span class="hidden right_id">'+value.right_id+'</span>'
												+'</div></li>');
						liste1.append(item);
					});
					
					
					var item = Y.Node.create('<li class=" group-hd grouprights ">Erstelle neues Recht in '+value.area_define_name+'</li>');
						liste4.append(item);
					
					var item = Y.Node.create('<li class="rights new-right"><div>'
												+'<label for="right_define_name">Name </label> <input type="text" value="" id="right_define_name" name="right_define_name"> '
												+'</div></li>');
					liste4.append(item);
					
					var item = Y.Node.create('<li class=" group-hd grouprights ">zu löschende Rechte: '+value.area_define_name
											+'<span class="hidden id">'+value.area_id+'</span>'
											+'<span class="hidden which-id">area_id</span>'
											+'<span class="hidden type">are-rights</span>'
											+'</li>');
						liste2.append(item);

				
				});

			};
			
			
			
			list1.sync();
			list2.sync();
			list3.sync();
			listUsers.sync();
			listGroups.sync();

		}else{
		
		
		Y.log("status : " + Y.dump(json_data.statusmsg));
		
		}

	}

	function handleSend(e) {

	
		var selection2 = Y.one('#list2').get('children'),
			selection1 = Y.one('#list1').get('children'),
			selection3 = Y.one('#list3').get('children'),
			selection4 = Y.all('#list4 input'),
			myData = {},
			action = "",
			group  = "";
			myData["rr"] = new Array ();
			myData["groups"] = new Array ();
			myData['meta'] =  new Array ();
			myData["new"] =  new Array ();
			
			
	
		if ( Y.Lang.isUndefined(e) ){
			action = "initial-load";
		}else{
		
		Y.log("action "+ Y.dump(this.get('text')) );	
			action =  this.one('.action').get('text');
			myData['id'] = this.one('.id').get('text');
		}	
		
		//Y.log('action '+ action );	
		//Y.log('my Data '+ Y.dump(myData) );	

			
			
		if (action == "send" ){
			selection2.each(function (taskNode) {
				//Y.log('id: '+ taskNode.get('id') );
				// extract Data from the List --> #liste2.li
				if( taskNode.hasClass('group-hd') ){
					id_meta = taskNode.one('.which-id').get('text');
					id = taskNode.one('.id').get('text');
					type = taskNode.one('.type').get('text');
					myData[id] = new Array ();
					myData['meta'].push( {  'id' : id, 'idType': id_meta,'type': type});
				}else{
					myData[id].push(taskNode.one('.right_id').get('text'));
				}
			});
			
			
			
			selection1.each(function (taskNode) {
				// extract Data from the List --> #liste1.li
				
				
				if( taskNode.hasClass('group-hd') ){
					
				}else{
					myData["rr"].push(taskNode.one('.right_id').get('text'));
				}
			});
			
			selection3.each(function (taskNode) {
				// extract Data from the List --> #liste3.li
				Y.log('node3 '+ Y.dump(taskNode.get('id')));
				
				if( taskNode.hasClass('group-hd') ){
					
				}else{
					myData["groups"].push(taskNode.one('.id').get('text'));
				}
			});
			
			selection4.each(function (input) {
				// extract Data from the List --> #liste4.li
				Y.log('node4 inside each '+ Y.dump(input));

				myData["new"].push(input.get('value'));

			});
			
			
			
			
			
			
			
		}
		
		if (action == "get-areas" ){
	
		}
		if (action == "get-group" ){
		
		}
		if (action == "get-user" ){
		
		}
		

		var jsonStr = Y.JSON.stringify(myData);
		Y.log('data send'+ Y.dump(myData) );
		//Y.log('action: '+ action);
		

		var cfg = {
			method: 'POST',
			data:   {
				'action': action,
				'jsonData' : jsonStr
				},
			on: {success: handleSucces}
		};

		Y.io('request/liveuser.php', cfg);

	};
	
	Y.on('domready', function () {
		handleSend();
	});
	
	Y.delegate("click", handleSend, "#permissions", ".eventSend li");

}, '1.0.0', {requires: [
    'dd-constrain', 'sortable', 'attribute', 'datasource-io',  'event-hover', 'json-parse', 'json-stringify', 'event', 'anim', 'json', 'anim'
]});