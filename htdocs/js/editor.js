// Editor implementation for the finishers.ch media Manager.
YUI.add('editor-allevo', function (Y) {

      var YAHOO = Y.YUI2;	
      
      YAHOO.widget.TrustingEditor = function(el, attrs) {
        YAHOO.widget.TrustingEditor.superclass.constructor.call(this, el, attrs );
      };
      
      YAHOO.extend(YAHOO.widget.TrustingEditor, YAHOO.widget.Editor, {
           _cleanIncomingHTML: function(html) {
               html = html.replace(/<script([^>]*)>/gi, '<YUI_SCRIPT$1>');
               html = html.replace(/<\/script([^>]*)>/gi, '</YUI_SCRIPT>');
               html = YAHOO.widget.TrustingEditor.superclass._cleanIncomingHTML.call(this, html);
               html = html.replace(/<YUI_SCRIPT([^>]*)>/gi, '&lt;script$1&gt;');
               html = html.replace(/<\/YUI_SCRIPT([^>]*)>/gi, '&lt;/script&gt;');
               return html;
           },
           cleanHTML: function(html) {
               if (!html)
                   html = this.getEditorHTML();
               delete this.invalidHTML.script;
               html = html.replace(/&lt;script([^&>]*)&gt;/g, '<script$1>');
               html = html.replace(/&lt;\/script&gt;/g, '<\/script>');
               html = YAHOO.widget.TrustingEditor.superclass.cleanHTML.call(this, html);
               return html;
           }
      });

	Y.on('domready', function () {				

		YUI.namespace('allevo.editor');	
		YUI.namespace('allevo.editor2');

		var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
		  state = 'off',
		  status = null,
		  myeditor = null,
		  el = new YAHOO.util.Element('editor-response'),
		  inhalt = YAHOO.util.Dom.get('editor-response');

    var EditorContent2Config = {
        height: '300px',
        width: '600px',
        dompath: true,
        filterWord: true,
        removeLineBreaks: false,
        focusAtStart: true,
		  toolbar: {
            titlebar: 'Inhalt',
            limitCommands: true,
            collapse: true,
            buttons: [
                { group: 'textstyle', label: 'Font Style',
                    buttons: [
                        { type: 'push', label: 'Bold', value: 'bold' },
                        { type: 'push', label: 'Italic', value: 'italic' },
                        { type: 'push', label: 'Underline', value: 'underline' },
                        { type: 'separator' },
                        { type: 'spin', label: '13', value: 'fontsize', range: [ 9, 75 ], disabled: true },
                        { type: 'separator' },
                        { type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
                        { type: 'color', label: 'Background Color', value: 'backcolor', disabled: true }
                    ]
                },
					 { type: 'separator' },
					 { group: 'indentlist', label: 'Listen',
						  buttons: [
								{ type: 'push', label: 'Indent', value: 'indent', disabled: true },
								{ type: 'push', label: 'Outdent', value: 'outdent', disabled: true },
								{ type: 'push', label: 'Create an Unordered List', value: 'insertunorderedlist' },
								{ type: 'push', label: 'Create an Ordered List', value: 'insertorderedlist' }
						  ]
					 },
					 { type: 'separator' },
					 { group: 'parastyle', label: 'Paragraph Style', 
						  buttons: [ 
							  { type: 'select', label: 'Normal', value: 'heading', disabled: false, 
									menu: [ 
										 { text: 'Normal', value: 'none', checked: true }, 
										 { text: 'Header 1', value: 'h1' }, 
										 { text: 'Header 2', value: 'h2' }, 
										 { text: 'Header 3', value: 'h3' }, 
									    { text: 'Header 4', value: 'h4' }, 
										 { text: 'Header 5', value: 'h5' }, 
										 { text: 'Header 6', value: 'h6' } 
									] 
							  	} 
							  ] 
	    					},
							
					{ type: 'separator' }, 
					{ group: 'insertitem', label: 'Insert Item', 
					 	 buttons: [ 
					 		{ type: 'push', label: 'HTML Link CTRL + SHIFT + L', value: 'createlink', disabled: true }, 
					 		{ type: 'push', label: 'Insert Image', value: 'insertimage' } 
					 	] 
					} 		
				]
        	} 
		};
	 
	 

	 
	 	var EditorContent2 = new YAHOO.widget.Editor('content2', EditorContent2Config);
		
 	    //var cleanPasteContent2 = new CleanPaste(EditorContent2);
  		//Y.log("response js editor " + Y.dump(editor));

        var handleSuccess = function(o) {
			  
			try {
				var data = Y.JSON.parse(o.responseText);
			} catch (o) {
			
			}

				//Dom.get('editable').innerHTML = myEditor.get('textarea').value;
				
				Y.one('#editable').setContent(myEditor.get('textarea').value);
				
				Dom.setStyle('yahooEditor-box', 'position', 'absolute');
				Dom.setStyle('yahooEditor-box', 'top', '-9999px');
				Dom.setStyle('yahooEditor-box', 'left', '-9999px');
				Dom.setStyle('editable', 'display', 'block');
							
				el.setStyle('background-color', '#BFFEBC');
				el.setStyle('border', '#098802 1px solid');
				inhalt.innerHTML ='ID <strong>'+data.result+'</strong> gespeichert.';
				
						Y.log("response " + Y.dump(data.result));
				
	
	
        }
		  
        var handleFailure = function(o) {
				YAHOO.log("error save button DATA: " + YAHOO.lang.dump(o));
				el.setStyle('background-color', '#FFEFF0');
				el.setStyle('border', '#AF0007 1px solid');
				inhalt.innerHTML ='<strong>' + o.statusText + '</strong>:  Daten nicht gespeichert!';	

        }
 
        var callback = {
            success: handleSuccess,
            failure: handleFailure,
        };
		  
		var gutter = null;

      var myConfig = {
         height: '500px', 
         width: 'auto', 
         dompath: true,
         filterWord: true,
         removeLineBreaks: false,
         focusAtStart: false, 
        };
		  

        //var save_button = new YAHOO.widget.Button('submitEditor');
		var saveButton = Y.one('#submitEditor');
      var myEditor = new YAHOO.widget.TrustingEditor('editor', myConfig);

      delete myEditor.invalidHTML.iframe;
      delete myEditor.invalidHTML.from;
      delete myEditor.invalidHTML.input;
      delete myEditor.invalidHTML.button;
      delete myEditor.invalidHTML.select;
      delete myEditor.invalidHTML.link;
      delete myEditor.invalidHTML.style;
      delete myEditor.invalidHTML.textarea;

			YAHOO.gutter = function() {
				return {
							status: false,
							gutter: null,
							createGutter: function() {
								 YAHOO.log('Creating gutter (#gutter1)', 'info', 'example');
								 this.gutter = new YAHOO.widget.Overlay('gutter1', {
									  height: '500px',
									  width: '300px',
									  context: [myEditor.get('element_cont').get('element'), 'tl', 'tr'],
									  position: 'absolute',
									  visible: false
								 });
								 this.gutter.hideEvent.subscribe(function() {
									  myEditor.toolbar.deselectButton('flickr');
									  Dom.setStyle('gutter1', 'visibility', 'visible');                
									  var anim = new YAHOO.util.Anim('gutter1', {
											width: {
												 from: 300,
												 to: 0
											},
											opacity: {
												 from: 1,
												 to: 0
											}
									  }, 1);
									  anim.onComplete.subscribe(function() {  
											Dom.setStyle('gutter1', 'visibility', 'hidden');
									  });
									  anim.animate();
								 }, this, true);
								 this.gutter.showEvent.subscribe(function() {
									  myEditor.toolbar.selectButton('flickr');
									  this.gutter.cfg.setProperty('context', [myEditor.get('element_cont').get('element'), 'tl', 'tr']);
									  Dom.setStyle(this.gutter.element, 'width', '0px');
									  var anim = new YAHOO.util.Anim('gutter1', {
											width: {
												 from: 0,
												 to: 300
											},
											opacity: {
												 from: 0,
												 to: 1
											}
									  }, 1);
									  anim.animate();
								 }, this, true);
								 var warn = '';
								 if (myEditor.browser.webkit || myEditor.browser.opera) {
									  warn = myEditor.STR_IMAGE_COPY;
								 }
								 this.gutter.setBody('<h2>Flickr Bilder Suche</h2><label for="flikr_search">Tag:</label><input type="text" value="" id="flickr_search"><div id="flickr_results"><p>Enter flickr tags into the box above, separated by commas. Be patient, this example my take a few seconds to get the images..</p></div>' + warn);
								 this.gutter.render(document.body);
							},
							open: function() {
								 Dom.get('flickr_search').value = '';
								 YAHOO.log('Show Gutter', 'info', 'example');
								 this.gutter.show();
								 this.status = true;
							},
							close: function() {
								 YAHOO.log('Close Gutter', 'info', 'example');
								 this.gutter.hide();
								 this.status = false;
							},
							toggle: function() {
								 if (this.status) {
									  this.close();
								 } else {
									  this.open();
								 }
							}
				}
			}

			myEditor.on('editorContentLoaded', function() {
				var head = this._getDoc().getElementsByTagName('head')[0];
				var link = this._getDoc().createElement('link');
				link.setAttribute('rel', 'stylesheet');
				link.setAttribute('type', 'text/css');
				link.setAttribute('href', 'http://'+YUI.allevo.HTTP_HOST+'/css/content.css');
				head.appendChild(link);

			}, myEditor, true);
			
			
			EditorContent2.on('toolbarLoaded', function() {
			
				var codeConfig = {
					type: 'push', label: 'Edit HTML Code', value: 'editcode'
				};

				this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
				
				this.toolbar.on('editcodeClick', function() {
					var ta = this.get('element'),
						iframe = this.get('iframe').get('element');

					if (state == 'on') {
						state = 'off';
						this.toolbar.set('disabled', false);
						Y.log('Inject the HTML from the textarea into the editor', 'info', 'example');
						this.setEditorHTML(ta.value);
						if (!this.browser.ie) {
							this._setDesignMode('on');
						}

						Dom.removeClass(iframe, 'editor-hidden');
						Dom.addClass(ta, 'editor-hidden');
						this.show();
						this._focusWindow();
					} else {
						state = 'on';
						Y.log('Show the Code Editor', 'info', 'example');
						this.cleanHTML();
						Y.log('Save the Editors HTML', 'info', 'example');
						Dom.addClass(iframe, 'editor-hidden');
						Dom.removeClass(ta, 'editor-hidden');
						this.toolbar.set('disabled', true);
						this.toolbar.getButtonByValue('editcode').set('disabled', false);
						this.toolbar.selectButton('editcode');
						this.dompath.innerHTML = 'Editing HTML Code';
						this.hide();
					}
					return false;
				}, this, true);

				this.on('cleanHTML', function(ev) {
					YAHOO.log('cleanHTML callback fired..', 'info', 'example');
					this.get('element').value = ev.html;
				}, this, true);
				
				this.on('afterRender', function() {
					var wrapper = this.get('editor_wrapper');
					wrapper.appendChild(this.get('element'));
					this.setStyle('width', '100%');
					this.setStyle('height', '100%');
					this.setStyle('visibility', '');
					this.setStyle('top', '');
					this.setStyle('left', '');
					this.setStyle('position', '');

					this.addClass('editor-hidden');
				}, this, true);
			}, EditorContent2, true);
			
			
			
	 
			myEditor.on('toolbarLoaded', function() {
			
				var codeConfig = {
					type: 'push', label: 'Edit HTML Code', value: 'editcode'
				};
			  
				gutter = new YAHOO.gutter();

				var flickrConfig = {
					type: 'push',
					label: 'Insert Flickr Image',
					value: 'flickr'
				}
   
				myEditor.toolbar.addButtonToGroup(flickrConfig, 'insertitem');
				
				
				this.toolbar.on('insertimageClick', function() 
				{
						//Get the selected element
						var _sel = this._getSelectedElement();
						//If the selected element is an image, do the normal thing so they can manipulate the image
						if (_sel && _sel.tagName && (_sel.tagName.toLowerCase() == 'img')) {
							 //Do the normal thing here..
						} else {
							 //They don't have a selected image, open the image browser window
							 win = window.open('/request/images.php?action=getimg', 'IMAGE_BROWSER', 'left=20,top=20,width=500,height=500,toolbar=0,resizable=0,status=0');
							 if (!win) {
								  //Catch the popup blocker
								  alert('Please disable your popup blocker!!');
							 }
							 //This is important.. Return false here to not fire the rest of the listeners
							 return false;
						}
				}, this, true);
				
				

				myEditor.toolbar.on('flickrClick', function(ev) {
				  YAHOO.log('flickrClick: ' + YAHOO.lang.dump(ev), 'info', 'example');
				  this._focusWindow();
				  if (ev && ev.img) {
						YAHOO.log('We have an image, insert it', 'info', 'example');
						//To abide by the Flickr TOS, we need to link back to the image that we just inserted
						var html = '<a href="' + ev.url + '"><img src="' + ev.img + '" title="' + ev.title + '"></a>';
						this.execCommand('inserthtml', html);
				  }
				  gutter.toggle();
				}, myEditor, true);
			 
				gutter.createGutter();

				this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
			  
				this.toolbar.on('editcodeClick', function() {
					var ta = this.get('element'),
						 iframe = this.get('iframe').get('element');
	
					if (state == 'on') {
						 state = 'off';
						 this.toolbar.set('disabled', false);
						 YAHOO.log('Show the Editor', 'info', 'example');
						 YAHOO.log('Inject the HTML from the textarea into the editor', 'info', 'example');
						 this.setEditorHTML(ta.value);
						 if (!this.browser.ie) {
							  this._setDesignMode('on');
						 }
	
						 Dom.removeClass(iframe, 'editor-hidden');
						 Dom.addClass(ta, 'editor-hidden');
						 this.show();
						 this._focusWindow();
					} else {
						 state = 'on';
						 Y.log('Show the Code Editor', 'info', 'example');
						 this.cleanHTML();
						 Y.log('Save the Editors HTML', 'info', 'example');
						 Dom.addClass(iframe, 'editor-hidden');
						 Dom.removeClass(ta, 'editor-hidden');
						 this.toolbar.set('disabled', true);
						 this.toolbar.getButtonByValue('editcode').set('disabled', false);
						 this.toolbar.selectButton('editcode');
						 this.dompath.innerHTML = 'Editing HTML Code';
						 this.hide();
					}
					return false;
				}, this, true);
	
				this.on('cleanHTML', function(ev) {
					this.get('element').value = ev.html;
				}, this, true);
			  
				var tb = this.toolbar;
					  
				var configLayout = {
							type: 'select', label: 'Layout', value: 'layout', disabled: false,
								 menu: [
									  { text: '2 Reihen', checked: true, value:2 },
									  { text: '3 Reihen', value:3 },
									  { text: '4 Reihen', value:4 },
									  { text: '5 Reihen', value:5 },
									  { text: '6 Reihen', value:6 }
									
								 ]
					  };
					  
				this.toolbar.addButtonToGroup(configLayout, 'insertitem');	  
				this.toolbar.on('layoutClick', function(ev) {
							var button = ev.button;
							var i=1;
							
							var mylayout ='<table ><tr>';
							for (i=1;i<=button.value;i++){
							    mylayout +='<td>Zelle  '+i+'</td>';
							}
 							    mylayout +='</tr></table>';
								 
							this.execCommand('inserthtml', mylayout ); 
				}, myEditor, true);	  
				this.on('afterRender', function() {
							var wrapper = this.get('editor_wrapper');
							wrapper.appendChild(this.get('element'));
							this.setStyle('width', '100%');
							this.setStyle('height', '100%');
							this.setStyle('visibility', '');
							this.setStyle('top', '');
							this.setStyle('left', '');
							this.setStyle('position', '');
							this.addClass('editor-hidden');
				}, this, true); 

			}, myEditor, true);

   		myEditor.on('afterOpenWindow', function() {
			  //When the window opens, disable the url of the image so they can't change it
			  var url = Dom.get(myEditor.get('id') + '_insertimage_url');
			  if (url) {
					url.disabled = true;
			  }
   		}, myEditor, true);


			myEditor.render();
			EditorContent2.render();

			var editable = Y.one("#editable");

			editable.on("dblclick", function (e) {

            myEditor.setEditorHTML(Y.one('#editable').get("innerHTML"));
				
				Dom.setStyle('yahooEditor-box', 'position', 'static');
				Dom.setStyle('yahooEditor-box', 'top', '');
				Dom.setStyle('yahooEditor-box', 'left', '');
				Dom.setStyle('editable', 'display', 'none');
            
				myEditor._focusWindow();
				
			});


		 
    saveButton.on('click', function(e) {
        	//Event.stopEvent(e);

        	myEditor.saveHTML();
			EditorContent2.saveHTML();

		  	var formObject = document.getElementById('nestedset-editor-content-form');
		  	YAHOO.widget.Button.addHiddenFieldsToForm(formObject);
		  
		  	YAHOO.util.Connect.setForm(formObject);
		  
		   var editordata = encodeURIComponent(myEditor.getEditorHTML());
			Dom.get('editable').innerHTML = editordata;
			

			if( Y.Lang.type(YUI.allevo.querystring) != 'undefined'){
			
				var querystring_records = YUI.allevo.querystring.dt.getRecordSet().getRecords();
			
				var querystring = new Array(),
					i = 0,
					j = 0;
				for(i, len=querystring_records.length; i<len; i++){

					if(querystring_records[i]._oData.ap === "aktiv"){
						//YAHOO.log("records: " + YAHOO.lang.dump(querystring_records[i]._oData));
						querystring[j] = new Object();
									
						if(querystring_records[i]._oData.meta_Value === "Date"){
							var datum = new Date(querystring_records[i]._oData.Value);
							querystring[j][querystring_records[i]._oData.Parameter] = datum.getTime()/1000;
						}else{
							querystring[j][querystring_records[i]._oData.Parameter] = querystring_records[i]._oData.Value;	
						}

						j = j +1;
					}
				}
			}

			if( Y.Lang.type(YUI.allevo.Media) != "undefined" && Y.Lang.type(YUI.allevo.Media.collectmedia) == 'function'){
				var jsonStr = YUI.allevo.Media.collectmedia();
			}
			
				var id = Dom.get('nestedsetid').value;	
				var sUrl = 'http://'+ YUI.allevo.HTTP_HOST +'/request/jstree.php';
            var data  = 'server=server';
            var tags  = Y.one('#ac-input-tags-content').get('value');
            data += '&tags='+ tags;
				data += '&media=' + jsonStr;
				data += '&query_string=' + Y.JSON.stringify(querystring); 
				data += '&id=' + Dom.get('nestedsetid').value;
				data += '&type=savecontent';
            
				//Y.log("data" + Y.dump(data));

				var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, data);
    });

	Event.onDOMReady(function() {
        status = Dom.get('status');
	});

	YAHOO.util.Event.onAvailable('flickr_search', function() {
		 YAHOO.log('onAvailable: #flickr_search', 'info', 'example');
		 YAHOO.util.Event.on('flickr_results', 'mousedown', function(ev) {
			  YAHOO.util.Event.stopEvent(ev);
			  var tar = YAHOO.util.Event.getTarget(ev);
			  if (tar.tagName.toLowerCase() == 'img') {
					if (tar.getAttribute('fullimage', 2)) {
						 YAHOO.log('Found an image, insert it..', 'info', 'example');
						 var img = tar.getAttribute('fullimage', 2),
							  title = tar.getAttribute('fulltitle'),
							  owner = tar.getAttribute('fullowner'),
							  url = tar.getAttribute('fullurl');
						 this.toolbar.fireEvent('flickrClick', { type: 'flickrClick', img: img, title: title, owner: owner, url: url });
					}
			  }
		 }, myEditor, true);
		 YAHOO.log('Create the Auto Complete Control', 'info', 'example');
		 oACDS = new YAHOO.widget.DS_XHR("request/flickr_proxy.php",
			  ["photo", "title", "id", "owner", "secret", "server", "url_o" ]);
		 oACDS.scriptQueryParam = "tags";
		 oACDS.responseType = YAHOO.widget.DS_XHR.TYPE_XML;
		 oACDS.maxCacheEntries = 0;
		 oACDS.scriptQueryAppend = "method=flickr.photos.search";
	
		 // Instantiate AutoComplete
		 oAutoComp = new YAHOO.widget.AutoComplete('flickr_search','flickr_results', oACDS);
		 oAutoComp.autoHighlight = false;
		 oAutoComp.alwaysShowContainer = true; 
		 oAutoComp.formatResult = function(oResultItem, sQuery) {
		 
			YAHOO.log("flickr antwort:  " + YAHOO.lang.dump(oResultItem));
			  // This was defined by the schema array of the data source
			  var sTitle = oResultItem[0];
			  var sId = oResultItem[1];
			  var sOwner = oResultItem[2];
			  var sSecret = oResultItem[3];
			  var sServer = oResultItem[4];
			  var urlo = oResultItem[5];
			  var urlPart = 'http:/'+'/static.flickr.com/' + sServer + '/' + sId + '_' + sSecret;
			  var sUrl = urlPart + '_s.jpg';
			  var lUrl = urlo;
			  var fUrl = 'http:/'+'/www.flickr.com/photos/' + sOwner + '/' + sId;
			  var sMarkup = '<img src="' + sUrl + '" fullimage="' + lUrl + '" fulltitle="' + sTitle + '" fullid="' + sOwner + '" fullurl="' + fUrl + '" class="yui-ac-flickrImg" title="Click to add this image to the editor"><br>';
			  return (sMarkup);
		 };
	});
	
	YUI.allevo.editor = myEditor;	
	YUI.allevo.editor2 = EditorContent2;	

	});

}, '1.0.0', {requires: [
'event', 'dump', 'json', 'node', 'event-delegate', "yui2-animation", "yui2-autocomplete", "yui2-button", "yui2-connection", "yui2-container", "yui2-dom", "yui2-editor", "yui2-element", "yui2-event", "yui2-menu"
]});
