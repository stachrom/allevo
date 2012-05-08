{config_load file="allevo.conf" section="usermanagement"}

<div id="live-users-search">
	<label for="liveuser-input">Search Users: </label><br>
	<input id="liveuser-input" type="text">
</div>
 <div id="results" class="yui3-1"></div> 
  <ul id="manage-live-user">
{foreach $auth_users as $user}

     {if $user.perm_user_id == $liveuser.perm_user_id}
         <li>Deine Rechte:
     {else}
         <li >
         {if $user.is_active}
            <img src="img/admin/icon.active.gif">
         {else}
            <img src="img/admin/icon.inactive.gif">
         {/if}
         
         {$user.handle} <span class="live-user" title="Remove this User"><span class="perm-user-id hidden">{$user.perm_user_id}</span></span>
     
     {/if}
  
    <ul> 
	{if $user.perm_type >= 4}
     <li>Für Master- und Superadministratoren bestehen keine Einschränkungen.</li>
	{else}     
		{foreach $UserRechteAllevo as $entry}
			<li id="{$entry@key}_{$entry.0.area_id}"> {$entry@key}: 
         	<ul class="rights"> 
					{foreach $entry as $userright}{if $user.perm_user_id ==  $userright.perm_user_id}<li class="rechte">{$userright.right_define_name}</li>{/if}{/foreach}
            </ul> 
			</li>
		{/foreach}
	{/if}
   </ul> 
</li>   
{/foreach}







 
