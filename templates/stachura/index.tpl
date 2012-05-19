<!DOCTYPE html>
<html lang="de">
<head>
<title>Roman Stachura </title>
<meta charset="utf-8" >
<link rel="stylesheet" href="http://yui.yahooapis.com/combo?3.5.1/build/cssgrids/cssgrids-min.css&3.5.1/build/cssreset/cssreset-min.css&3.5.1/build/cssfonts/cssfonts-min.css&3.5.1/build/cssbase/cssbase-min.css&3.5.0/build/cssbutton/cssbutton-min.css">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Orbitron:400,500,700,900&text=stachura.ch"  >
<link rel="stylesheet" media="all" href="/css/main.css"> 
<link rel="stylesheet" href="assets/anim/anim.css" >

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script src="http://yui.yahooapis.com/3.5.1/build/yui/yui-min.js"></script>
</head>

<body id="doc" class="yui3-skin-sam  yui-skin-sam" >

<div id="background">

{foreach $BGPictures  as $BgP}
<img class="stretch"  src="img/upload/{$BgP.sidepictures.0}" alt="hintergundbild">
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
				<li class="nav-tab hoverable  nav-tab-active" > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}"  class="active" >{$nav.name}</a> </li> 
			{else}
				<li class="nav-tab hoverable"  > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" title="{$nav.name}"  >{$nav.name}</a> </li>
			{/if}
		{/if}                            
		{/foreach}
		</ul>
   </nav>

</header> 
   
<div class="yui3-g"> 
   <aside class="yui3-u-1-4"> 
      <div class="content">
      
      
      {if $navigation_2}
      
      <div id="toggle-work" class="yui3-module toggle-area">
         <div class="yui3-hd">
            <h2>{$content.title}</h2> 
         </div>
         <div class="yui3-bd">
            <ul>
         {foreach $navigation_2 as $nav}
         
            {if $smarty.session.level_3 == $nav.id}
               <li class="current" > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}"  title="{$nav.name}"  class="active" >{$nav.name}</a> </li> 
            {else}
               <li class="nav_level2"  > <a href="?id={if $nav.link}{$nav.link}{else}{$nav.id}{/if}" title="{$nav.name}"  >{$nav.name}</a> </li>
            {/if}
            
         {/foreach}
            </ul>
         </div>
      </div>  
         
      {/if}
    
      {if $content.nested_set_id == 1}
       
         <div id="toggle-work" class="yui3-module toggle-area">
            <div class="yui3-hd">
               <h2>Kürzliche Arbeiten:</h2> 
         </div>
            <div class="yui3-bd">
               <ul>
                  <li><a href="http://www.stachura.ch/finishers">Finishers Preview</a></li>
                  <li><a href="/sae/unterricht/">HTML5 Unterricht</a></li>
                  <li><a href="http://prezi.com/o5lbewwiaz1f/soziale-medien/">Presentation: Soziale Medien </a></li>
                  <li><a href="/sae/">HTML5 Workshop</a></li>
                  <li><a href="http://www.stadttheater-olten.ch">Stadttheater Olten</a></li>
                  <li><a href="http://webstarter.stachura.ch">Webstarter Delicious</a></li>
                  <li><a href="http://www.spm.ch">SPM Google Apps </a></li>
                  <li><a href="http://http://my-sport.sites.djangoeurope.com/">mysport trainigs tool</a><sup>*</sup></li>
               </ul>
            </div>
         </div>   

         <h2>Long Term Projekte:</h2> 
         <ul>
            <li><a href="http://nodejs.org/">NodeJS</a>
               <ul>
                  <li><a href="http://kyon.stachura.ch:8000/">Nodejs Hellow World</a></li>
                  <li><a href="http://howtonode.org/">How To Node </a></li>
                  <li><a href=" http://npm.mape.me/"><strong>N</strong>ode<strong>P</strong>ackage <strong>M</strong>anager </a></li>
               </ul>
            </li>   
            <li><a href="http://www.kk.org/quantifiedself/">Quantified Self YQL YUI </a></li>
            <li><a href="horde">Horde 4.0</a><sup>*</sup></li>
            <li><a href="/howtos/yql-horde.php">How To "YQL Open Table für Horde 4.0"</a></li>
         </ul>
         <h2>Mixed:</h2>
         <ul>
            <li><a href="/protected/">PEAR</a><sup>*</sup></li>
            <li><a href="https://github.com/">GitHUB</a></li>
         </ul>
         <h2>Links:</h2>
         <ul>
            <li><a href="http://westciv.com/tools/box-properties/index.html">CSS3</a></li>
         </ul>              
         <sup>*</sup> Login ist erforderlich

      {/if}         
      </div> 
   </aside> 
 
   <div class="yui3-u-3-4"> 
      <div class="content">
      <div id="wetter"></div>
      {$content.content}
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
			requires : [ 'gallery-formmgr', 'panel', 'dd-plugin', 'io-form', 'json']
		},
      'gallery-localWeather':{
			fullpath : 'js/yui3-gallery/gallery-weather.js',
			requires : ['widget', 'substitute', 'yql', 'datatype-date']
		}

   }
   
 
}).use(
   'gallery-notify',
   'gallery-localWeather',
   'gallery-yui-slideshow',
   'overlay-login',
   'toggle-div',
   'gallery-yquery',
   'event-focus',
   'event',
   'node',    
   'json',
   'plugin',
   'datatype',
   'dump',  function (Y) {
   

   var slideshow = new Y.Slideshow({ 
      srcNode: '#background',
      duration: 5,
      interval: 20
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