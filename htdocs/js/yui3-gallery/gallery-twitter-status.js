YUI.add('gallery-twitter-status', function(Y) {

   var query = "SELECT * FROM twitter.user.timeline(5) WHERE screen_name='stachrom'";


   function format_date (data) {
      data.relativeTime = Y.toRelativeTime(
         new Date(data.created_at.replace(/\+\d+/,''))
      );
      return data;
   };

    // Define the response handler that is executed when YQL responds with data
    var responseHandler = function(response) {
      var count, 
         html = [], 
         tweets;
         
      if(response.query.results){
         tweets = response.query.results.statuses.status;
         html.push("<div class='divider' ><hr><h2>Twitter</h2></div>");
      }

      // Loop through each tweet
      Y.Object.each(tweets, function (value, index){
      
         var tweet = format_date(value);

         tweet.text = tweet.text.replace(/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/gi, '<a href="$1" target="_blank">$1<\/a>');
         tweet.text = tweet.text.replace(/@([a-zA-Z0-9_]+)/gi, '<a href="http://twitter.com/$1" target="_blank" class="username">@$1<\/a>');
         tweet.text = tweet.text.replace(/#([a-zA-Z0-9_]+)/gi, '<a href="http://search.twitter.com/search?q=%23$1" target="_blank" class="hashtag">#$1<\/a>');

         // Create the HTML for each tweet
         html.push("<div class='tweet'>");
         html.push(" <div>");
         html.push(" <a class='tweet-image' href='http://twitter.com/"+ tweet.user.screen_name +"'><img src='" + tweet.user.profile_image_url + "' height='50' width='50'></a>");
         html.push(" </div>");
         html.push(" <div class='tweet-body'>");
         html.push("" +  tweet.text + "");
         html.push(" </div>");
         html.push(" <div class='relativeTime' ><a href='http://twitter.com/"+ tweet.user.screen_name +"/status/"+ tweet.id +"'>"+ tweet.relativeTime +"</div>");
         html.push(" <div style='clear:both'></div>");
         html.push("</div>");
      });

        html = html.join('');

        // Insert it into the #tweets node
        Y.one("#twitter-feed").set("innerHTML", html);
   }

    // Execute the query
    new Y.YQL(query, responseHandler);

}, '@VERSION@' ,{requires:['node', 'yql', 'gallery-torelativetime']});