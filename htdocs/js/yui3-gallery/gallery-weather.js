YUI().add('gallery-localWeather', function(Y) {
 
   //base class (Widget) constructor
   function LocalWeather(config) {
      LocalWeather.superclass.constructor.apply(this, arguments);
   }
 
   LocalWeather.NAME = "localWeather";
 
   LocalWeather.ATTRS = {
      location : {
         value: "Winterthur"
      },
      u : {
         value: "c"
      },
      layout : {
         value: "full"
      }
     
   };    
  

   Y.extend(LocalWeather, Y.Widget, {
 
      initializer : function() {
      },
 
      destructor : function() {
      },
 
      //dom manipulatoin
      renderUI : function() {
         this._addWeather();
      },
 
      //handle events
      bindUI : function() {
      },
 
      //sync changes
      syncUI : function() {
      },
 
      _addWeather : function() {
         var boundingBox = this.get("boundingBox"),
             contentBox  = this.get("contentBox"),
             username    = this.get('username'),
             location    = this.get('location'),
             u           = this.get('u');
             layout      = this.get('layout');

         Y.YQL( 'use "http://www.datatables.org/weather/weather.bylocation.xml" as we; select * from we where location="'+location+'" and unit="'+u+'"', function(r) { 
         
           var result     = r.query.results.weather.rss.channel;
               astronomy  = result.astronomy,
               sunrise    = astronomy.sunrise,
               sunset     = astronomy.sunset,
               lastupdate = result.item.condition.date,
               units      = result.units,
               wind       = result.wind,
               direction  = wind.direction,
               atmosphere = result.atmosphere,
               barometer  = (atmosphere.rising == 1) ? "steigend": "fallend",
               compass    = '';
               
               
           var temp = lastupdate.split(' ', 6);
           var aktualiserung = temp[4] +' '+  temp[5];
               
               // day or night ?
               temp = sunrise.split(' am', 1);
               temp = temp[0].split(':', 2);
           var sunrise_min = parseInt(temp[0], 10) * 60 + parseInt(temp[1], 10);
              
               temp = sunset.split(' pm', 1);
               temp = temp[0].split(':', 2);
           var sunset_min = parseInt(temp[0], 10) * 60 + (12*60) + parseInt(temp[1], 10);
               
           var now = Y.DataType.Date.format(new Date(), {format:"%H:%M"})
               now = now.split(':', 2);
           var now_min = parseInt(now[0], 10) * 60 + parseInt(now[1], 10);
               
               //var localtime = aktualiserung.split(' ', 2);
               //var localtime1 = localtime[0].split(':', 2);
               //localtime_min = parseInt(localtime1[0]) * 60 + parseInt(localtime1[1]);
               //if(localtime[1] === "pm") localtime_min = localtime_min + (12*60);


               if( sunrise_min <= now_min && sunset_min >= now_min ){
                  var meridium = 'd', class_m = '';
               }else{
                  var meridium = 'n', class_m = 'night';
               }
               
               icon = 'assets/weather/'+result.item.condition.code + meridium  +'.png';
               background = 'assets/weather/background/'+result.item.condition.code + meridium  +'-106755.jpg';
               
               // wind direction 
               if ((direction >= 0 && direction <= 22.5)||(direction > 337.5 && direction <= 360)) compass = "Nord";
               else if (direction > 22.5  && direction <= 67.5)  compass = "Nordosten";
               else if (direction > 67.5  && direction <= 112.5) compass = "Osten";
               else if (direction > 112.5 && direction <= 157.5) compass = "Südosten";
               else if (direction > 157.5 && direction <= 202.5) compass = "Süden";
               else if (direction > 202.5 && direction <= 247.5) compass = "Südwesten";
               else if (direction > 247.5 && direction <= 290.5) compass = "Westen";
               else if (direction > 290.5 && direction <= 337.5) compass = "Nordwesten";

           var html = '<div id="yw-forecast" class="'+ class_m +'" style=" background:url(\''+background+'\'); background-size: cover;" >';
               html += '<div id="yw-cond" class="'+ class_m +'" >'+result.location.city+'</div>';

            if(layout === "full"){
               html += '<dl>'; 
               html += '<dt>gefühlt:</dt><dd>'+wind.chill+'°'+units.temperature+'</dd>';
               html += '<dt>Luftdruck:</dt><dd>'+atmosphere.pressure+' '+ units.pressure+' '+ barometer +'</dd>'; 
               html += '<dt>Feuchtigkeit:</dt><dd>'+atmosphere.humidity+' % </dd>'; 
               html += '<dt>Sichtweite:</dt><dd>'+atmosphere.visibility+' '+ units.distance+'</dd>';
               html += '<dt>Wind:</dt><dd>'+wind.speed+' '+ units.speed+' aus Richtung '+compass+'</dd>'; 
               html += '<dt>Sonnenaufgang:</dt><dd>'+sunrise+'</dd>'; 
               html += '<dt>Sonnenuntergang:</dt><dd>'+sunset+'</dd>';               
               html += '</dl>'; 
            }               
              
               html += '<em> Letzte Aktualisierung '+aktualiserung+'</em>'; 
               
               html += '<div class="forecast-temp">'+
                          '<div id="yw-temp">'+result.item.condition.temp+'°</div>'+
                           '<p>H:'+result.item.forecast[0].high+'° L: '+result.item.forecast[0].low+'°</p>'+
                          '</div>';
               html += '<div class="forecast-icon" style=" background:url(\''+icon+'\'); _background-image/* */: none; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+icon+'\', sizingMethod="crop"); "></div>';                                         
               html += '</div>';  

            contentBox.setContent(html);
         });
      }
      
   });
 
   Y.LocalWeather = LocalWeather;
 
}, '1.0', {require: ["widget", "substitute", "yql", "datatype-date-format"]});