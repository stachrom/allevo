{literal}
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">

 google.load('visualization', '1.1', {packages: ['controls', 'table', 'corechart']});

var map,
    layer,
    tableid = 964141;

function initialize() {
  map = new google.maps.Map(document.getElementById('map_canvas_dashboard'), {
    center: new google.maps.LatLng(46.762443052080066, 8.39630126953125),
    zoom: 7,
    mapTypeId: google.maps.MapTypeId.ROADMAP

  });

  var style = [
    {
      featureType: 'all',
      elementType: 'all',
      stylers: [
        { saturation: 6 }
      ]
    } ,
    {
      featureType: 'road.highway',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    } ,
    {
      featureType: 'road.arterial',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    } ,
    {
      featureType: 'administrative.locality',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    } ,
    {
      featureType: 'administrative.neighborhood',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    } ,
    {
      featureType: 'administrative.land_parcel',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    } ,
    {
      featureType: 'transit',
      elementType: 'all',
      stylers: [
        { visibility: 'off' }
      ]
    }
  ];

  var styledMapType = new google.maps.StyledMapType(style, {
    map: map,
    name: 'Styled Map'
  });

  map.mapTypes.set('map-style', styledMapType);
  map.setMapTypeId('map-style');

  layer = new google.maps.FusionTablesLayer(tableid);
  layer.setQuery("SELECT 'Location' FROM " + tableid);
  layer.setMap(map);
}

function changeMap() {
 
  var searchString = document.getElementById('searchString').value.replace("'", "\\'");
  
 var stopyear =  document.getElementById('slider2Value').innerHTML;
 var startyear = document.getElementById('slider1Value').innerHTML;
 
 var startdate = "31/12/"+startyear;
 var stopdate = "31/12/"+stopyear;
 
  if(searchString == "Email") {
    layer.setQuery("SELECT 'Location' FROM " + tableid + " WHERE 'Wettkampfdatum' > '"+startdate+"' AND  'Wettkampfdatum' < '"+stopdate+"' ");
    return;
  }
  layer.setQuery("SELECT 'Location' FROM " + tableid + " WHERE 'Email' = '" + searchString + "' AND 'Wettkampfdatum' > '"+startdate+"' AND  'Wettkampfdatum' < '"+stopdate+"' ");
 
 
 
 
}

 function updateHeatmap() {
    var heatmap = document.getElementById('heatmap');
    layer.set('heatmap', heatmap.checked);
  }


function loadScript() {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=initialize";
  document.body.appendChild(script);
}

window.onload = loadScript;



</script>
{/literal} 
<div class="yui3-g">
 	<div class="yui3-u-1" > 
		<div class="content">
			<h2>Saison <span id="slider1Value">{$sliderSaisonValueStart}</span> - <span id="slider2Value">{$sliderSaisonValueEnd}</span> </h2>
			<div id="saisonSlider"></div>
        </div>
    </div>
</div>
           

<div class="yui3-g">
 	<div class="yui3-u-1-2" > 
		<div class="content">
			<div id="wrapper">
				<div id="googleMap" class="border" >
					<input id="heatmap" type="checkbox" onClick="updateHeatmap()" />  
					<label for="heatmap"> Heatmap aktivieren</label> 
					<div id="map_canvas_dashboard"></div>
				</div>
			</div>
		</div>
 	</div>   
 	<div class="yui3-u-1-2"> 
		<div class="content" >
			<h3>Finishers Klub Charts</h3>
			<div class="border" id="dashboard_piecharts" >
				<div id="piechart"></div>
					<div id="preisgeld_wrapper">
					<label for="wettkaempfe">Preis Gelder: </label>
					<select name="wettkaempfe" id="wettkaempfe" onChange="changeData(this, this.value);" >
					   <option value="Meisterschaft">Meisterschaft</option>
					   <option value="Wettkampf">Wettkampf</option>
					   <option value="Clubmeisterschaft">Clubmeisterschaft</option>
					</select>
					</div>
				<div  id="preisgeld"></div>
			</div>
		</div>
 	</div>
</div>


<div class="yui3-g">
 	<div class="yui3-u-1" > 
		<div class="content">
			<h3>Athleten Filter</h3>
			<select id="searchString" onChange="changeMap(this.value); changeData(this.value);  ">
			<option value="Email"> Alle Athleten </option> 
				{foreach from=$dashboard_athleten key=k item=v } 
						<option value="{$v.email}">{$v.name} {$v.vorname}</option> 
				{/foreach}
			</select>
			<div id="dashboard" >
				<table id="dashboardControls" >
					<tr style='vertical-align: top'>
					  <td><div id="control2"></div></td>
					  <td><div id="control3"></div></td>
					  <td><div id="control4"></div></td>
					  <td><div id="control1"></div></td>
					</tr>
				</table>
				<div id="charttable"></div>
			</div>
			<div id="visualization"></div>
		</div>
	</div>
