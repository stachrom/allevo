<div class="yui3-g" >
<section class="yui3-u-1" >

         <header  id="scrollview-news-header">
            <h1>News</h1>
            <nav>
               <a class="prev" href="#prev" title="Previous Vorstandsmitglied" id="scrollview-news-prev" >‹‹</a>
               <a class="next" href="#next" title="Next Vorstandsmitglied" id="scrollview-news-next" >››</a>
         	</nav>
         </header>
         
                  
     <div class="yui3-u-1"  id="scrollview-news-container">
     <div id="scrollview-news-content" class="yui3-scrollview-loading">
     <div class="yui3-g" id="content-news">
     


 {foreach $news as $n}
 
 
      <article class="yui3-u-1-5">
      
      <div class="content">
               <header>
                  <h1><a href="?id={$n.nested_set_id}" >{$n.title}</a></h1>
                  <time datetime={$n.timestamp|date_format:"%Y-%m-%d"} pubdate>{$n.timestamp|date_format:"%d.%m.%Y"}</time>
               </header>
               
               <p>
               {assign var=contnet_news value=$n.content|strip_tags}{$contnet_news|truncate:200:"..."}  
               </p>

               <footer>
               <dl>
                  <dt>Tags: </dt>
                  <dd><a href="#news" class="tag">news</a></dd>

                  
                  <dt>Author: </dt>
                  <dd><a href="#" class="author">Sabine Simmen</a></dd>
               </dl>
            </footer>
       
      </div>
      </article>
         
{/foreach}

{literal}

<script id="news-template" type="text/x-handlebars-template">
	{{#news}}
		<article class="yui3-u-1-5">
			<div class="content">
				<header>
					<h1><a href="?id={{nested_set_id}}" >{{title}}</a></h1>
					<time datetime="{{{datetime date}}}" pubdate>{{{timestamp date}}}</time>
				</header> 
				<p>
				{{{content}}} 
				</p>
				<footer>
					<dl>
					{{#if tags}}
					  <dt>Tags: </dt>
						{{#each tags}}
							<dd><a href="#{{.}}" class="tag">{{.}}</a></dd>
						{{/each}}
					{{/if}}
					{{#if author}}					
					  <dt>Author: </dt>
					  <dd><a href="author#" class="author">{{author}}</a></dd>
					{{/if}}
				   </dl>
				</footer>
			</div>
		</article>
	{{/news}} 
</script>

{/literal}


   </div>
   </div>
   </div>
   
</section>
</div>

{literal}
<script> 

YUI({
	gallery: 'gallery-2012.01.25-21-14',
	lang:'de',
	modules : {

      'gallery-twitter-status':{
			fullpath : 'http://finishers.stachura.ch/js/yui3-gallery/gallery-twitter-status.js',
			requires : ['node', 'yql', 'gallery-torelativetime']
		}

	}
}).use('scrollview', 'json-parse', 'scrollview-paginator', 'io-base', 'handlebars', 'node-base', 'datatype-date', 'gallery-twitter-status', function (Y) {



	var news_source   = Y.one('#news-template').get('text'),
        template = Y.Handlebars.compile(news_source),
        Data_mustage;

	Y.Handlebars.registerHelper('datetime', function (datum) {
		date = Y.DataType.Date.parse(datum);
		return Y.DataType.Date.format(date, {format:"%F"});
	});

	Y.Handlebars.registerHelper('timestamp', function (datum) {
	    date = Y.DataType.Date.parse(datum);
		return Y.DataType.Date.format(date, {format:"%d.%m.%Y"});
	});	
	



	 var scrollView = new Y.ScrollView({
		 id: 'scrollview',
		 srcNode: '#scrollview-news-content',
		 width: Y.one('#scrollview-news-container').getComputedStyle("width"),
		 flick: {
			  minDistance:10,
			  minVelocity:0.3,
			  axis: "x"
		 }
	 });
	 
	 
	 
	 
	 
	 
	 
	
	 scrollView.plug(Y.Plugin.ScrollViewPaginator, {
        selector: 'article'
	 });
	

	 scrollView.render();
	 var content = scrollView.get("contentBox");
	 
	
	 // Prevent default image drag behavior
    scrollView.get("contentBox").delegate("mousedown", function(e) {

        e.preventDefault();
	 }, "img"); 
	 
	 

	Y.one('#scrollview-news-next').on('click',function (e) {
			scrollView.pages.next();
			checkPaging();
			

	});
	 
	Y.one('#scrollview-news-prev').on('click', function (e) {
	 	scrollView.pages.prev();
		checkPaging();
	});

	content.on('flick', function (e) {
		checkPaging();
	});
	
	
	
	 function checkPaging() {
	 
	 	 	Y.log('total '+ scrollView.pages.get("total"));
	 		Y.log('index '+ scrollView.pages.get("index"));
			
			var total = scrollView.pages.get("total");
	 		var index = scrollView.pages.get("index");
			
			{/literal}			
			{foreach $news as $n}
				{if $n@first}
					var q = {$n@key};
				{/if}
			{/foreach}
			{literal}
			
			
			if( total >=6 && total-index <= 5 ){

				var uri = "request/search.php?action=get_content&q="+q+"&offset="+(index+5)+"&limit="+10;
			 	var request = Y.io(uri);

			}
			
			
			function complete(id, o, args) {
			
				var data = o.responseText; 
				
				try {
					var json_data = Y.JSON.parse(data);
				}catch (o) {
					json_data= [];
				}
			
			
				var id = id; // Transaction ID.

				
				var content_news = new Array ();
				Y.Object.each(json_data.content, function (value, index){
				
				content_news.push(value);
				
				
				});
				  
				  
				Data_mustage = template({
					news: content_news 
				});

				// Append the rendered template to the page.
				Y.one('#content-news').append(Data_mustage);
				
				scrollView.syncUI();

				
				  
			};
		
		
			Y.on('io:complete', complete, Y);

    };
 
}); 

</script> 
{/literal}
