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


{literal}
<script> 

YUI().use('node', "event", 'json', 'io', 'event-mouseenter', 'dump', 'anim', function (Y) { 

   Y.on('domready', function () {


      function over(e) {
         e.currentTarget.addClass('live-user-hover');
      }
      
      function out(e) {
         e.currentTarget.removeClass('live-user-hover');    
      }
      
      var handleSucces = function(id, o, a) {

         var data      = o.responseText,
             json_data = [];      
         
         try {
            json_data = Y.JSON.parse(data);
         }catch (o) {
            
         }			

         if (json_data.status == 200 && json_data.action == "deleteUser" ) {

				Y.log("sucess argument: " + Y.dump('#'+a));

				var parent_node = Y.one('#'+a).get('parentNode');

			   var anim = new Y.Anim({
					  node: parent_node,
					  to: { opacity: 0 }
				 });

    			var onEnd = function() {
					  var node = this.get('node');
					  node.get('parentNode').removeChild(node);
				};

				anim.on('end', onEnd);
				anim.run();

			}

    
    }; 
  
      
   function delete_user() {

				var perm_user_id = this.get('children').get('text');
				var click_id = this.get('id');

				var cfg = {
					method: 'POST',
					data:   {
								'action': 'deleteUser',
								'perm_user_id': perm_user_id
					},
					on:     {success: handleSucces},
					arguments: click_id 
				};
            Y.log("cfg : " + Y.dump(cfg) );
            
			   Y.io('admin.php', cfg);

	};   

      Y.one('#manage-live-user').delegate('hover', over, out, '.live-user');
      Y.one('#manage-live-user').delegate('click', delete_user, '.live-user-hover');
   
  });

});

</script> 
{/literal}





 
