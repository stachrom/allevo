<fieldset><legend style="font-size:1.5em;"> Applikation <span style="color:blue;">{$edit_Appplication.application_name.value} </span> managen:</legend>
                 <br>
                 <h2> Module f&uuml;r die Applikation:"{$edit_Appplication.application_name.value}" </h2>





 	{if $LiveUserAreas}
         	<table border=0 cellspacing="1" cellpadding="1" id="usertabelle">
			<tr>
			    <th colspan=2>name: </th>
                        </tr>
                {section name=LiveUserAreas loop=$LiveUserAreas}

    		         <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
			    <td>{$LiveUserAreas[LiveUserAreas.index].area_define_name}</td>
			    <td>
                                    <a href="?AreaId={$LiveUserAreas[LiveUserAreas.index].area_id}&amp;cmd=EditArea&amp;AreaName={$LiveUserAreas[LiveUserAreas.index].area_define_name}"><img src="/img/admin/blatt.png"></a> </td>
			    </td>
			</tr>
	       {/section}
                </table>

 	{else}
	 Bisher noch keine Module erfasst.
	{/if}

         {literal}
        	<script language=javascript>
        	<!--
			var userInfo = new Array("bearbeiten", "new_modul" );

               // -->
	       </script>
        {/literal}



                        <ul id="navlist_content">

                        <li ><a href="javascript:void(0)" id="bearbeiten_link"   onclick="Aendern('bearbeiten_link'); Toggle('bearbeiten')">  Aplikationsname bearbeiten</a></li>
		        <li ><a href="javascript:void(0)" id="new_modul_link" class="current" onclick="Aendern('new_modul_link');Toggle('new_modul')">Neues Modul</a></li>

                        </ul>
                        

<div id="new_modul" style="display: inline;">

		<form {$edit_Appplication.attributes}>
		

		{$edit_Appplication.hidden}

         <span style="color:red; width:200px;">{$edit_Appplication.application_name.error} </span>



                <br/>
                 <h2> Neues Modul hinzuf&uuml;gen:   </h2>

                 {$edit_Appplication.new_area_name.label}
        	 {$edit_Appplication.new_area_name.html}

                 <br/>
                 <br/>

                {$edit_Appplication.abbrechenApplication.html}
                {$edit_Appplication.newArea.html}

		</form>
		
</div>

<div id="bearbeiten" style="display: none;">

		<form {$edit_Appplication.attributes}>
		{$edit_Appplication.hidden}




         <span style="color:red; width:200px;">{$edit_Appplication.application_name.error} </span>
        	<br/>
                 <h2> Edit Applikationen:   </h2>
                {$edit_Appplication.application_name.label}
        	{$edit_Appplication.application_name.html}



                 <br/>
                 <br/>
                {$edit_Appplication.deleteApplication.html}
                {$edit_Appplication.abbrechenApplication.html}
                {$edit_Appplication.updateApplication.html}

		</form>
		
</div>

</fieldset>