</div>

{literal}   
<script  id="script"> 

function drawpie(response) {

   if (response.isError()) { 
     alert('Error in query: ' + response.getMessage() + ' ' +  response.getDetailedMessage()); 
     return; 
   }

	var data = response.getDataTable();

	//alert(data.toJSON());

  new google.visualization.PieChart(document.getElementById('piechart')).
      draw(data,
			{
			'width': 400,
			'height': 200,
			'legend': 'true',
			'is3D': true,
			'title': 'Wettkämpfe im Verein',
			'chartArea': {'left': 15, 'top': 15, 'right': 0, 'bottom': 0},
			'pieSliceText': 'value'
			}
      );
}

      google.setOnLoadCallback(changeData("drawpie"));


function drawtable(response) {

   if (response.isError()) { 
     alert('Error in query: ' + response.getMessage() + ' ' +  response.getDetailedMessage()); 
     return; 
   }

	var data = response.getDataTable();


    var preisgeldtabelle = new google.visualization.Table(document.getElementById('preisgeld'));

	if (data){

  	for (i=0; i<data.getNumberOfColumns(); i++) {

	//alert(data.getColumnLabel(i));

		if(data.getColumnLabel(i) == '8-18 Junioren' ){
			data.setColumnLabel(i, 'Preisgeld');
		}else if( data.getColumnLabel(i) == '19-30 Hauptklasse' ){
			//data.setColumnLabel(i, 'Preisgeld');
		}else if( data.getColumnLabel(i) == '31-39 Altersklasse' ){
			//data.setColumnLabel(i, 'Preisgeld');
		}else if( data.getColumnLabel(i) == '40-x Altersklasse' ){
			//data.setColumnLabel(i, 'Preisgeld');
		}else if( data.getColumnLabel(i) == 'Wettkampftitel' ){
			data.setColumnLabel(i, 'Wettkampf');
		}else if( data.getColumnLabel(i) == 'Wettkampfdatum' ){
			data.setColumnLabel(i, 'Datum');
		}else if( data.getColumnLabel(i) == 'Name' ){

			data.setColumnLabel(i, 'Athlet');
		
			var formattername = new google.visualization.PatternFormat('{0} {1}');
  			formattername.format(data, [0,1], 0);
			
			
		}else if( data.getColumnLabel(i) == 'Alter' ){
		
		
			for (j=0; j<data.getNumberOfRows(); j++) {
				var alter = data.getValue(j, 2);
       	 		//data.setFormattedValue(j, 2, wettkampfzeit);
					if(alter <= 18 ){
						//alert(alter + 'kleiner als 18');
						data.setFormattedValue(j, 3, data.getValue(j, 3));
					}else if( 19 <  alter  & alter <=  30  ){
					  //alert(alter + 'zwischen 19 und 30');
					  data.setFormattedValue(j, 3, data.getValue(j, 4));
					}else if( 31 <  alter  & alter <=  39  ){
					  //alert(alter + 'zwischen 31 und 39'); 
					  data.setFormattedValue(j, 3, data.getValue(j, 5)); 
					}else if( 40 <=  alter ){
					  //alert(alter + ' ist 40 plus');
					  data.setFormattedValue(j, 3, data.getValue(j, 6)); 
					//alert(data.getValue(j, 7));  
					}

			}	

			dataview = new google.visualization.DataView(data);
 			dataview.setColumns([0,3,9,10,8]);

		}else if( data.getColumnLabel(i) == 'Rangkatego' ){

			data.setColumnLabel(i, 'Rang');
		}
		
	} // ende for

	} // ende if
	

	preisgeldtabelle.draw(
		dataview,
		{showRowNumber: true}
    );
	//alert(data.toJSON());

}


