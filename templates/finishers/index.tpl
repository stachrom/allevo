<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8"> 
	<title>Allevo</title>
   
	<link rel="stylesheet" href="http://yui.yahooapis.com/combo?3.5.0/build/cssfonts/fonts-min.css&amp;3.5.0/build/cssreset/reset-min.css&amp;3.5.0/build/cssgrids/grids-min.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,800italic,600italic"  >
	<link rel="stylesheet" href="css/main.css" >
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" >
   
	<!--[if IE]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
   <script src="http://yui.yahooapis.com/3.5.0/build/yui/yui-min.js"></script>
   <script src="https://apis.google.com/js/plusone.js"></script>
</head>
 
<body  class="yui3-skin-sam">
<div id="doc">

    <header id="hd" >
    	<div class="content" id="hd-content">
      <div id="wetter"> </div>
		<aside>
			<ul id="headerlink"> 

            <li> 
				{if $liveuser.loggedIn} 
                User: <span style="color:black">{$liveuser.handle} </span> <a href="?logout=1">Logout</a> | <a href="./admin.php">Administration</a>
                <a id="show-loginOverlay" style="display:none;" href="#">Login</a>
            {else}
                <a id="show-loginOverlay" href="#">Login</a>
            {/if}
          	</li>     
         </ul>  
			<form id="main-search" class="search" action="http://finishers.ch/search" method="get" role="search">
            <input type="search" class="search-input yui3-aclist-input" name="q" placeholder="Search Trainings / Webpage / Mitglieder" autocomplete="off" >
			</form>
      </aside>

        <div class="yui3-g">
			<div id="main-nav" class="yui3-u">
				<nav>
					<ul class="nav hoverable-group">  
						{foreach $navigation_1 as $nav}

						{if $nav.name == "Animation"}
			
						{else}
						
							{if $smarty.session.level_2 == $nav.id}
							<li class="nav-tab hoverable  nav-tab-active" > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}"  class="active" >{$nav.name}</a> 
							{else}
							<li class="nav-tab hoverable"  > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" title="{$nav.name}"  >{$nav.name}</a> 
							{/if}
							
							{if $nav.subnavigation}
								<ul class="nav-submenu">
									{foreach $nav.subnavigation as $nav_2}
									<li class="nav-child " > <a href="?id={if $nav_2.link}{$nav_2.link}{else}{$nav_2.id}{/if}"  title="{$nav_2.name}"  >{$nav_2.name}</a></li>
									{/foreach}
								</ul>
							{/if}
							</li>  

						{/if}                            
						{/foreach}
					</ul>
				</nav>
			</div>

			<nav id="breadcrumb" class="yui3-u-1">
			<ol role="navigation">   
			{foreach $breadcrumb as $nav} 
			   <li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
				 <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" itemprop="url" title="{$nav.name}"><span itemprop="title">{$nav.name}</span></a>
			   </li>
			{/foreach}

			{if $smarty.get.eventUID && !$smarty.get.id}

			{else}
			<li>{$content.title}</li> 
			{/if}

			
				
			</ol>                   
			<br style="clear:both;">
			</nav>
	   
				<nav id="subnav" class="yui3-u-1">
	  
					<div class="divider">
						<hr>
					{if $smarty.get.eventUID && !$smarty.get.id}
						<h1>Event</h1>
					{else}
						<h1>{$content.title}</h1>
					{/if}
					</div>
	   
					{if $navigation_siblings}
						<ul class="subnav">
						{foreach $navigation_siblings as $nav} 
						<li>
							{if $nav.id == $smarty.get.id}
							<a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" class="current" title="{$nav.name}">{$nav.name}</a>
							{else}
							<a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}">{$nav.name}</a>
							{/if}
						</li>
						{/foreach}
						</ul>
					{else}
						<ul class="subnav"> 
						{foreach $navigation_2 as $nav}
							{if $nav.name == "Animation"}
							{else}
							<li>
								{if $nav.id == $smarty.get.id}
									<a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" class="current" title="{$nav.name}">{$nav.name}</a>
								{else}
									<a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}">{$nav.name}</a>
								{/if}
							</li>
							{/if}
						{/foreach}	
						</ul>
					{/if}

				</nav>
		</div>
		</div>
	</header>
   
   
{if $content.title == "Mitglieder"}
	{include file='finishers/turba.tpl'} 
{/if} 
       
