{config_load file="allevo.conf" section="usermanagement"}

	<span style="color:red ">{$LiveUserRightComments}</span>
	
	<br />

<div id="adduser">




{if $smarty.get.cmd_edit_user or $smarty.post.cmd_edit_user}



         {include file="admin/usermanagement/Live_User_Edit_User.tpl"}


{else}

		
	<fieldset><legend style="font-size:1.5em;">New User</legend>


		<form {$add_user_to_cmdbase.attributes}>
		   {$add_user_to_cmdbase.username.label}
		    <br/>
			{$add_user_to_cmdbase.username.html}
            {$add_user_to_cmdbase.hidden}
             <br/>
            {$add_user_to_cmdbase.pass1.label}
            <br/>
			{$add_user_to_cmdbase.pass1.html}
            <br/>

        	{$add_user_to_cmdbase.pass2.label}
        	<br/>
			{$add_user_to_cmdbase.pass2.html}
            <br/>

        	<span style="color:red; width:200px;">{$add_user_to_cmdbase.username.error} </span>
        	<span style="color:red; width:200px;">{$add_user_to_cmdbase.pass1.error}  </span>
        	<span style="color:red; width:200px;">{$add_user_to_cmdbase.pass2.error}  </span>
        	<br/>
        	{$add_user_to_cmdbase.buttons.html}
                <br/>
		</form>
		</fieldset>

	{/if}
</div>

   <div id="adduser">

        

	<fieldset><legend style="font-size:1.5em;"> User </legend>

	<table border=0 cellspacing="1" cellpadding="1" id="usertabelle">
            <tr>
                <th ></th>
                <th ></th>
                <th >Benutzername</th>
                <th >letztes Login: </th>
                <th></th>
            </tr>

	{section name=aktive_user loop=$aktive_user}

    		<tr bgcolor="{cycle values=#cycleColors#}">
    		    <td >
				{if $aktive_user[aktive_user.index].is_active eq 1}
					<img src="img/admin/icon.active.gif">
				{else}
					<img src="img/admin/icon.inactive.gif">
				{/if}
				</td>
				<td>{$aktive_user[aktive_user.index].auth_user_id}</td>
				<td>{$aktive_user[aktive_user.index].handle}</td>
				<td>{$aktive_user[aktive_user.index].lastlogin}</td>
                                <td><a href="?cmd_edit_user={$aktive_user[aktive_user.index].auth_user_id}"><img src="img/admin/blatt.png"></a></td>
			</tr>
	{/section}

	</table>

	</fieldset>

</div>

   <div id="register_application">

   {include file="admin/usermanagement/Live_User_Application_Register.tpl"}


 </div>

