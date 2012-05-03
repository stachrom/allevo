{config_load file="allevo.conf" section="usermanagement"}

<div id="live-users-search">
	<label for="liveuser-input">Search Users: </label><br>
	<input id="liveuser-input" type="text">
</div>
  
<div id="edit_user_overlay">
  	<div class="yui3-widget-hd"></div>
	<div id="widget" class="yui3-widget-bd"></div>
    <div class="yui3-widget-ft"></div>
</div>
   
<div id="results" class="yui3-1"></div> 







{foreach $auth_users as $user}

     {if $user.perm_user_id == $liveuser.perm_user_id}
         <h2>Deine Rechte:</h2>
     {else}
         <h2>{$user.handle}</h2>
     {/if}
  
    <ul> 
	{if $user.perm_type >= 4}
     <li>Für Master- und Superadministratoren bestehen keine Einschränkungen.</li>
	{else}     
		{foreach $UserRechteAllevo as $entry}
			<li id="{$entry@key}_{$entry.0.area_id}"> <b>{$entry@key}: </b> 
         	<ul class="rights"> 
					{foreach $entry as $userright}{if $user.perm_user_id ==  $userright.perm_user_id}<li class="rechte">{$userright.right_define_name}</li>{/if}{/foreach}
            </ul> 
			</li>
		{/foreach}
	{/if}
   </ul>   
   
{/foreach}  
