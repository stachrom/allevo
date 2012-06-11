<!DOCTYPE html>
<html lang="de">
<head>
<title>{$content.title}</title>
<meta charset="utf-8" >
<link rel="stylesheet" media="screen, projection" href="http://yui.yahooapis.com/combo?3.5.1/build/cssgrids/cssgrids-min.css&amp;3.5.1/build/cssreset/cssreset-min.css&amp;3.5.1/build/cssfonts/cssfonts-min.css&amp;3.5.1/build/cssbase/cssbase-min.css&amp;3.5.0/build/cssbutton/cssbutton-min.css">
<link rel="stylesheet" media="screen, projection" href="http://fonts.googleapis.com/css?family=Orbitron:400,500,700,900&amp;text=stachura.ch"  >
<link rel="stylesheet" media="screen, projection" href="/css/main.css"> 
<link rel="stylesheet" media="print" href="/css/print.css" >

<link rel="stylesheet" href="assets/anim/anim.css" >
<link rel="stylesheet" href="css/animation.css" >

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32485696-1']);
  _gaq.push(['_setDomainName', 'stachura.ch']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script src="http://yui.yahooapis.com/3.5.1/build/yui/yui-min.js"></script>
</head>
<body id="doc" class="yui3-skin-sam  yui-skin-sam" >
<div id="background">
{foreach $BGPictures  as $BgP}
<img class="stretch"  src="img/upload/{$BgP.sidepictures.0}" alt="">
{/foreach}
</div>

<header id="hd">  
   <div id="logo"> 
      <h1><span>stachura</span>.<small>ch</small></h1>
   </div>
   <div id="logger"></div>  
   <nav>
      <ul class="nav">
      {foreach $navigation_1 as $nav}
      
      {if $nav.name == "Backgroundpictures"}
      
      {else}
         {if $smarty.session.level_2 == $nav.id}
            <li><a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}"  class="current" >{$nav.name}</a></li> 
            {else}
            <li><a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" title="{$nav.name}" >{$nav.name}</a></li>
         {/if}
      {/if}
      
      {/foreach}
      </ul>
   </nav>

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
	</nav>
   
   
   

</header> 
   
<div class="yui3-g"> 
   <aside class="yui3-u-1-4"> 
      <div class="content">
      
      	{foreach $content.sidepictures as $pic}
			   {if $pic}
			   <img src="img/upload/280px/{$pic}"  alt="{$pic}"  style="width:95%" > 
			   {/if}
			{/foreach}
      
      {if $navigation_2}
      
      <div id="toggle-work" class="yui3-module toggle-area">
         <div class="yui3-hd">
         {foreach $breadcrumb as $nav} 
            {if $smarty.session.level_2 == $nav.id}
               <h2>{$nav.name}</h2> 
            {elseif $content.nested_set_id == $smarty.session.level_2}  
               <h2>{$content.title}</h2>             
            {/if}

         {/foreach}

         </div>
         <div class="yui3-bd">
            <ul>
         {foreach $navigation_2 as $nav}
         
            {if $smarty.session.level_3 == $nav.id}
               <li class="current nav_level2" > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}"  >{$nav.name}</a> </li> 
            {else}
               <li class="nav_level2"  > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" title="{$nav.name}"  >{$nav.name}</a> </li>
            {/if}
            
         {/foreach}
            </ul>
         </div>
      </div>  
         
      {/if}

      </div> 
   </aside> 
 
   <div class="yui3-u-3-4"> 
      <div class="content">
      <div id="wetter"></div>
      
      
      {if $content.title === "Arbeiten"}
      
      <p>
         Ein Auszug meiner bisherigen Arbeit aufgeteilt in <strong>Webentwicklung</strong>, 
         <strong>Webdesign</strong>, <strong>Datenvisualisierung</strong> und <strong>Animationen</strong>.
      </p> 
<br>
      <div class="yui3-g" >
      
         <div class="yui3-u-1-24">
            <div class="content-nav">
               <span id="scrollview-prev">‹‹</span>
            </div>
         </div>
         
         <div class="yui3-u-11-12">  
           <div id="scrollview-content" class="yui3-scrollview-loading">
               <ul>
                  <li><img src="img/work/finishers.jpg" alt="Finishers Winterthur"></li>
                  <li><img src="img/work/singlespeed.jpg" alt="Singlespeed"></li>
                  <li><img src="img/work/fotohaus.jpg" alt="Fotohaus"></li>
                  <li><img src="img/work/gallery-dreyfus.jpg" alt="Gallery Dreyfus"></li>
                  <li><img src="img/work/berater.jpg" alt="Berater und Partner"></li>
                  <li><img src="img/work/leupartner.jpg" alt="Leupartner"></li>
                  <li><img src="img/work/riset.jpg" alt="Riset AG"></li>
                  <li><img src="img/work/stalder.jpg" alt="Stalder Monatgen"></li>
                  <li><img src="img/work/stalder2.jpg" alt="Stalder Abdichtungen"></li>
                  <li><img src="img/work/gremper.jpg" alt="Gremper"></li>
                  <li><img src="img/work/stadttheater.jpg" alt="Gremper"></li>
               </ul>
            </div>
         </div>
         
         <div class="yui3-u-1-24">
            <div class="content-nav">
               <span id="scrollview-next">››</span>
            </div>
         </div>
      </div>
   {/if}
      
      {$content.content}
      <div id="twitter-feed"></div>
      </div> 
   </div> 