function changeData(athlet, b, c) {

   var whereClause = "";
	
	var stopyear =  document.getElementById('slider2Value').innerHTML;
	var startyear = document.getElementById('slider1Value').innerHTML;
	 
	var startdate = "31/12/"+startyear;
	var stopdate = "31/12/"+stopyear;

  if(athlet == "Email") {
  
	whereClause =  "WHERE 'Wettkampfdatum' > '"+startdate+"' AND  'Wettkampfdatum' < '"+stopdate+"'";

 	var queryText = encodeURIComponent("SELECT   Sportart, Wettkampfdatum, Rangkategorie,  Kategorie, Time, 'min/km', Wettkampftitel, URL, Vorname, Name FROM 964141 "  + whereClause  );
  	var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
  
  	query.send(handleQueryResponse); 

	var queryText = encodeURIComponent("SELECT   Sportart, count() FROM 964141 "+ whereClause +" group by Sportart " );
  	var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
  	query.send(drawpie);
	
  }else if (athlet != "drawpie" && athlet != "Email"){
  
	whereClause =  "WHERE 'Email' = '" + athlet + "'AND 'Wettkampfdatum' > '"+startdate+"' AND  'Wettkampfdatum' < '"+stopdate+"'";

	var queryText = encodeURIComponent("SELECT   Sportart, Wettkampfdatum, Rangkategorie,  Kategorie, Time, 'min/km', Wettkampftitel, URL, Vorname, Name FROM 964141 "  + whereClause  );
	var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
  
	query.send(handleQueryResponse); 

  }

  if(b){
  
      var tableId = ""; 
  
	  if(b == "Wettkampf" ){
		tableId = 2958812; 
	  }else if(b == "Clubmeisterschaft" ){
		tableId = 2958935; 
	  }else if(b == "Meisterschaft" ){
		tableId = 2958906;
	  }
   	 whereClause =  " WHERE 'Rang' <=10 AND 'Alter' >= 1 AND 'Wettkaempfe'='" + b + "' AND 'Wettkampfdatum' > '"+startdate+"' AND  'Wettkampfdatum' < '"+stopdate+"'";

	var queryText = encodeURIComponent("SELECT  'Name', 'Vorname', 'Alter', '8-18 Junioren', '19-30 Hauptklasse', '31-39 Altersklasse', '40-x Altersklasse', 'Email', 'Wettkampfdatum', 'Rang', 'Wettkampftitel'   FROM "+tableId+whereClause);
  	var query = new google.visualization.Query('http://www.google.com/fusiontables/gvizdata?tq='  + queryText);
	
	query.send(drawtable);

  }

}


