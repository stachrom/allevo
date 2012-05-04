{literal}
<script type="text/javascript">
var map;
var marker;

// Simple form checking.
function check_form() {
  if(document.getElementById('location').value == '') {
      alert('location required.');
      return false;
  } 
  return true;
}


  


function initialize() {
  // Initialize the Google Map
  map = new google.maps.Map(document.getElementById('map_canvas'), {
    center: new google.maps.LatLng(47.08882558740764, 8.55560302734375),
    zoom: 8,
    mapTypeId: google.maps.MapTypeId.ROADMAP
	
  });
    
var style = [
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
      featureType: 'road.local',
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
  
  
  
  //Add a click listener to listen for clicks on the map
  google.maps.event.addListener(map, 'click', function(e) {
    // alert('You clicked lat,lng: ' + e.latLng.lat() + ',' + e.latLng.lng());
    // The following line sets the value of the hidden location input
    // This field will be submitted along with the other form inputs
    if(marker == null) { 
      marker = new google.maps.Marker({
        map: map
      });
    }
    marker.setPosition(e.latLng);
    document.getElementById('location').value = e.latLng.lat() + ',' + e.latLng.lng();
  });
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
<form method="post" action="request/fusiontable.php?id={$smarty.get.id}" onSubmit="return check_form();">
<div class="yui3-g">
 		<div class="yui3-u-2-5" > 
			<div class="content">
				<fieldset>
    			<legend><h2>Sport Resultat Melden</h2></legend>
            
            
            {if $smarty.get.success == 1} 

					<div class="sucess"> <p>Deine Daten wurden erfolgreich gespeichert!</p> </div>

       		{elseif $smarty.get.failure == 1 } 
            	<div class="failure">
               <h2>Oh my God! Something is Broken</h2> 
               <p>Grund: {$smarty.get.message} </p>
               <p>Hilfe: Benachrichten sie den Sysadmin --> <a href="mailto:info@finishers.ch?Subject=finishers%20Fusiontable%20Error:%20{$smarty.get.message}"> info@finishers.ch</a>  </p> 
               </div>

       	{/if} 
            
            
				<div id="athlet"> 
            <p>
               <label for="vorname-form" >Vorname: <span>*</span></label> 
               <input type="text" name="vorname" id="vorname-form" required autofocus placeholder="Tina" value="{$smarty.get.vorname}"> 
            <p>
            </p>
               <label for="name" >Name: <span>*</span></label> 
               <input type="text" name="name" id="name" required placeholder="Bucher" value="{$smarty.get.name}" >  
            </p>
            
            <p> 
               <label for="mail" > Email: <span>*</span> </label>
               <input type="email" name="email" id="mail" required placeholder="info@finishers.ch" value="{$smarty.get.email}">
            <p>
           </p>
               <label for="alter">Alter: </label> 
               <input  id="alter" value="{$smarty.get.alter}" name="alter" placeholder="30"> 
           </p> 
			</div>

         <div id="veranstaltung">
          <p>   
            <label for="wettkampftitel" >Wettkampftitel: <span>*</span></label> 
            <input type="text" name="wettkampftitel" id="wettkampftitel" required placeholder="70.3 Rapperswil Jona" > [Züri Marathon 2011]
         </p>
          <p>
            <label for="sportart"> Anlass: </label>
            <input name="sportart" id="sportart" placeholder="Halbmarathon" > [Marathon]       
         </p>

         <p>   
           <label for="wettkampfdatum" > Wettkampfdatum: <span>*</span> </label>
           <input type="text" name="wettkampfdatum" id="wettkampfdatum" required placeholder="TT-MM-JJJJ" > [16-04-2011]
        </p>  
        <p> 
            <label for="wettkaempfe">Art: </label>
            <select name="wettkaempfe" id="wettkaempfe" >
               <option value="Wettkampf">Wettkampf</option>
               <option value="Meisterschaft">Meisterschaft</option>
               <option value="Clubmeisterschaft">Clubmeisterschaft</option>
            </select>
      </p> 
      
        <p>  
               <label for="url" >URL: </label>
               <input type="url"  name="url" id="url" placeholder="http://services.datasport.com/2011/lauf/winterthur/" > <br> [Rangliste oder Wettkampf Link: http://www... ]
                 
            </p>
            <hr />
            
       <p>  
               <label for="preisgeld" >Preisgeld berechtigt: </label>
               <input type="checkbox"  name="preisgeld" id="preisgeld" value"yes"> Ich habe das <a href="?id=626" target="_blank" >Reglement</a> gelesen und Anspruch auf Preisgeld.  
      </p>
            
            
         </div>
 
         <div id="resultat"> 
           
         <p id="time">
            Zeit:
            <input id="h" value="0" name="h">
            <label for="h" >h</label>
            
            <input id="min" value="30" name="min">
            <label for="min" >min </label>
            
            <input id="sec" value="45" name="sec">
            <label for="sec" >sek </label> [Wettkampfzeit] 
           
         </p>
            
         <p id="distanz_dailer">   
               <label for="distanz" >Distanz: </label>
               <input id="distanz" value="" name="distanz" > km
         </p>
         
         
         <hr> 
         
         <div id="rang"> 
               <p> 
                <label for="rangkategorie" >Rang Kategorie: </label>
               <input type="number" name="rangkategorie" id="rangkategorie" placeholder="1" required min="1"> 
               <label for="kategorie" style="display:inline"> Kategorie: </label>
                <input type="text" name="kategorie" id="kategorie" placeholder="M35" >
               </p>
               
               <p> 
               <label for="rangoverall" >Rang Overall: </label>
               <input type="number" name="rangoverall" id="rangoverall" placeholder="1" required min="1">    
               </p>
               
               <p>
               <label for="teilnehmerkategorie" >Teilnehmer Kategorie: </label>
               <input type="number" name="teilnehmerkategorie" id="teilnehmerkategorie" placeholder="1" required onFormInput="min=rangkategorie.value" >
               </p> 
            
               <p>
                  <label for="teilnehmertotal" >Teilnehmer Overall: </label>
                  <input type="number" name="teilnehmertotal" id="teilnehmertotal" placeholder="1" required onFormInput="min=rangoverall.value" >      
               </p>
            </div> 
			</div> 	
  </fieldset>



{literal} 
<script>

YUI({
    gallery: 'gallery-2011.06.01-20-18',
    modules:{
		'calendar-skin':{
			fullpath:'../../js/yui3-gallery/gallery-calendar/assets/skin.css',
			type:'css'
		},
		'gallery-calendar':{
			fullpath:'../../js/yui3-gallery/gallery-calendar/js/calendar.js',
			requires:['calendar-skin','node']
		}
	}

}).use('gallery-calendar', 'datatype-date',  "autocomplete", "autocomplete-filters", "autocomplete-highlighters", function(Y) {


 var sportarten = [
        'Marathon',
		'Halbmarathon',
        'Triathlon', 
		'Volkslauf', 
        'Ironman',
        '70.3',
        'Biken',
        'Schwimmen',
        'Walking',
		'Duathlon',
		'Gigathlon'
  ];
  

 Y.one('#sportart').plug(Y.Plugin.AutoComplete, {
    resultFilters    : 'phraseMatch',
    resultHighlighter: 'phraseMatch',
    source           : sportarten
  });

   var c = new Y.Calendar('wettkampfdatum',{
		popup:true,
		action:['click']
	});
	
	c.on('show', function() {
		c.render({selected:new Date(Y.one('#wettkampfdatum').get('value'))});
	});

	c.on('select',function(d) {
		Y.one('#wettkampfdatum').set('value',Y.DataType.Date.format(d, {format:"%d-%m-%Y"})); 
	});
	

})

</script>
         
{/literal}         
      
</div>
 		</div>
        
 		<div class="yui3-u-3-5"> 
			<div class="content" >
            <fieldset>
            <legend><p>Platziere deinen Wettkampf: <b>Klick auf die Karte</b> </p></legend>
               <!-- Create the map here -->
              <div id="map_canvas"></div>
              <!-- Hidden input field for location selected on map -->
              <input type="hidden" name="location" id="location" />
            <div id="send">
              <input type="submit" value="Daten Senden" onsub />
            </div>
            </fieldset>
			</div>
 		</div>
</div>
</form>