</div> 
 
<footer id="ft">
   <a href="http://www.w3.org/html/logo/"><img src="http://www.w3.org/html/logo/badge/html5-badge-h-connectivity-css3-device-graphics-multimedia-performance-semantics-storage.png" width="357" height="64" alt="HTML5 Powered with Connectivity / Realtime, CSS3 / Styling, Device Access, Graphics, 3D &amp; Effects, Multimedia, Performance &amp; Integration, Semantics, and Offline &amp; Storage" title="HTML5 Powered with Connectivity / Realtime, CSS3 / Styling, Device Access, Graphics, 3D &amp; Effects, Multimedia, Performance &amp; Integration, Semantics, and Offline &amp; Storage"></a>
   <div class="login">
   
      {if $liveuser.loggedIn} 
         <div class="loggedin">
            Willkommen <b>{$liveuser.handle} </b>
            last login: {$liveuser.Last_Login} <br>
            <a href="admin.php">Administration</a>
            <a href="?logout=1"> Log out </a>
            <a id="show-loginOverlay" ></a>
         </div>
      {else}
         <a id="show-loginOverlay" href="#">Login</a>
      {/if}

   </div>
   
<div id="panelContent">
	<div class="yui3-widget-bd">
		<p id="form-status"></p>
		<form id="login" name="login" action="?action=authentication"  method="get" >
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


</footer> 
{literal}
<script> 
YUI({
   gallery: 'gallery-2012.04.18-20-14',
   combine: true,
   modules : {
      'gallery-effects' : {
         fullpath : 'js/yui3-gallery/gallery-effects.js',
         requires : ['node','anim','async-queue']
         },
      'toggle-div' : {
         fullpath : 'js/yui3/toggle-div.js',
         requires : ['anim', 'node']
         },
      'overlay-login':{
			fullpath : 'js/yui3/overlay_login.js',
			requires : [ 'gallery-formmgr', 'panel', 'dd-plugin', 'io-form', 'json', 'event-key']
		},
      'gallery-localWeather':{
			fullpath : 'js/yui3-gallery/gallery-weather.js',
			requires : ['widget', 'substitute', 'yql', 'datatype-date']
		},
      'gallery-twitter-status':{
			fullpath : 'js/yui3-gallery/gallery-twitter-status.js',
			requires : ['widget', 'substitute', 'yql', 'datatype-date']
		}

   }
   
 
}).use(
   'gallery-notify',
   'gallery-localWeather',
   'gallery-yui-slideshow',
{/literal}
{if $content.title === "Twitter"}
   'gallery-twitter-status',
{/if}
{literal}
   'overlay-login',
   'toggle-div',
   'gallery-yquery',
   'event-focus',
   'event',
   'node',    
   'json',
   'plugin',
   'datatype',
   'scrollview', 
   'scrollview-paginator',
   'dump',  function (Y) {
   

   var slideshow = new Y.Slideshow({ 
      srcNode: '#background',
      duration: 10,
      interval: 60
   });
    
   slideshow.render();


   var work_node = Y.one('#toggle-work');
   
   if (work_node){
      var toggle_work = work_node.togglediv();
   }
   
   
   
   //work_node.addClass('yui3-closed');
   //toggle_work.setStyle("height", "0");
   //toggle_work.fx.set('reverse', true);
   

{/literal}


{if $content.title === "Arbeiten"}

   var scrollView = new Y.ScrollView({
        id: "scrollview",
        srcNode : '#scrollview-content',
        width : 622,
        flick: {
            minDistance:10,
            minVelocity:0.3,
            axis: "x"
        }
   });

    scrollView.plug(Y.Plugin.ScrollViewPaginator, {
        selector: 'li'
    });

    scrollView.render();

    var content = scrollView.get("contentBox");

    content.delegate("click", function(e) {
        // For mouse based devices, we need to make sure the click isn't fired
        // at the end of a drag/flick. We use 2 as an arbitrary threshold.
        if (Math.abs(scrollView.lastScrolledAmt) < 2) {
            //alert(e.currentTarget.getAttribute("alt"));
        }
    }, "img");

    // Prevent default image drag behavior
    content.delegate("mousedown", function(e) {
        e.preventDefault();
    }, "img");

    Y.one('#scrollview-next').on('click', Y.bind(scrollView.pages.next, scrollView.pages));
    Y.one('#scrollview-prev').on('click', Y.bind(scrollView.pages.prev, scrollView.pages));

{/if}


{if $content.nested_set_id == 1}

   wetter = new Y.LocalWeather({
                  location : 'Winterthur',
						u :'c',
                  layout : 'full'
					}).render('#wetter');
               
{/if}
   
{literal}

   login_node = Y.one('#show-loginOverlay')
   var notify = new Y.Notify({prepend:true});
   notify.render();

});
</script> 
{/literal} 
</body>
</html>