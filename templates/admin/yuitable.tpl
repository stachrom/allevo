<div id="listtable"></div>
<div id="meat"></div>
<div id="showtable"></div>
<div id="add-row">
   <div id="bt-add-row"></div>

</div>

<div id="yui-table-loger"></div>


<script type="text/javascript">





{literal}

YAHOO.namespace("yuitable");
 this.myLogReader = new YAHOO.widget.LogReader("yui-table-loger");
 

 (function() {
 
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event,
		  action = '',
		  table_name ='',
		  json_string ='';
		  
  
	



   Event.onDOMReady(function() {

			tableRequest(action = '', table_name ='', json_string ='');

			//*******************//
			// Table create Code //
			//*******************//
			
			YAHOO.oMenuButton1 = new YAHOO.widget.Button("rowcount", { 
                                        type: "menu", 
                                        menu: "rowcount1select" });	
	 
	 

		 
			var handleCreateTable = function() {
				var action = "create_table";
				var table_name = this.getData().table_name;
				
				function row (type, length) {
					 this.type = type;
					 this.length = length;
				}
		
				var rows = new Object();
		
				for ( i in this.getData()){
		
					if( i.indexOf('row') !=-1){
						YAHOO.log("tuppel: " +YAHOO.lang.dump(this.getData()[i]));
						var tuppel = this.getData()[i];
					}
					
					if( i.indexOf('radiobuttons') !=-1 ){
						YAHOO.log("type: "+YAHOO.lang.dump(this.getData()[i]));
						if( this.getData()[i] == "date" ){
							rows[tuppel] = new row(this.getData()[i]);
						}else{
							rows[tuppel] = new row(this.getData()[i], 255 );
						}
					}
				}
		
				var json_string = YAHOO.lang.JSON.stringify(rows);
				YAHOO.log("json string "+ YAHOO.lang.dump(json_string));
			
				tableRequest(table_name, action, json_string );
				this.submit();
			};
			var handleCancel = function() {
				this.cancel();
			};
			var handleSuccess = function(o) {
				var response = o.responseText;
			};
			var handleFailure = function(o) {
				alert("Submission failed: " + o.status);
			};
	 		YAHOO.util.Dom.removeClass("tablecreate", "yui-pe-content");
			
			YAHOO.tablecreate = new YAHOO.widget.Dialog("tablecreate", 
							{ width : "45em",
							  fixedcenter : true,
							  visible : false,
							  modal : true,
							  hideaftersubmit: true, 
							  postmethod:"none", 
							  constraintoviewport : true,
							  buttons : [ { text:"Submit", handler:handleCreateTable, isDefault:true },
								           { text:"Cancel", handler:handleCancel }
											]
							});

			YAHOO.tablecreate.validate = function() {
				var data = this.getData();
				if (data.table_name == "" ) {
					alert("Bitte einen Tabellennamen Angeben");
					return false;
				} else {
					return true;
				}
			};
		 
			// Wire up the success and failure handlers
			YAHOO.tablecreate.callback = { 
										success: handleSuccess,
										failure: handleFailure 
										};

			YAHOO.tablecreate.render();
			

			//	Click event listener for row count 
			var onMenuClick = function (p_sType, p_aArgs) {
					  var oEvent    = p_aArgs[0],	//	DOM event
							oMenuItem = p_aArgs[1],	//	MenuItem instance that was the target of the event
							row ='';					   // row elements
					
						if (oMenuItem) {
						
							for (i=1; i<=oMenuItem.value; i++){
								YAHOO.log("durchlauf " + i);
								row +='<div class="row">';
								row +='<label for="row'+ i +'"> Bezeichnung Reihe '+i+ ' : </label>';
								row +='<input type="textbox" name="row'+i+'" />';
								//row +='<div class="clear"></div>';
								//row +='<label for="radiobuttons['+i+']">Type:</label>'; 
								row +='<input type="radio" name="radiobuttons['+i+']" value="text" checked/> Text Feld';
								row +='<input type="radio" name="radiobuttons['+i+']" value="date" /> Datum';
								row +='<input type="radio" name="radiobuttons['+i+']" value="integer" /> Zahl';
								row +='</div>';
							}
							
							var content_row = Dom.get("container-rows");
				
							content_row.innerHTML = row;
							YAHOO.log("[MenuItem Properties] text: " + oMenuItem.cfg.getProperty("text") + ", value: " + oMenuItem.value);
						}
			};
		
			//	Add a "click" event listener for the Button's row count
			YAHOO.oMenuButton1.getMenu().subscribe("click", onMenuClick);

			YAHOO.log("Create Table: " + YAHOO.lang.dump(table_name));
		
   });
	

	 
	var onTableLinkClick = function (event, matchedEl, container) {	
		var table_name = matchedEl.parentNode.id;
		
		if (Dom.hasClass(matchedEl, "create-table")) {
			YAHOO.log("create Table!!"+ YAHOO.tablecreate.show );
			YAHOO.tablecreate.show();
		}
		
	
		if (Dom.hasClass(matchedEl, "show-table")) {
		  getTable(table_name); 
		  YAHOO.log("Show Table: " + YAHOO.lang.dump(table_name));  
		}
	
		if (Dom.hasClass(matchedEl, "edit-table")) {
			
			YAHOO.log("Edit Table: " + YAHOO.lang.dump(table_name));  
		}
	
		if (Dom.hasClass(matchedEl, "delete-table")) {

			var action = "drop_table";
			
			tableRequest(table_name, action);
			YAHOO.log("Delete Table: " + YAHOO.lang.dump(table_name)); 
		}
	};


	Event.delegate("listtable", "click", onTableLinkClick, "a");	 
	 

 
	function tableRequest(table_name, action, json_string ){
		var callback = {
			success:function(response){
		  		try{
					var responseVal = YAHOO.lang.JSON.parse(response.responseText);
				}catch(x){
					YAHOO.log("error " + YAHOO.lang.dump(response));
					alert("JSON Parse failed!");
					return;
				}
				
				


					
					var el = new YAHOO.util.Element('listtable');
					var inhalt = YAHOO.util.Dom.get('listtable'),
					    content="",
					    action= new Array(),
					    code= new Array(),   
						 replyText = "",
						 error = "";
					

					YAHOO.log("Json DATA: " + YAHOO.lang.dump(responseVal));
					
		for(i in responseVal ){
				switch (i) {
				  case "action":
				  	 action = responseVal[i];
					 YAHOO.log("action: " + YAHOO.lang.dump(action));
					 break;
				  case "code":
					 YAHOO.log("code: " + YAHOO.lang.dump(responseVal[i]));
					 cood = responseVal[i];
					 break;
				  case "replyText":
					 YAHOO.log("replyText: " + YAHOO.lang.dump(responseVal[i]));
					 replyText = responseVal[i];
					 break;
				  case "error":
					 YAHOO.log("error: " + YAHOO.lang.dump(responseVal[i]));
					 error = responseVal[i];
					 el.setStyle('background-color', '#FFEFF0');
					 el.setStyle('border', '#AF0007 1px solid');
					 el.setStyle('padding', '5px');
	
					 inhalt.innerHTML  = YAHOO.lang.dump(responseVal);
					 inhalt.innerHTML  += '<br> <strong> <a  href="/"  > WATCH OUT!!! Be nice with the Tables! nochmals versuchen...</a> </strong>';
					 break;	 
					 
				  default:
					 break;
				}
					
		}




					if (responseVal.code['list_table'] == 200 ) {
					
						el.setStyle('background-color', '#ffffff');
						el.setStyle('border', '0px');
						el.setStyle('padding', '5px');
						 
						var tables = responseVal.yui_table;
						
						YAHOO.log("Tables : " + YAHOO.lang.dump(tables));

						content = '<h2> Tabellen</h2>';
						content += '<a class="create-table" href="#"  id="create-table" >neue Tabelle erstellen</a> <br/>';
						content += '<span class="server-respond">'+ responseVal.replyText[action['1']]+'</span>';
						content += '<ul>';
					 	for(var i in tables) {
						content += '<li id="'+ i +'"> ';
						content +=  i;
						content += ' <a class="show-table" href="#">zeigen</a> ';
						content += ' <a class="edit-table" href="#">bearbeiten</a> '; 
						content += ' <a class="delete-table" href="#">löschen</a>';
						content += '</li>';
						}	
						content += "</ul>";
						
						inhalt.innerHTML = content;
						
					}

	

		
					
					

					

					
						if (responseVal.code[action['1']] == 200 && action['1'] == "list_table" ){
							YAHOO.tablecreate.visible = false;
						}
						else if(i == 'error'){
							YAHOO.log("error: " + YAHOO.lang.dump(i));
						}
		
		  
					YAHOO.log("Table: " + YAHOO.lang.dump(responseVal) );
			},
			failer:function(response){
				YAHOO.log("Failer: " + YAHOO.lang.dump(response) );		  
		   }
		 
		};
 
		var sUrl = "request/table.php";
		var postData  = 'action='+ action;
	       postData += '&table='+ table_name;
		 	 postData += '&json_data='+ json_string;
		 
		YAHOO.log("anfrage senden: " + YAHOO.lang.dump(postData) );	
 
		var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
	}
		
		
		
	function DataProvider(url){
            this.url = url;
	
        }

   DataProvider.prototype = {
            url:null,
            data:null,
            ds:null,
            getData:function() {return this.data},
            initialize:function(){
                YAHOO.util.Connect.asyncRequest('GET', this.url, this);
            },
            success:function(response){
				
				
						try{
							var responseVal = YAHOO.lang.JSON.parse(response.responseText); 
						}catch(x){
							alert("JSON Parse failed!");
							return;
						}
					
						var tables = responseVal.yui_table;
						YAHOO.log("Json DATA: " + YAHOO.lang.dump(tables));
						
					 	for(var i in tables) { // fetch table content
							var value = tables[i];
							var table_name = this.url.split("table=");
						
						 	//YAHOO.log( i + YAHOO.lang.dump(value));
						 
						 	if( table_name[1] == i ){
							
							
							   // some editors
							 YAHOO.yuitable.ddEditor = new YAHOO.widget.DropdownCellEditor();
						
							   
								 var columnList = value.columnList;
								 
								 

								 
								 var responseSchema = value.responseSchema;
								 
								 		
								 this.data = value.results;
								 this.ds = new YAHOO.util.FunctionDataSource(function(){return this.dataProvider.getData()});
								 
								 this.ds.responseSchema = {
									  resultsList:"value.results",
									  fields:responseSchema
								 }

								 
								 YAHOO.log("responseSchema : " + YAHOO.lang.dump(responseSchema));
								 YAHOO.log("column List "+ i + YAHOO.lang.dump(columnList));
								 
						
								 
								// ADD a ROW --> HTML
								
								// if Dialog already exist, than destroy it!!
								 		if ( YAHOO.lang.isObject(YAHOO.addRowToTable) ) {
													YAHOO.addRowToTable.destroy();
										}else{
						
										}
				
				
										var nodeBtAddRow = YAHOO.util.Dom.get('bt-add-row');
										var el = document.createElement('div');
										    el.id = 'add-row-form';
										YAHOO.util.Dom.insertAfter(el, nodeBtAddRow);
										var nodeAddRowForm = YAHOO.util.Dom.get(el.id);
										var content = "";

       
                       				content += '<div class="hd">Datensatz zur Tabelle "'+table_name[1]+'" hinzufügen</div>'
											content += '<div class="bd">';
											content += '<form name="addRowToTable" method="POST" action="#">';
											content += '<input type="hidden" value="'+table_name[1]+'" name="table_name" />';
											for(var i in columnList) {
											if (columnList[i].label == "id" ){
											// id-Feld nicht übermitteln
											}else{
											content += '<label for="'+ columnList[i].key +'">'+ columnList[i].label +'</label> <input id="'+ columnList[i].key +'" name="'+ columnList[i].key +'" type="text" />  <br>';
											}
											
											}
											content += '</form>';
											content += '</div>';
											  
						
											nodeAddRowForm.innerHTML = content;
											nodeBtAddRow.innerHTML = ""; 
											
									
									

											YAHOO.oPushButtonAddRow = new YAHOO.widget.Button({ 
												 label:"Add Row", 
												 id:table_name[1], 
												 container:"bt-add-row" });

											function onButtonClick(p_oEvent) {

												YAHOO.addRowToTable.show();
											}


											YAHOO.oPushButtonAddRow.on("click", onButtonClick);
											
											
										
											
											
											YAHOO.addRowToTable = new YAHOO.widget.Dialog("add-row-form", 
													{ width : "22em",
													  fixedcenter : true,
							  						  visible : false,
													  modal : true,
													  hideaftersubmit: true, 
													  postmethod:"none", 
													  constraintoviewport : true,
													  buttons : [ 	{ text:"hinzufügen", handler:handleAddRowToTable, isDefault:true },
								           							{ text:"Cancel", handler:handleCancelRow }
																	
																	]
											});

									
					
											YAHOO.addRowToTable.render();

							}
					 	}

                	this.ds.dataProvider = this;
						/* make call to initialize your table using the data set */

					   					 //textEditor = new YAHOO.widget.TextboxCellEditor({ disableBtns: false, asyncSubmitter: submitter_cell_edit });
						YAHOO.yuitable.myDataTable = new YAHOO.widget.DataTable("showtable", columnList, this.ds, {caption: table_name[1]});

						
						 var highlightEditableCell = function(oArgs) {
								var elCell = oArgs.target;
								if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
									 this.highlightCell(elCell);
								}
						  };
						
						
    					YAHOO.yuitable.myDataTable.subscribe("cellMouseoverEvent", highlightEditableCell);
    					YAHOO.yuitable.myDataTable.subscribe("cellMouseoutEvent", YAHOO.yuitable.myDataTable.onEventUnhighlightCell);
						//YAHOO.yuitable.myDataTable.subscribe("cellClickEvent", YAHOO.yuitable.myDataTable.onEventShowCellEditor);
						
						YAHOO.yuitable.myDataTable.on('cellClickEvent',function() {
								this.onEventShowCellEditor.apply(this,arguments);
						});
						YAHOO.yuitable.ddEditor.dropdownOptions = [
							{value:0,label:'unknown'},
							{value:1,label:'one'},
							{value:2,label:'two'},
							{value:3,label:'three'}
						];
						YAHOO.yuitable.ddEditor.render();
						
						
						
			

						
YAHOO.log("ddEditor "+ i + YAHOO.lang.dump(ddEditor));
						
						
						
						
						
						
						
					
						//*****************************//
					   //      Row Context Menu      //
						// Destroy if already exist! //
					   //**************************//		


						if ( YAHOO.lang.isObject(YAHOO.yuitable.myContextMenu) ) {
							YAHOO.yuitable.myContextMenu.destroy();
						}else{
						
							}
						
						
						YAHOO.yuitable.myContextMenu = new YAHOO.widget.ContextMenu("mycontextmenu",{
																	 	trigger:YAHOO.yuitable.myDataTable.getTbodyEl(),
											 							lazyload: true
											 							});
						YAHOO.yuitable.myContextMenu.addItem("Delete Item");
						
								  // Render the ContextMenu instance to the parent container of the DataTable
						YAHOO.yuitable.myContextMenu.render("showtable");
						YAHOO.yuitable.myContextMenu.clickEvent.subscribe(onContextMenuClick, YAHOO.yuitable.myDataTable);
								  
						return {
										oDS: this.ds,
										oDT: YAHOO.yuitable.myDataTable
								};
						
						

					

						
						
						
						
						

			
						//console.debug(this.data);
            },
				failer:function(response){
				 		YAHOO.log("errorDATA: " + YAHOO.lang.dump(responseVal));
				}
        }
		  
		var handleCancelRow = function() {
												YAHOO.log("Add Row Data");
												this.cancel();
		}; 
											
		var handleAddRowToTable= function() {
												var action = "add_row";
												var row_data = this.getData();
												var table_name = this.getData().table_name;

												// delete the id than we do that id stuff in teh backend
												delete row_data.id;
												delete row_data.table_name;
												
												var json_string = YAHOO.lang.JSON.stringify(row_data);
												YAHOO.log("Add Row Data"+ YAHOO.lang.dump(json_string));
												tableRequest(table_name, action, json_string );
												
												//myDataTable.addRow(row_data, index); 
												
												this.submit();
		};
		
		

												  
  
		  
	function getTable(table_name){
	 var sUrl = "request/table.php?action=select&table="+ table_name;
    var dataProvider = new DataProvider(sUrl);
    dataProvider.initialize();
	}
	
	var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
										var task = p_aArgs[1];
										if(task) {
											 // Extract which TR element triggered the context menu
											 var elRow = this.contextEventTarget;
											 elRow = p_myDataTable.getTrEl(elRow);
											 
											
						 
											 if(elRow) {
												  switch(task.index) {
														case 0:     // Delete row upon confirmation
															 var oRecord = p_myDataTable.getRecord(elRow);
															 
															  YAHOO.log("context menu: " + YAHOO.lang.dump(oRecord));
															  
															 if(confirm("Are you sure you want to delete SKU " +
																		oRecord.getData("SKU") + " (" +
																		oRecord.getData("Description") + ")?")) {
																  p_myDataTable.deleteRow(elRow);
															 }
												  }
											 }
										}
								  };
								
	
	var submitter_cell_edit = function (callback, newValue, table_name){
        var record = this.getRecord();
        var column = this.getColumn();
        var oldValue = this.value;
        var datatable = this.getDataTable();
		  var json_string ='{"'+ column.key +'":'+ escape(newValue) +'}';

        // send the data to our update page to update the value in the database
        YAHOO.util.Connect.asyncRequest('POST', 'update.php',{
            success: function (o){
                var r = YAHOO.lang.JSON.parse(o.responseText);
 
                if (r.replyType === 'date') { r.data = YAHOO.util.DataSource.parseDate(r.data); }
 
                if (r.replyCode === '201') { callback(true, r.data); }
                else
                {
                    alert(r.replyText);
                    callback();
                }
            },
            failure: function (o){
                alert(o.statusText);
                callback();
            },
            scope: this
        	},
		  

        'action=update_cell&id=' + record.getData('id') +
		  			    '&json_data=' + json_string +
                    '&oldValue=' + escape(oldValue) +
                       '&table=' + table_name 
        );
    };
	 
	  
})();	
 
 
 {/literal}
</script> 

<div id="tablecreate" class="yui-pe-content"> 
	<div class="hd">Create Table</div> 
   <div class="bd"> 
   <form method="post" action="#" id="yui-table-form"> 
      <label for="firstname"><strong>Table Name*</strong></label><input type="textbox" name="table_name" /> 
   <input type="button" id="rowcount" name="row_count" value="anzahl Reihen">
   <select id="rowcount1select" name="rowcount1select">
         <option value="1">  1 </option> 
         <option value="2">  2 </option> 
         <option value="3">  3 </option>
         <option value="4">  4 </option> 
         <option value="5">  5 </option> 
         <option value="6">  6 </option>
         <option value="7">  7 </option> 
         <option value="8">  8 </option> 
         <option value="9">  9  </option>
         <option value="10"> 10 </option>                  
   </select>
    
      <div class="clear"></div> 
      <fieldset> 
         <legend><strong> Reihen </strong></legend>
         <div id="container-rows"></div>  
      </fieldset>
   
   <div class="clear"></div>
   
   </form> 
   </div> 
</div>