function handleQueryResponse(response) { 


   if (response.isError()) { 
     alert('Error in query: ' + response.getMessage() + ' ' +  response.getDetailedMessage()); 
     return; 
   }

 
   var slider = new google.visualization.ControlWrapper({ 
     'controlType': 'NumberRangeFilter', 
     'containerId': 'control2', 
     'options': { 
       'filterColumnLabel': 'Rang', 
       'ui': {
		'labelStacking': 'vertical',
		'label': 'Rang: '
	} 
     } 
   }); 
	

	
	var categoryPicker = new google.visualization.ControlWrapper({
    'controlType': 'CategoryFilter',
    'containerId': 'control3',
    'options': {
      'filterColumnLabel': 'Sportart',
      'ui': {
      'labelStacking': 'vertical',
        'allowTyping': false,
        'allowMultiple': false
      }
    }
  });
  
  
  
  	var jahresPicker = new google.visualization.ControlWrapper({
    'controlType': 'CategoryFilter',
    'containerId': 'control4',
    'options': {
      'filterColumnLabel': 'Year',
      'ui': {
      'labelStacking': 'vertical',
        'allowTyping': false,
        'allowMultiple': false
      }
    }
  });
  
  

  

var stringFilter = new google.visualization.ControlWrapper({
    'controlType': 'StringFilter',
    'containerId': 'control1',
    'options': {
      'filterColumnLabel': 'Name'
    }
  });
  

	
   // Define a table 
   var table = new google.visualization.ChartWrapper({ 
     'chartType': 'Table', 
     'containerId': 'charttable', 
     'options': { 
		'width': document.getElementById("dashboard").clientWidth +'px',
		'allowHtml': true,
		'showRowNumber': true
     },
 	'view': {'columns': [0,1,2,3,4,5,7]}
   }); 
	

  var data = response.getDataTable();
  
  
  for (i=0; i<data.getNumberOfColumns(); i++) {
  
  		console.log(data.getColumnLabel(i));
   
		if(data.getColumnLabel(i) == 'Wettkampfdatum' ){
			data.setColumnLabel(i, 'Datum');
			var ColumnNumber = data.addColumn('number', "Year", "dynamicRow");
			
			for (j=0; j<data.getNumberOfRows(); j++) {

				var year =  data.getValue(j, i);
				//var year =  new Date();
				data.setCell(j, ColumnNumber, year.getFullYear() )
				//data.setFormattedValue(j, 10, year);
			}

		}else if( data.getColumnLabel(i) == 'min/km' ){
		
			data.setColumnLabel(i, 'Ø '); 
		
			for (j=0; j<data.getNumberOfRows(); j++) {

				var min =  data.getValue(j, i);

				if (min != "Infinity"){
					console.log(minProKm(min));
					data.setFormattedValue(j, i,  minProKm(min));
				}else{
					data.setFormattedValue(j, i,  '');
				}
			}

		}else if( data.getColumnLabel(i) == 'Time' ){

			data.setColumnLabel(i, 'Zeit');

			for (j=0; j<data.getNumberOfRows(); j++) {
				var wettkampfzeit = secToStr(data.getValue(j, 4));
       	 		data.setFormattedValue(j, 4, wettkampfzeit);
			}
 
		}else if( data.getColumnLabel(i) == 'URL' ){
			data.setColumnLabel(i, 'Wettkampf');
		}else if( data.getColumnLabel(i) == 'count()' ){
			data.setColumnLabel(i, 'Anzahhl');
		}else if( data.getColumnLabel(i) == 'Vorname' ){

			data.setColumnLabel(i, 'Athlet');
		
			v = new google.visualization.DataView(data);
  			v.setColumns([0,1,2,3,4,5,7,8]);
  			//alert(v.toJSON()); //That doesn't look right!
			table.setView(v.toJSON());

  			var formattername = new google.visualization.PatternFormat('{0} {1}');
  			formattername.format(data, [8,9], 8);

		}else if( data.getColumnLabel(i) == 'Rangkategorie' ){
			data.setColumnLabel(i, 'Rang');
		}
		
		//alert(data.getColumnLabel(i));

	}


  function secToStr(sec){
		var sec = parseInt(sec);
		var minuten = parseInt(sec/60);
	
		sec = sec%60;
		sec = (sec < 10) ? "0"+ sec : sec ;
	
		var stunden = parseInt(minuten/60);
		stunden = (stunden < 10) ? "0" + stunden : stunden ;
		
		minuten = minuten%60;
		minuten = (minuten < 10) ? "0" + minuten : minuten ;
		
		return stunden+':'+minuten+':'+sec;
}
  

  function minProKm(min){

		var sec = min % 1;
		    sec = parseInt(sec*60);
			 sec = (sec < 10) ? "0"+ sec : sec ;
			 
			 
		
		return  parseInt(min)+':'+sec+' min/km ';
}

  // iteration des Datenblocks
  var formatterurl = new google.visualization.PatternFormat('<a href="{0}" target="_blank">{1}</a>');
  formatterurl.format(data, [7, 6]); 

  var formatter = new google.visualization.DateFormat({pattern: "d. MM. yyyy"});
  formatter.format(data, 1);	

  //var formatterYear = new google.visualization.DateFormat({pattern: "yyyy"});
  //formatterYear .format(data, 10);	


  new  google.visualization.Dashboard(document.getElementById('dashboard')). 

	 //bind(stringFilter, categoryPicker).
	 bind(categoryPicker, slider).
	 bind(slider, jahresPicker).
	 bind(jahresPicker, table).
	 draw(data); 

}       
      

changeData("Email");

 {/literal}  

YUI({
		gallery: 'gallery-2012.03.23-18-00',
		
	}).use('gallery-yui-dualslider', function(Y) {

		var eleYuiSlider = new Y.DualSlider({
			id: 'DualSlider',
			axis: 'x',
			min: {$sliderSaisonStart},
			max: {$sliderSaisonEnd},
			length: Y.one('#saisonSlider').get('clientWidth')-10					
		});
			
		eleYuiSlider.after('slideEnd', function(e) {
			var eleYuiSlider = e.currentTarget;
			eleYuiSlider.syncUI();  			
		});
			
		eleYuiSlider.after(['valueChange', 'value2Change'], function(e) {
			if (e.attrName == 'value2'){
				document.getElementById('slider2Value').innerHTML = e.newVal;						
			}else{
				document.getElementById('slider1Value').innerHTML = e.newVal;
			}
		});
		
		eleYuiSlider.after(['slideEnd'], function(e) {
				
			console.log(document.getElementById('slider2Value').innerHTML);
			console.log(Y.one('#slider2Value').get('innerHTML'));
			console.log(Y.one('#slider1Value').get('innerHTML'));
					
			changeMap();
					
			var wettkampf = document.getElementById('wettkaempfe').value.replace("'", "\\'"); 
			var athlet = document.getElementById('searchString').value.replace("'", "\\'"); 

			changeData(athlet, wettkampf);

		});
		
		eleYuiSlider.render('#saisonSlider');
		
		eleYuiSlider.setValue({$sliderSaisonValueStart});
		eleYuiSlider.setValue2({$sliderSaisonValueEnd});
			
	});		
</script>