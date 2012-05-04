// Data Table implementation for the finishers.ch media Manager.
YUI.add('data-table', function (Y) {


	var YAHOO = Y.YUI2;
   YUI.namespace('allevo.querystring');
	YUI.namespace('allevo.publisher');
	


	var publisher = new Y.EventTarget();
	publisher.name = 'global publisher';
	 
	publisher.publish('global_notification:on_new_data_querystring', {
		 broadcast:  2,   // global notification
		 emitFacade: true // emit a facade so we get the event target
	});
	
	

	Y.Global.on('global_notification:on_new_data_querystring', function(e) {
																							  
																							  
		 Y.log(e.data); // global publisher
		 
		
																							  
	//querry_string_data = e.data;
	
     update_datatable('', e.data);
	
	
    });

YUI.allevo.publisher.querystring = publisher;





 



Y.on("domready",  YUI.allevo.querystring = function() {
																	 
																	 
		Y.log("datatable is loading"); 
		 
			var Dom = YAHOO.util.Dom,
				 Event = YAHOO.util.Event,
				 DDM = YAHOO.util.DragDropMgr;
				 
				 myDTDrags = {};

			var formatterDispatcher = function (elCell, oRecord, oColumn,oData) {
				var meta = oRecord.getData('meta_' + oColumn.key);
				oColumn.editorOptions = meta.editorOptions;
				switch (meta) {
					case 'Number':
						YAHOO.widget.DataTable.formatNumber.call(this,elCell, oRecord, oColumn,oData);
						break;
					case 'Date':
						YAHOO.widget.DataTable.formatDate.call(this,elCell, oRecord, oColumn,oData);
						break;
					case 'Text':
						YAHOO.widget.DataTable.formatText.call(this,elCell, oRecord, oColumn,oData);
						break;
					case 'YesNo':
						elCell.innerHTML = oData;
						break;
				}
			};
			
			var editors = {
				Text: new YAHOO.widget.TextboxCellEditor(),
				Number:new YAHOO.widget.TextboxCellEditor({validator:function (val) { 
					val = parseFloat(val);
					if (YAHOO.lang.isNumber(val)) {return val;}
				}}),
				Date:new YAHOO.widget.DateCellEditor(),
				YesNo:new YAHOO.widget.RadioCellEditor({radioOptions:["aktiv","passiv"],disableBtns:true})
			};
			
			var myColumnDefs = [
				{key:"Parameter",label:'Parameter',className:'th'},
				{key:"Value",formatter:formatterDispatcher,editor:new YAHOO.widget.BaseCellEditor()},
				{key:"ap", label:'Aktiv/Passiv',formatter:formatterDispatcher,editor:new YAHOO.widget.BaseCellEditor()}
			];
			
						//YAHOO.log("tree " + YAHOO.lang.dump(ds), "autocomplyte");	
						
			update_datatable = function(type, args) {
				//YAHOO.log("oRequest " + YAHOO.lang.dump(args[0].data));
				
				dt.showTableMessage("Loading...");
				
				dt._oDataSource.liveData  = args;

  				dt.getDataSource().sendRequest('', { success: dt.onDataReturnInitializeTable, scope: dt }, dt);

				//var recs = dt.getRecordSet();
				//YAHOO.log("data" + YAHOO.lang.dump(recs));
			//this.ds.sendRequest("query=orders&results=10", oCallback);	
			}
			
			
			var querry_string_data;  
			
			//YAHOO.log("querry_string_data" + YAHOO.lang.dump(querry_string_data));
			

			var ds = new YAHOO.util.DataSource(querry_string_data);			

			ds.responseType = YAHOO.util.DataSource.TYPE_JSON;

			ds.responseSchema = {
			   resultsList: "Result",
				fields: ['Rows', 'Parameter', 'Value','meta_Value','ap','meta_ap']
				
				
			};
			
			
			ds.doBeforeParseData=function(oRequest, oFullResponse, oCallback){
				if(!oFullResponse){
					oFullResponse = {"Result":[]};
				}else{
					for (var n in oFullResponse.Result){
						// Fetch meta_ Value "Date" and transform the Value string into a javascript date
						if(oFullResponse.Result[n].meta_Value == "Date"){  
							oFullResponse.Result[n].Value = new Date(oFullResponse.Result[n].Value); 
						}
					}
				}  
				return oFullResponse; 
			}
			

			var dt = new YAHOO.widget.DataTable("tableContainer", myColumnDefs, ds, {initialLoad: false});

			dt.subscribe("cellClickEvent", function (oArgs) {
				var target = oArgs.target,
					record = this.getRecord(target),
					column = this.getColumn(target),
					type = record.getData('meta_' + column.key);
					
 
				column.editor = editors[type];
				this.showCellEditor(target);  
			});
			
		  //////////////////////////////////////////////////////////////////////////////
        // Custom drag and drop class
        //////////////////////////////////////////////////////////////////////////////
        YUI.allevo.querystring.DDRows = function(id, sGroup, config) {
            YUI.allevo.querystring.DDRows.superclass.constructor.call(this, id, sGroup, config);
            Dom.addClass(this.getDragEl(),"custom-class");
            this.goingUp = false;
            this.lastY = 0;
        };
		 
		 
		  //////////////////////////////////////////////////////////////////////////////
        // DDRows extends DDProxy
        //////////////////////////////////////////////////////////////////////////////
        YAHOO.extend(YUI.allevo.querystring.DDRows, YAHOO.util.DDProxy, {
            proxyEl: null,
            srcEl:null,
            srcData:null,
            srcIndex: null,
            tmpIndex:null,
 
            startDrag: function(x, y) {
                var    proxyEl = this.proxyEl = this.getDragEl(),
                    srcEl = this.srcEl = this.getEl();
 
                this.srcData = dt.getRecord(this.srcEl).getData();
                this.srcIndex = srcEl.sectionRowIndex;
                // Make the proxy look like the source element
                Dom.setStyle(srcEl, "visibility", "hidden");
                proxyEl.innerHTML = "<table><tbody>"+srcEl.innerHTML+"</tbody></table>";
            },
 
            endDrag: function(x,y) {
                var position,
                    srcEl = this.srcEl;
 
                Dom.setStyle(this.proxyEl, "visibility", "hidden");
                Dom.setStyle(srcEl, "visibility", "");
            },
 
           onDrag: function(e) {
                // Keep track of the direction of the drag for use during onDragOver
                var y = Event.getPageY(e);
                
                if (y < this.lastY) {
                    this.goingUp = true;
                } else if (y > this.lastY) {
                    this.goingUp = false;
                }
 
                this.lastY = y;
           },
 
           onDragOver: function(e, id) {
                // Reorder rows as user drags
                var srcIndex = this.srcIndex,
                    destEl = Dom.get(id),
                    destIndex = destEl.sectionRowIndex,
                    tmpIndex = this.tmpIndex;
 
                if (destEl.nodeName.toLowerCase() === "tr") {
                    if(tmpIndex !== null) {
                        dt.deleteRow(tmpIndex);
                    }
                    else {
                        dt.deleteRow(this.srcIndex);
                    }
 
                    dt.addRow(this.srcData, destIndex);
                    this.tmpIndex = destIndex;
 
                    DDM.refreshCache();
                }
           }
        });
		 

		  //////////////////////////////////////////////////////////////////////////////
        // Create DDRows instances when DataTable is initialized
        //////////////////////////////////////////////////////////////////////////////
        dt.subscribe("initEvent", function() {
            var i, id,
                allRows = this.getTbodyEl().rows;
 
            for(i=0; i<allRows.length; i++) {
                id = allRows[i].id;
                // Clean up any existing Drag instances
                if (myDTDrags[id]) {
                     myDTDrags[id].unreg();
                     delete myDTDrags[id];
                }
                // Create a Drag instance for each row
                myDTDrags[id] = new  YUI.allevo.querystring.DDRows(id);
            }
        });
		 
		  //////////////////////////////////////////////////////////////////////////////
        // Create DDRows instances when new row is added
        //////////////////////////////////////////////////////////////////////////////
        dt.subscribe("rowAddEvent",function(e){
            var id = e.record.getId();
            myDTDrags[id] = new YUI.allevo.querystring.DDRows(id);
        })
			
		//on_new_data_querystring.subscribe(upadte_datatable);
		



		YUI.allevo.querystring.dt = dt;
		
		
	

	 });

		Y.log("datatable is loading finished"); 
		
}, '1.0.0', {requires: [
    'node', 'event-custom', "yui2-animation", "yui2-autocomplete", "yui2-button", "yui2-connection", "yui2-container", "yui2-dom", "yui2-editor", "yui2-element", "yui2-event", "yui2-menu", "yui2-datatable", "yui2-dom", "yui2-dragdrop", "event-custom", "yui2-calendar"
]});