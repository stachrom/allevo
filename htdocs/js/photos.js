
// Photo Browser implementation for the finishers.ch media Manager.
YUI.add('photo', function (Y) {


	Y.on('domready', function () {
	
	YUI.namespace('allevo.Media');																
	YUI.namespace('allevo.publisher');

	var publisher = new Y.EventTarget();
	publisher.name = 'global publisher';
	 
	publisher.publish('global_notification:on_new_photo', {
		 broadcast:  2,   // global notification
		 emitFacade: true // emit a facade so we get the event target
	});
	
	
	Y.Global.on('global_notification:on_new_photo', function(e) {

		 Y.log("publischer photos: " + Y.dump(e.photos));
		 loadpictures(e.id, e.photos);

   });

	YUI.allevo.publisher.media = publisher;


    var wrapper = Y.one('#yui-main .yui-g ul');
    //Set it's height to the height of the viewport so we can scroll it.
    wrapper.setStyle('height', (wrapper.get('winHeight') - 250 )+ 'px');
	Y.log('height: '+ wrapper.get('winHeight'));
	
	
    Y.on('windowresize', function() { wrapper.setStyle('height', (wrapper.get('winHeight') - 250 )+ 'px'); });

	

		//YUI.allevoMedia.collectMedia();
		
		var handleResetMedia = function(o) {
			
		    wrapper.all('li').each(function(node) {
       		this.removeClass('album');
				this.removeClass('left_side');
				this.removeClass('remove');
				
           });
			 
			Y.all('#photoList li').each(function(node) {
			  if (!node.hasClass('all')) {
					//get all Drop Targets except the all photos.
					node.one('span').set('innerHTML', '(0)');
			  }
    		});

		}
		
		
		
		
		YUI.allevo.Media.collectmedia = function() {
		

			function img (album, siedpictures, deleteimg) {
					 this.album = album;
					 this.siedpictures = siedpictures;
					 this.deleteimg = deleteimg;
				}
				
		 		var wrapper = Y.one('#yui-main .yui-g ul');
				var server_data = new Object();
				var album = false;
				var siedpictures = false;
				var deleteimg = false;

			 wrapper.all('li').each(function(node){
			
				if (this.test('.album')) {
					album = true;
				}else{
					album = false;	
				}
				
				if (this.test('.left_side')) {
			  		siedpictures = true;
				}else{
					siedpictures = false;	
				}
				
				if (this.test('.remove')) {
			  		deleteimg = true;
				}else{
					deleteimg = false;	
				}
				
				if(this.one('img'))
				{
					server_data[this.one('img').getAttribute('title')] = new img(album, siedpictures, deleteimg);
				}
				
			 });

			if (server_data){
				var jsonStr = Y.JSON.stringify(server_data);
				return	jsonStr;
			}
	

		}
		
		
		
		
		
		var handleStart = function(o) {
		Y.log("start up ");
		
		}
		var handleSuccess = function(o, response) { 
		
		
		
			try {
				var data = Y.JSON.parse(response.responseText);
			}
			catch (ex) {
				 //n.add({'message': 'could not recive data'});
			}
			
			Y.log("data: "  + Y.dump(data) );	
			
			loadpictures(o, data);
			
		}
		var handleFailure = function(o) {}
		

		var handleManualLoad = function(o) {

			var tag_id = o.target.get('children').get('text');	
			Y.Lang.trim(tag_id);
			
			Y.log('tag id: '+ tag_id);

			var cfg = {
				method: 'GET',
				data:   {
					'action': 'getimg',
					'tag_id': tag_id
				},
				
				on: {
						start: handleStart,
						success: handleSuccess,
						failure: handleFailure
					}
			};

			Y.io('/request/images.php', cfg);

		}


		Y.on('click', handleManualLoad, "#manual-media-load", this);	
		Y.on('click', handleResetMedia, "#media-reset", this);
	



 function loadpictures(id, o) {

	handleResetMedia();

	Y.one('#yui-main .yui-g ul').removeClass('loading');
		
	var id = id; // Transaction ID.
	var photos = o.photos;

    if (photos) {

		var allddnode = Y.all('li.yui3-dd-draggable');
		wrapper.set('innerHTML', "");

        //Walk the returned photos array
        Y.each(photos, function(v, k) {

			//Create our URL
            var url = './' + v.path +''+ v.img;

			//Create the image and the LI
			li = Y.Node.create('<li class="loading"><img src="' + url + '" title="' + v.title + '"></li>');

			  	if (typeof v.basket != "undefined") {
						
					var count_seitenbild = 0;
					var count_album = 0;
						
					Y.each(v.basket, function(v, k) {
						if(k == "album"){
							li.addClass('album');
							count_album = count_album + 1;
						}
							
						if(k == "leftside"){
							li.addClass('left_side');
							count_seitenbild = count_seitenbild + 1;

						}
						
					});

					Y.one('#left_side').one('span').set('innerHTML', '(' + count_seitenbild + ')');
					Y.one('#album').one('span').set('innerHTML', '(' + count_album + ')');
				}
			  
			  
            //Get the image from the LI
          img = li.get('firstChild');
          //Append the li to the wrapper
			 		
			 	
			 
          wrapper.appendChild(li);
          //This little hack moves the tall images to the bottom of the list
          //So they float better ;)
          img.on('load', function() {
                    //Is the height longer than the width?
                    var c = ((this.get('height') > this.get('width')) ? 'tall' : 'wide');
                    this.addClass(c);
                    if (c === 'tall') {
                        //Move it to the end of the list.
                        this.get('parentNode.parentNode').removeChild(this.get('parentNode'));
                        wrapper.appendChild(this.get('parentNode'));
                    }
                    this.get('parentNode').removeClass('loading');
                });
            });
            //Get all the newly added li's
            wrapper.all('li').each(function(node) {
														  
												  
														  
                //Plugin the Drag plugin
                this.plug(Y.Plugin.Drag, {
                    offsetNode: false
                });
                //Plug the Proxy into the DD object
                this.dd.plug(Y.Plugin.DDProxy, {
                    resizeFrame: false,
                    moveOnEnd: false,
                    borderStyle: 'none'
                });
            });
            //Create and render the slider 
				
				// only for the first time.
				
				if(id == 1){


				var sl = new Y.Slider({
						 length: '200px', value: 40, max: 70, min: 5
					}).render('.horiz_slider');
				
					sl.after('valueChange',function (e) {
                //Insert a dynamic stylesheet rule:
						 var sheet = new Y.StyleSheet('image_slider');
						 sheet.set('#yui-main .yui-g ul li', {
							  width: (e.newVal / 2) + '%'
						 });
            	});
            //Listen for the change
            //Remove the DDM as a bubble target..
            sl._dd.removeTarget(Y.DD.DDM);
				
					}	
            //Remove the wrappers loading class
            wrapper.removeClass('loading');
            Y.one('#ft').removeClass('loading');
        }
    };
	 
	 
	  

	
	 
    //Listen for all mouseup's on the document (selecting/deselecting images)
    Y.delegate('mouseup', function(e) {
        if (!e.shiftKey) {
            //No shift key - remove all selected images
            wrapper.all('img.selected').removeClass('selected');
        }
        //Check if the target is an image and select it.
        if (e.target.test('#yui-main .yui-g ul li img')) {
            e.target.addClass('selected');
        }
    }, document, '*');
	
	
	
	
	
	    //Listen for all clicks on the '#photoList img' selector
    Y.delegate('click', function(e) {

        //Prevent the click
        e.halt();
        //Remove all the selected items
		
		Y.log(Y.dump(e.currentTarget));
       

    }, document, '.yui3-dd-draggable img');
	
	
	
	
	
	
	
	
	
	
	
	

    //Listen for all clicks on the '#photoList li' selector
    Y.delegate('click', function(e) {

        //Prevent the click
        e.halt();
        //Remove all the selected items
        e.currentTarget.get('parentNode').all('li.selected').removeClass('selected');
        //Add the selected class to the one that one clicked
        e.currentTarget.addClass('selected');
        //The "All Photos" link was clicked
        if (e.currentTarget.hasClass('all')) {
            //Remove all the hidden classes
            wrapper.all('li').removeClass('hidden');
        } else {
            //Another "album" was clicked, get it's id
            var c = e.currentTarget.get('id');
            //Hide all items by adding the hidden class
            wrapper.all('li').addClass('hidden');
            //Now, find all the items with the class name the same as the album id
            //and remove the hidden class
            wrapper.all('li.' + c).removeClass('hidden');
        }

    }, document, '#photoList li');

    //Stop the drag with the escape key
    Y.one(document).on('keypress', function(e) {
        //The escape key was pressed
        if ((e.keyCode === 27) || (e.charCode === 27)) {
            //We have an active Drag
            if (Y.DD.DDM.activeDrag) {
                //Stop the drag
                Y.DD.DDM.activeDrag.stopDrag();
            }
        }
    });
    //On the drag:mouseDown add the selected class
    Y.DD.DDM.on('drag:mouseDown', function(e) {
        e.target.get('node').all('img').addClass('selected');
    });
    //On drag start, get all the selected elements
    //Add the count to the proxy element and offset it to the cursor.
    Y.DD.DDM.on('drag:start', function(e) {
        //How many items are selected
        var count = wrapper.all('img.selected').size();
        //Set the style on the proxy node
        e.target.get('dragNode').setStyles({
            height: '25px', width: '25px'
        }).set('innerHTML', '<span>' + count + '</span>');
        //Offset the dragNode
        e.target.deltaXY = [25, 5];
    });
    //We dropped on a drop target
    Y.DD.DDM.on('drag:drophit', function(e) {
        //get the images that are selected.
        var imgs = wrapper.all('img.selected'),
            //The xy position of the item we dropped on
            toXY = e.drop.get('node').getXY();
        
        imgs.each(function(node) {
            //Clone the image, position it on top of the original and animate it to the drop target
            node.get('parentNode').addClass(e.drop.get('node').get('id'));
            var n = node.cloneNode().set('id', '').setStyle('position', 'absolute');
            Y.one('#media-management').appendChild(n);
            n.setXY(node.getXY());
            new Y.Anim({
                node: n,
                to: {
                    height: 20, width: 20, opacity: 0,
                    top: toXY[1], left: toXY[0]
                },
                from: {
                    width: node.get('offsetWidth'),
                    height: node.get('offsetHeight')
                },
                duration: .5
            }).run();
        });
        //Update the count
        var count = wrapper.all('li.' + e.drop.get('node').get('id')).size();
        e.drop.get('node').one('span').set('innerHTML', '(' + count + ')');
    });
    //Add drop support to the albums
    Y.all('#photoList li').each(function(node) {
        if (!node.hasClass('all')) {
            //make all albums Drop Targets except the all photos.
            node.plug(Y.Plugin.Drop);
        }
    });
	
	

	Y.delegate('click', function(e) {
        e.halt();
		Y.log('click event '+ Y.dump(e.currentTarget));
		Y.one("#object-name").set("value", e.currentTarget.get('title'));
		Y.one("#image_replacement").setContent("<img src='"+ e.currentTarget.get('src')+"' width='300px' >");
		
		send('get_tags');
		
		panelTager.show(); 
		
    }, document, '.yui3-dd-draggable img');
	

	var panelTager = new Y.Panel({
  		srcNode: "#panelContent",
  		width: 350,
  		centered: true,
  		visible: false,
  		modal:true,
  		zIndex:5,
  		headerContent: "Tagger",
  		plugins: [Y.Plugin.Drag]
	});

	panelTager.addButton({
		value: "Reset",
		action: function(e) { 
			e.preventDefault();
			inputNode.set('value', "" );			
		},
		section: Y.WidgetStdMod.FOOTER
	});
	
	
	panelTager.addButton({
		value: "Cancel",
		action: function(e) { 
			e.preventDefault();
			inputNode.set('value', "" );			 
			panelTager.hide(); 
		},
		section: Y.WidgetStdMod.FOOTER
	});

	panelTager.addButton({
		value: "Save",
		action: function(e) { 
			e.preventDefault(); 
			send('add_tags');
		},
		section: Y.WidgetStdMod.FOOTER
	});

	panelTager.render();
	
	
	var inputNode = Y.one('#ac-input-tag').plug(Y.Plugin.AutoComplete, {
		activateFirstItem: true,
		allowTrailingDelimiter: true,
		minQueryLength: 0,
		queryDelay: 0,
		queryDelimiter: ',',
		resultHighlighter: 'startsWith',
		resultListLocator: 'data.tags',
		resultTextLocator: 'name',
		source: '/request/jstree.php?action=search_tags&q={query}',

		resultFilters: ['startsWith', function (query, results) {
	 
		  var selected = inputNode.ac.get('value').split(/\s*,\s*/);
		  selected.pop();
		  selected = Y.Array.hash(selected);
		  return Y.Array.filter(results, function (result) {
			return !selected.hasOwnProperty(result.text);
		  });
		}]
	});
 
  inputNode.on('focus', function () {
    inputNode.ac.sendRequest('');
  });
 
  // After a tag is selected, send an empty query to update the list of tags.
  inputNode.ac.after('select', function () {
    inputNode.ac.sendRequest('');
    inputNode.ac.show();
  });


  
  
	var handleSuccesTags = function(id, o, a) {
	  
		var data = o.responseText; 
				
		try {
			var json_data = Y.JSON.parse(data);
		}catch (o) {
			json_data= [];
		}			

		Y.log("Tag IDS: " + Y.dump(json_data));

		if (json_data.status != 200) {
			//error deal with it!

		}else{

		//n.add({'message': json_data.statusmsg});
		
		
			if(json_data.status == 200 && json_data.action == "get_tags"){
				inputNode.set('value', "" );
				
				Y.one("#present_tags").setContent("");
				
			

				Y.each(json_data.data.tags, function(tag_name, tag_id) {

					Y.one("#present_tags").append(
						"<li class='tag' tabindex='"+tag_id+"' >" + tag_name +
						"<a href='#' class='remove-tag' title='Remove this tag'><span class='tag-id hidden'>"+tag_id+"</span></a>"+
						"</li>"
						);

				});			
			}
			
			if (json_data.status == 200 && json_data.action == "remove_tag_from_object" ) {
				// remove the tag from the dom before do animation
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
				panelTager.hide();
				inputNode.set('value', "" );
			}

		}

	}

	function over(e) {
        e.currentTarget.addClass('tag-hover');
    }
	 
	function out(e) {
        e.currentTarget.removeClass('tag-hover');;
    }
	
	function remove_tag(e) {
	
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
							'type': 'bilder',
							'object_name': Y.one("#object-name").get("value")
					},
					on:     {success: handleSuccesTags},
					arguments: click_id 
		};
		
		Y.io('/request/jstree.php', cfg);

	};	
	
	
  
	function send(action) {

		Y.log("value input: " + Y.dump(inputNode.get('value')));

		var tags        = inputNode.get('value');
		var type_id     = "bilder";
		var object_name = Y.one("#object-name").get("value");
		
		Y.log("object_name " + Y.dump(object_name));

		var cfg = {
			method: 'POST',
			data:   {
				'action': action,
				'tags': tags,
				'type': type_id,
				'object_name': object_name
				},
			on:     {success: handleSuccesTags}
		};

		Y.io('/request/jstree.php', cfg);

	};
	

	
	
	Y.one('#present_tags').delegate('hover', over, out, '.tag');
	Y.one('#present_tags').delegate('click', over, '.tag');
	Y.one('#present_tags').delegate('click', remove_tag, 'a');
	
	
	
	Y.one('#pictures_tags').delegate('click', handleManualLoad, 'li');

	
	
	

	
	


});
	
	
}, '1.0.0', {requires: [
    'node', 'event',  'json-parse', 'io-form',  'json', 'dd-plugin', 'datasource-io', 'transition', 'panel', 'event-hover', 'event-custom', 'autocomplete', 'autocomplete-highlighters', 'autocomplete-filters', 'anim', 'dd', 'dd-plugin', 'dd-drop-plugin', 'slider', 'stylesheet', 'event-delegate', 'dump', 'json-stringify', 'io', 'panel'
]});
