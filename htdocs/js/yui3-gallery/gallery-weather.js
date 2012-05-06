YUI().add('gallery-localWeather', function(Y) {
 
   //base class (Widget) constructor
   function LocalWeather(config) {
      LocalWeather.superclass.constructor.apply(this, arguments);
   }
 
   LocalWeather.NAME = "localWeather";
   
   
   LocalWeather.D_LIST_TEMPLATE ='<dl>'+ 
                                    '<dt>{s_chill}</dt><dd>{chill}°{u_temperature}</dd>'+
                                    '<dt>{s_pressure}</dt><dd>{pressure} {u_pressure} {barometer}</dd>'+ 
                                    '<dt>{s_humidity}</dt><dd>{humidity} % </dd>'+
                                    '<dt>{s_visibility}</dt><dd>{visibility} {u_distance}</dd>'+
                                    '<dt>{s_speed}</dt><dd>{speed} {u_speed} {s_direction} {compass}</dd>'+ 
                                    '<dt>{s_sunsetrise}</dt><dd>{sunrise} {s_timeunit}</dd>'+ 
                                    '<dt>{s_sunset}</dt><dd>{sunset} {s_timeunit}</dd>'+                 
                                 '</dl>';

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
            atmosphere: {visibility:"Sichtweite:", humidity:"Luftfeuchtigkeit:", pressure:"Luftdruck:", rise:"steigend", drop:"fallend", drop_rapidly:"rapide fallend", steady:"stabil" },
            sun: {set:"Sonnenuntergang:", rise:"Sonnenaufgang:", unit:"Uhr"},
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
         else if (angle > 290.5 && angle <= 337.5) compass = direction.NW;
               
         return compass;
      },
      /*
       * Pressure is heading up down or not even at all. 
      */
      _pressureMove : function(code, string) {
      
         var pressureDirection = "";
         code = parseInt(code, 10);

         // pressure is heading...
         if (code === 0) pressureDirection = string.steady;
         else if (code === 1) pressureDirection = string.drop;
         else if (code >= 2) pressureDirection = string.drop_rapidly;
         else pressureDirection = string.rise;

         return pressureDirection;
      },
      /**
       * takes a date string and get out the Minutes of the Day.
       * @method _timeOfTheDayToMinutes
       * @param {date} Valide JS Date string --> new Date();
       * 
       * @private
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
       * 
       * @private
      */
      _timeToValideDate : function( timeOfTheDay ) {
      
        var MDY = Y.DataType.Date.format(new Date(), {format:"%m/%d/%Y"});
        var today = Y.DataType.Date.parse(MDY+" "+timeOfTheDay);
    
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
         
         
         
         
         if (r.query && r.query.results) {

            var result     = r.query.results.weather.rss.channel,
                astronomy  = result.astronomy,
                units      = result.units,
                wind       = result.wind,
                atmosphere = result.atmosphere,
                item       = result.item;
    
            
            if (result.description === "Yahoo! Weather Error"){

               var city       = result.item.title,
                   compass    = "",
                   temperatur = "",
                   high       = "",
                   low        = "";
                   
            }else{
            var sunrise     = that._timeToValideDate(astronomy.sunrise),
                sunset      = that._timeToValideDate(astronomy.sunset),
                sunrise_min = that._timeOfTheDayToMinutes(sunrise),
                sunset_min  = that._timeOfTheDayToMinutes(sunset),
                now_min     = that._timeOfTheDayToMinutes(new Date()),
                direction   = wind.direction,
                city        = result.location.city,
                barometer   = that._pressureMove(atmosphere.rising, strings.atmosphere),
                lastupdate  = item.condition.date;
                temperatur  = item.condition.temp,
                high        = item.forecast[0].high,
                low         = item.forecast[0].low,
                compass     = '';

            var a = lastupdate.split(' ', 6);
            var aktualiserung = Y.DataType.Date.parse(a[2]+' '+a[1]+', '+a[3]+' '+a[4] +' '+a[5]);
                aktualiserung = Y.DataType.Date.format(aktualiserung, {format:"%H:%M"});

            // day or night
            if( sunrise_min <= now_min && sunset_min >= now_min ){
               var meridium = 'd', class_m = '';
            }else{
               var meridium = 'n', class_m = 'night';
            }
               
            var icon = 'assets/weather/'+result.item.condition.code + meridium  +'.png';
            var background = 'assets/weather/background/'+result.item.condition.code + meridium  +'-106755.jpg';
            
            compass = that._windDirection(direction, strings.compass);
            
           }    
            // HTML Template's
           var html = '<div id="yw-forecast" class="'+ class_m +'" style=" background:url(\''+background+'\'); background-size: cover;" >';
               html += '<div id="yw-cond" class="'+ class_m +'" >'+city+'</div>';

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
                          '<div id="yw-temp">'+temperatur+'°</div>'+
                           '<p>H:'+high+'° L: '+low+'°</p>'+
                          '</div>';
               html += '<div class="forecast-icon" style=" background:url(\''+icon+'\'); _background-image/* */: none; filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''+icon+'\', sizingMethod="crop"); "></div>';                                         
               html += '</div>'; 

         }else{
            var html = '<div id="error" > No Results received from the Weatherchannel </div>';
            Y.log(r);
         }
            contentBox.setContent(html);
         });
      }
      
      
   });
 
   Y.LocalWeather = LocalWeather;
 
}, '1.0', {require: ["widget", "substitute", "yql", "datatype-date-format", "datatype-date-parse" ]});