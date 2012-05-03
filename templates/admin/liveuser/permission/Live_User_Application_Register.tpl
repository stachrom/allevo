

 { if $smarty.get.cmd == 'EditApplication' or $smarty.post.cmd == 'EditApplication'}



      {include file="admin/usermanagement/Live_User_Application_Manage.tpl"}


 { elseif $smarty.get.cmd == 'EditArea' or $smarty.post.cmd == 'EditArea' }



      {include file="admin/usermanagement/Live_User_Edit_Area.tpl"}


{else}

 <fieldset><legend style="font-size:1.5em;"> Bestehende Applikationen </legend>

	<table border=0 cellspacing="1" cellpadding="1" id="usertabelle">
			<tr>
				<th >Aplikation: </th>
				<th > </th>

                        </tr>
        {if $LiveUserApplications}
	{section name=Applications loop=$LiveUserApplications}

    		<tr bgcolor="{cycle values=#cycleColors#}">

				<td>{$LiveUserApplications[Applications.index].application_define_name}</td>
				<td><a href="?application_id={$LiveUserApplications[Applications.index].application_id}&amp;cmd=EditApplication&amp;application_name={$LiveUserApplications[Applications.index].application_define_name}"><img src="/img/admin/blatt.png"></a> </td>

			</tr>
	{/section}

	{else}
	 Bisher noch keine Applikation Erfasst.
	{/if}

	</table>

</fieldset>


   	<fieldset><legend style="font-size:1.5em;">Register New Application</legend>


		<form {$add_Appplication.attributes}>
		{$add_Appplication.hidden}


        	{$add_Appplication.application.label}
        	<br/>
                {$add_Appplication.application.html}
                <br/>

        	<span style="color:red; width:200px;">{$add_Appplication.application.error} </span>
        	<br/>
        	{$add_Appplication.buttons.html}


		</form>
		
		
	


      </fieldset>






{/if}