<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">

{foreach key=schluessel item=wert from=$metatag_header}
<meta name="{$schluessel}" content="{$wert}">
{/foreach}

<title>{$Page_Title}</title>
<link rel="stylesheet" href="http://yui.yahooapis.com/2.8.2/build/reset-fonts-grids/reset-fonts-grids.css" >
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?3.5.1/build/cssbutton/cssbutton-min.css&3.5.1/build/cssgrids/cssgrids-min.css&3.5.1/build/cssreset/cssreset-min.css&3.5.1/build/cssfonts/cssfonts-min.css&3.5.1/build/cssbase/cssbase-min.css">
<link rel="stylesheet" href="http://{$smarty.server.SERVER_NAME}/css/admin/photos.css">
<link rel="stylesheet" href="http://{$smarty.server.SERVER_NAME}/css/admin/uploader.css">
<link rel="stylesheet" href="http://{$smarty.server.SERVER_NAME}{$pfad}/css/admin.css">

<link rel="stylesheet" href="assets/anim/anim.css" >

<style id="styleoverrides"></style>






<style>
{literal}

li.yui-button-selectedmenuitem {
	background: url( /img/admin/yui/checkbox.png) left center no-repeat;
}
	
.yui-skin-sam .yui-toolbar-container .yui-toolbar-editcode span.yui-toolbar-icon {
    background-image: url( /img/admin/yui/editor/html_editor.gif );
   background-position: 3px 2px;
    left: 4px;
    top: 2px;
}

.yui-skin-sam .yui-toolbar-container .yui-button-editcode-selected span.yui-toolbar-icon {
    background-image: url( /img/admin/yui/editor/html_editor.gif );
	background-position: 3px 2px;
    left: 4px;
    top: 2px;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-layout{
	width: 5em;
}


.yui-skin-sam .yui-toolbar-container .yui-toolbar-table{
	width: 5em;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-flickr span.yui-toolbar-icon {
    background-image: url( /img/admin/yui/editor/flickr_default.gif );
    background-position: 3px 2px;
    left: 4px;
    top: 2px;
}

.yui-skin-sam .yui-toolbar-container .yui-toolbar-flickr-selected span.yui-toolbar-icon {
    background-image: url( /img/admin/yui/editor/flickr_active.gif );
    background-position: 3px 2px;
    left: 4px;
    top: 2px;
}

.editor-hidden {
    visibility: hidden;
    top: -9999px;
    left: -9999px;
    position: absolute;
}

textarea {
    border: 0;
    margin: 0;
    padding: 0;
}

#yahooEditor-box {
	position: absolute;
	top:  -9999px;
	left: -9999px;
}

#editable {
	background-color:#FFFFFF;
	padding:5px;
	border:#CCCCCC 1px solid;
	min-height:500px;
}		  

/*  Extend Yahoo Editor Flickr suche "Gutter"  */


#gutter1 {
	overflow: hidden;
	visibility: hidden;
	text-align: left;
}

#gutter1 .bd {
	border:1px solid #808080;
	border-left: none;
	background-color: #F2F2F2;
	height: 95% ! important;
	overflow: hidden;
	width: 249px;
	margin-top: 10px;
	padding-left: .25em;
}

#gutter1 ul {
	list-style-type: none;
}

#gutter1 ul li {
	margin: 0;
	padding: 0;
	float:left;
	display:inline;
}

#gutter1 .bd h2 {
	font-size: 120%;
	font-weight: bold;
	margin: 0.5em 0;
	color: #000;
	border: none;
}

#gutter1 img {
	margin: 0 .5em;
	border:1px solid #808080;
	height: 50px;
	width: 50px;
}

#flickr_results {
	height: 75%;
	overflow: auto;
	position:static;
}

#flickr_results p {
    padding: .5em;
    margin-bottom: 1em;
}

#flickr_results div.yui-ac-content {
    width: 225px;
}

.yui-skin-sam .yui-ac-input {
    position: static;
    width: 12em;
}

#gutter1 .tip {
	display:block;
	font-size:85%;
	margin:0.5em;
	padding-left:23px;
	position:relative;
	text-align:left;
}

#gutter1 .tip span.icon-info {
	background-position:-106px -32px;
	background-image:url(css/sprite.png);
	background-position:-84px -32px;
	display:block;
	height:20px;
	left:0pt;
	position:absolute;
	top:0pt;
	width:20px;
}

#admin_allevo{
	widht:100%;
	text-align:left;
}

#drag_it {
	max-width: 250px;
}


#drag_it li{
	cursor:move;
	position: relative;
	padding:2px;
	border:1px  #666666 solid;
	background-color:rgba(114, 195, 103, 1);
	margin:2px;
	display: inline-block;
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-webkit-box-shadow: 1px 2px 4px rgba(0,0,0,.5);
	-moz-box-shadow: 1px 2px 4px rgba(0,0,0,.5);
	box-shadow: 1px 2px 4px rgba(0,0,0,.5);
}

{/literal}


{strip}
{foreach key=schluessel item=wert from=$css_header}
{$schluessel} {$wert}
{/foreach}
{/strip}
</style>


<script src="http://yui.yahooapis.com/3.5.1/build/yui/yui-min.js"></script>


<script type="text/javascript">
	{literal}
		YUI().use( 'console',function(Y) {
		
		
		new Y.Console({ logSource: Y.Global }).render();
		
		
			YUI.namespace('allevo');

	{/literal}
			YUI.allevo.HTTP_HOST = "{$smarty.server.SERVER_NAME}";
			YUI.allevo.pfad = "{$pfad}";
	{literal}
		});
	{/literal}
	
		{foreach key=schluessel item=wert from=$javascript_header}
			{$wert}
		{/foreach}

</script>


<link rel="shortcut icon" type="image/x-icon" href="http://{$smarty.server.SERVER_NAME}{$pfad}/favicon.ico" />
</head>

<body  class="yui3-skin-sam  yui-skin-sam">