{if $content.title == "Resultat Eintragen"}
	{include file='finishers/fusiontable.tpl'} 
{/if}  
   
{if $content.title == "Resultate"}
	{include file='finishers/dashboard.tpl'} 
{/if}


{if $content.title == "mitglied werden"}
	{include file='finishers/anmeldeformular.tpl'} 
{/if}         
   
 
 
 
 
 
    <div class="yui3-g">
 		<div class="yui3-u-2-5" > 
			<div class="content">
			{foreach $content.sidepictures as $pic}
			   {if $pic}
			   <img src="img/upload/446px/{$pic}"  alt="{$pic}"  style="width:97%" > 
			   {/if}
			{/foreach}
			
			{if $pic}
			<div style="height:20px"></div>
			{/if}
		
			

			<div  class="yui3-skin-sam">
				<div id="mycalendar"></div>
			</div>
         

			<div id="twitter-feed"></div>

         
         
         
         
			{$content.content2}

			{if $turba_contact}
				{$turba_contact}
			{/if} 
			</div>
		</div>
        
 		<div class="yui3-u-3-5"> 
			<div class="content" id="single-content">
				{$content.content}
				 
				{if $kronolith_event}

				{$kronolith_event}
				 
				{/if}
				<div id="datatable"></div>
			</div>
 		</div>

		<footer class="yui3-u" >
			{if $content.tags} 
			  <dl>
				   <dt>Tags in {$content.title} : </dt>
				 {foreach $content.tags as $tags}
				   {if $tags}
					<dd><a href="#{$tags}" class="tag">{$tags}</a></dd>
				   {/if}
				{/foreach} 
			  </dl>
			{/if} 
		</footer>     
	</div>
	

   {if $news AND $content.nested_set_id != 1}
   {include file='finishers/news.tpl'} 
    {/if}     
   
        


   
    


{if $content.title == "Vorstand"}
	<div id="scrollview-container">
    <section class="yui3-g" >
         <header class="yui3-u-1" id="scrollview-header" >
            <h1>{$content.title}</h1>
            
           
             <nav style="display: block; ">
            	<a class="prev" href="#prev" title="Previous Vorstandsmitglied" id="scrollview-prev" >‹‹</a>
            	<a class="next" href="#next" title="Next Vorstandsmitglied" id="scrollview-next" >››</a>
         	</nav>
         </header>

		<div class="yui3-u-1" >
			<div id="scrollview-content" class="yui3-scrollview-loading">
				<div class="yui3-g" >

 {foreach $vorstand as $n}

    <article class="yui3-u-1-3">
      <div class="content">
            <header>
               <a href="?id={$n.nested_set_id}" > <h1>{$n.title}</h1>   </a>
               <time pubdate="pubdate" datetime={$n.timestamp|date_format:"%Y-%m-%d"} >{$n.timestamp|date_format:"%d.%m.%Y"}</time>
            </header>
            
            
            {foreach $n.sidepictures as $pic}
              {if $pic}
              <img src="img/upload/280px/{$pic}"  alt="{$n.title}"  style="width:50%; float:left;"  > 
              {/if}
           {/foreach}
   
            <p>
            {assign var=content_vorstand value=$n.content|strip_tags}
            {$content_vorstand|truncate:500:"<a href=\"?id={$n.nested_set_id}\" >... </a>"}  
            </p>
   
            <footer></footer>
 
	</div>
   </article>
   

         
{/foreach}
	
   <footer>  </footer>
   
   
   
   </div>
   </div>
   </section>
   </div>
{/if}


    
{if $scrollview OR $vorstand AND  $content.nested_set_id != 1 }



