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
      },
      strings: {
        value: {
            compass: {N:"Norden", NO:"Nordosten", O:"Osten", SO:"Südosten",  S:"Süden", SW:"Südwesten", W:"Westen", NW:"Nordwesten"},
            wind: {chill:"gefühlt", speed:"Wind:", direction:"aus Richtung"},
            atmosphere: {visibility:"Sichtweite:", humidity:"Luftfeuchtigkeit:", pressure:"Luftdruck:"},
            sun: {set:"Sonnenaufgang:", rise:"Sonnenuntergang:", unit:"Uhr"},
            aktualisierung : "Letzte Aktualisierung:"
        }
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
      
      /*
       * Convert Angle into Direction 
      */
      _windDirection : function(angle, direction) {

          // wind direction 
         if ((angle >= 0 && angle <= 22.5)||(angle > 337.5 && angle <= 360)) compass = direction.N;
         else if (angle > 22.5  && angle <= 67.5)  compass = direction.NO;
         else if (angle > 67.5  && angle <= 112.5) compass = direction.O;
         else if (angle > 112.5 && angle <= 157.5) compass = direction.SO;
         else if (angle > 157.5 && angle <= 202.5) compass = direction.S;
         else if (angle > 202.5 && angle <= 247.5) compass = direction.SW;
         else if (angle > 247.5 && angle <= 290.5) compass = direction.W;
         else if (dangle > 290.5 && angle <= 337.5) compass = direction.NW;
               
         return compass;
      },
      
      /**
       * transform a Date into Minutes of the Day.
       * @method _stringToValideDate
       * @param {string} timeOfTheDay 8:28 pm / 8:38 am / 8:38:10
       * modified.
       * @privat
      */
      _timeOfTheDayToMinutes : function(date) {

         var HM = Y.DataType.Date.format( date, {format:"%H:%M"});
             HM = HM.split(':', 2);
         var Minutes = parseInt(HM[0], 10) * 60 + parseInt(HM[1], 10);

         return Minutes;
      },
 
      
      /**
       * transform the time of the day into date string.
       * @method _stringToValideDate
       * @param {string} timeOfTheDay 8:28 pm / 8:38 am / 8:38:10
       * modified.
       * @privat
      */
      _timeToValideDate : function( timeOfTheDay ) {
      
        var YMD = Y.DataType.Date.format(new Date(), {format:"%F"});
        var today = Y.DataType.Date.parse(YMD+" "+timeOfTheDay);
        
        return today;
      },

      //renders the current weather from YQL
      _addWeather : function() {
         var boundingBox = this.get("boundingBox"),
             contentBox  = this.get("contentBox"),
             location    = this.get('location'),
             u           = this.get('u'),
             layout      = this.get('layout'),
             strings     = this.get("strings"),
             that        = this;

         Y.YQL( 'use "http://www.datatables.org/weather/weather.bylocation.xml" as we; select * from we where location="'+location+'" and unit="'+u+'"', function(r) { 
         
            var result    = r.query.results.weather.rss.channel;
               astronomy  = result.astronomy,
               sunrise    = that._timeToValideDate(astronomy.sunrise),
               sunset     = that._timeToValideDate(astronomy.sunset),
               lastupdate = result.item.condition.date,
               units      = result.units,
               wind       = result.wind,
               direction  = wind.direction,
               atmosphere = result.atmosphere,
               barometer  = (atmosphere.rising == 1) ? "steigend": "fallend",
               compass    = '';

               
            var sunrise_min = that._timeOfTheDayToMinutes(sunrise);
            var sunset_min = that._timeOfTheDayToMinutes(sunset);
            var now_min = that._timeOfTheDayToMinutes(new Date());

            var aktualiserung = lastupdate.split(' ', 6);
                aktualiserung = aktualiserung[4] +' '+  aktualiserung[5];
            
            // day or night
            if( sunrise_min <= now_min && sunset_min >= now_min ){
               var meridium = 'd', class_m = '';
            }else{
               var meridium = 'n', class_m = 'night';
            }
               
            var icon = 'assets/weather/'+result.item.condition.code + meridium  +'.png';
            var background = 'assets/weather/background/'+result.item.condition.code + meridium  +'-106755.jpg';
            
            compass = that._windDirection(direction, strings.compass);

           var html = '<div id="yw-forecast" class="'+ class_m +'" style=" background:url(\''+background+'\'); background-size: cover;" >';
               html += '<div id="yw-cond" class="'+ class_m +'" >'+result.location.city+'</div>';

            if(layout === "full"){
               html += '<dl>'; 
               html += '<dt>'+strings.wind.chill+'</dt><dd>'+wind.chill+'°'+units.temperature+'</dd>';
               html += '<dt>'+strings.atmosphere.pressure+'</dt><dd>'+atmosphere.pressure+' '+ units.pressure+' '+ barometer +'</dd>'; 
               html += '<dt>'+strings.atmosphere.humidity+'</dt><dd>'+atmosphere.humidity+' % </dd>'; 
               html += '<dt>'+strings.atmosphere.visibility+'</dt><dd>'+atmosphere.visibility+' '+ units.distance+'</dd>';
               html += '<dt>'+strings.wind.speed+'</dt><dd>'+wind.speed+' '+ units.speed+' '+strings.wind.direction+' '+compass+'</dd>'; 
               html += '<dt>'+strings.sun.rise+'</dt><dd>'+Y.DataType.Date.format( sunrise, {format:"%R"})+ ' '+strings.sun.unit+'</dd>'; 
               html += '<dt>'+strings.sun.set+'</dt><dd>'+Y.DataType.Date.format( sunset, {format:"%R"})+' '+strings.sun.unit+'</dd>';                 
               html += '</dl>'; 
            }               
              
               html += '<em>'+strings.aktualisierung+' '+aktualiserung+'</em>'; 
               
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
 
}, '1.0', {require: ["widget", "substitute", "yql", "datatype-date"]});