<script> 
{literal}	 
YUI().use('scrollview',  'scrollview-paginator', function (Y) {


	 var scrollView = new Y.ScrollView({
		 id: 'scrollview',
		 srcNode: '#scrollview-content',
		 width: Y.one('#scrollview-container').getComputedStyle("width"),
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
	 
	 

	Y.one('#scrollview-next').on('click',function (e) {
			scrollView.pages.next();
	});
	 
	Y.one('#scrollview-prev').on('click', function (e) {
	 	scrollView.pages.prev();
	});


 
	  
}); 
{/literal}	
</script> 

{/if}


{if $news}


{/if}



{if $animation and !$smarty.get.eventUID}


    <div class="yui3-g">
 		<div class="yui3-u-1-4"> 
			<div class="content">
				<a href="?id=639">
					<div class="divider">
						<hr>
						<h2>Schwimmen</h2>
					</div>
					<img src="img/startseite/schwimmen.jpg" alt="" style="width:100%">
				</a>
			</div>
    	</div>
       
		<div class="yui3-u-1-4"> 
			<div class="content">
				<a href="?id=638">
					<div class="divider">
						<hr>
						<h2>Laufen</h2>
					</div>
					<img src="img/startseite/laufen.jpg" alt="" style="width:100%">
				</a>
			</div>
		</div>
      
		<div class="yui3-u-1-4"> 
			<div class="content">
				<a href="?id=640">
					<div class="divider">
						<hr>
						<h2>Radfahren</h2>
					</div>
					<img src="img/startseite/velofahren.jpg" alt="" style="width:100%">
				</a>
			</div>
    	</div>
      
		<div class="yui3-u-1-4"> 
			<div class="content">
				<a href="?id=642">
					<div class="divider">
						<hr>
						<h2>Nordic Walking</h2>
					</div>
					<img src="img/startseite/walking.jpg" alt="" style="width:100%">
				</a>
			</div>
    	</div>     
    </div> 



    <div class="yui3-g">
 		<div class="yui3-u-1"> 
			<div class="content slideshow">
				<ul id="slideshow">
				{foreach $animation as $n}
				   <li>
				   <blockquote>
				   {$n.content}
				   <cite>{$n.title}</cite>
				   </blockquote>
				   </li>
				{/foreach}
				</ul>
    		</div>
    	</div>      
    </div>       
                 
      {literal}
		<script> 
              YUI({
               gallery: 'gallery-2011.12.14-21-12'
               }).use('gallery-yui-slideshow',  function(Y){
                      
                  var slideshow = new Y.Slideshow({ 
                        srcNode: '#slideshow',
                        duration: 3,
                        interval: 10
                     });
            
                  slideshow.render();

						Y.on('domready', function () {
							  var node = Y.one('#slideshow');
							  node.setStyle( "visibility", 'visible');
						});
      
                      
               });
      
      </script>  
      {/literal}
                     
{/if} 

	<footer id="ft"> 
		<div class="yui3-g">
			<div class="yui3-u-1-4"> 
				<div class="content">
				
				</div>
			</div>	
			<div class="yui3-u-1-4"> 
				<div class="content">
          
			
				</div>
			</div> 
			<div class="yui3-u-1-4"> 
				<div class="content" >
  
			
				</div>
			</div>       
			<div class="yui3-u-1-4"> 
				<div class="content">
					<p style=" text-align:right">
					Letzte Änderung:  <time datetime="{$content.modified|date_format:"%Y-%m-%d"}" >{$content.modified|date_format:"%A der %d. %B %Y"}</time>
					</p>
				</div>
			</div> 
		</div>      
	</footer>



	<div id="panelContent">
		<div class="yui3-widget-bd">
			<p id="form-status"></p>
			<form id="login" name="login" action="?action=authentication" >
				<fieldset><legend><b>Credentials</b></legend>
					 <div class="formmgr-row"> 
						<label for="handle">Benutzername <em>*</em></label>
						<span class="formmgr-message-text"></span> 
						<input type="text" id="handle" name="handle" class="yiv-required">
					 </div>

					 <div class="formmgr-row"> 
						<label for="passwd">Passwort <em>*</em></label>
						<span class="formmgr-message-text"></span>
						<input type="password" id="passwd" name="passwd" class="yiv-required"> 
					 </div>
          
					<div class="formmgr-row">      
						<span class="formmgr-message-text"></span> 
						<div>   
						   <label for="rabo1" >Stay logged in:</label>
						   <input type="checkbox" id="rabo1" name="rememberMe" value="1"> 
						   <label for="rabo1" class="radio-label">Remember me</label> 
						</div> 
					</div> 
				</fieldset>
			</form>
		</div>   
	</div>
</div>
	
{if $calendar_tags || $smarty.get.eventUID || $calendar_id}

<script> 	 
YUI({
	gallery: 'gallery-2012.01.25-21-14',
	lang:'de'

}).use(
	'datatype-date', 
	'datatable', 
	'node-event-delegate', 
	'datasource-io', 
	'datasource-jsonschema',   
	'event-hover', 
	'json-parse', 
	'json-stringify',  
	'calendar', 
	'datatype-date-math', 
function (Y) {

	var dtdate = Y.DataType.Date;
	var calendar_id ="{$calendar_id}";		
	var dtDates,
		dataSource,
		calendar_dates,
		stopDate,
		startDate;

	{if $stopDate}
	
		stopDate = "{$stopDate}";
		startDate = "{$startDate}";
		calendar_dates = "{$calendar_dates}".split('/');
	{else}
		stopDate = dtdate.format(new Date());
	{/if}
			
		  
	var dataSource =  new Y.DataSource.IO(
		{ source:"/request/search.php" }

	);
	
    dataSource.plug(Y.Plugin.DataSourceJSONSchema, {
       schema: {
			resultListLocator: "calendar.events",
			resultFields: [
				{ key:"date", parser: Y.DataType.Date.parse}, 
				{ key:"uid" }, 
				{ key:"t" },
				{ key:"l" },  
				{ key:"durMin" },
				{ key:"tg" } 
			]
       }
   });	  
		  

	var  formatDates = function(o){
		Y.log("Tags "+ Y.dump(o.value));
        return Y.DataType.Date.format(o.value, { format:"%d.%m.%Y" });
    };
	 
	var formatLinks = function(o){
	
		var linkName  = o.data.t,
		date = Y.DataType.Date.format(o.data.date, { format:"%d.%m.%Y" }),
		item = '<a class="datatablelink" href="http://finishers.stachura.ch/?eventUID='+ o.value +'&date='+ date +'"> <img class="imgLink datatable" src="/img/icons/goto.png" alt=" Details von Event '+ linkName +'" ></a>';
		
  		return item;
    };
	 
	 
	var formatTags = function(o){
	
		var item ='<ul class="calendarTags">';
		
		if(o.value != null){
			for (x = 0; x < o.value.length; x++) {
				item += '<li class="tag">'+o.value[x]+'</li>';
  			};
		};
		
		item += '</ul>';	
	
  		return item;
    };
	 
	 
    
	var cols = [
		{ key:"date", label: "Datum", abbr: "D", formatter:formatDates,  sortable:true }, 
		{ key:"l",  label: "Ort", abbr: "O", sortable:true },
		{ key:"durMin", label: "Dauer", abbr: "Z",  sortable:true },
		{ key:"t",  label: "Titel", abbr: "T",  sortable:true },
		{ key:"tg",  label: "Tags", abbr: "Ta",  formatter:formatTags, allowHTML:true },
		{ key:"uid",  label: "Link", abbr: "D",  formatter:formatLinks, allowHTML:true }
	];
	 
	var table = new Y.DataTable({
	 	columns:cols, 
    	caption:"Finishers Trainings"
	});
		
	table.plug(Y.Plugin.DataTableDataSource, { 
		datasource: dataSource,
		initialRequest: "?action=calendar&calendar_id="+calendar_id+"&q="+startDate+'/'+stopDate
	});
	
	dataSource.after("response", function() {
		//Y.one('#yui-main .yui-g ul').addClass('loading');
		//Y.one('#yui-main .yui-g ul').removeClass('loading');
		table.render("#datatable");
	});

	Y.CalendarBase.prototype._afterHeaderRendererChange = function () {
		var headerCell = this.get("contentBox").one(".yui3-calendar-header-label");
		headerCell.setContent(this._updateCalendarHeader(this.get('date')));
	};

	// Switch the calendar main template to the included two pane template
	Y.CalendarBase.CONTENT_TEMPLATE = Y.CalendarBase.TWO_PANE_TEMPLATE;

	var calendar = new Y.Calendar({
			contentBox: "#mycalendar",
			width: "100%",
			showPrevMonth: true,
			showNextMonth: true,
			selectionMode: 'multiple',
			date: new Date()
	}).render();


	calendar.set("headerRenderer", function (curDate) {
		var ydate = Y.DataType.Date,
		   output = ydate.format(curDate, {
		   format: "%B %Y"
		 }) + " &mdash; " + ydate.format(ydate.addMonths(curDate, 1), {
		   format: "%B %Y"
		 });
	   return output;
	}); 



	if(stopDate && startDate){
	
		table.set('caption', 'Finishers Trainigs <br>'+startDate+' - '+stopDate );
		
		for (x = 0; x < calendar_dates.length; x++) {
			var date = new Date(calendar_dates[x]);
			calendar.selectDates(date);
		}
	}

	calendar.on("selectionChange", function (ev) {

 		var newSelection = ev.newSelection
 		var Dates = new Array();

		for (i=0; i<ev.newSelection.length; i++){
			var newDate = ev.newSelection[i];
			Dates[i] = dtdate.format(newDate);
				
			Y.log(dtdate.format(newDate));
				
			var startDate = Y.DataType.Date.format(ev.newSelection[0], { format:"%d.%m.%Y" });  
			var stopDate = Y.DataType.Date.format(ev.newSelection[i], { format:"%d.%m.%Y" });    
		}
	
		table.set('data', [] );
		table.showMessage('loadingMessage');
		table.datasource.load({ request: "?action=calendar&calendar_id="+calendar_id+"&q="+Dates });
		table.set('caption', 'finishers Trainigs <br>' +startDate+' - '+stopDate );
	});	

	Y.one('#datatable').delegate('click', show_tags, '.tag');		

	function show_tags(e) {
	
		var tag = this.get('text');
		
		table.datasource.load({
			request: "?action=searchTags&q="+tag 
		});
		
		table.set('caption', 'finishers '+tag+' Trainigs' );
	}
 
}); 
	 
</script> 
	

{/if}



<script> 
{literal}	 
YUI({
	gallery: 'gallery-2012.01.25-21-14',
	lang:'de',
	modules : {
		'hoverable' : {
			fullpath : '/js/yui3/hoverable-min.js',
			requires : ['event-hover', 'node-base', 'node-event-delegate']
		},
		'search':{
			fullpath : '/js/yui3/search.js',
			requires : ['autocomplete','autocomplete-highlighters','node-pluginhost']
		},
		'overlay-login':{
			fullpath : 'js/yui3/overlay_login.js',
			requires : [ 'gallery-formmgr', 'panel', 'dd-plugin', 'io-form', 'json']
		},
      'gallery-localWeather':{
			fullpath : 'js/yui3-gallery/gallery-weather.js',
			requires : ['widget', 'substitute', 'yql', "datatype-date-format", "datatype-date-parse" ]
		}
  
	}
}).use('hoverable', 'search', 'overlay-login', 'gallery-localWeather', function (Y) {


   var navHoverable = new Y.Hoverable({
            hoverClass: 'nav-tab-hover',
            srcNode: '#main-nav'
      });
      
   var wetter = new Y.LocalWeather({
                  location : 'winterthur',
						u :'c',
                  layout : 'small'
         }).render('#wetter');  
}); 

</script> 
{/literal}		
</body> 
</html>
