<br/>
<fieldset><legend style="font-size:1.5em;"> Modul Bearbeiten <span style="color:blue;">{$edit_Area.area_name.value} </span> </legend>
         <br/>

         {literal}
        	<script language=javascript>
        	<!--
			var userInfo = new Array("bearbeiten", "new_right" );

               // -->
	       </script>
        {/literal}



                        <ul id="navlist_content">

                        <li ><a href="javascript:void(0)" id="bearbeiten_link"   onclick="Aendern('bearbeiten_link'); Toggle('bearbeiten')"> Rollen f&uuml;r Modul </a></li>
		        <li ><a href="javascript:void(0)" id="new_right_link" class="current" onclick="Aendern('new_right_link');Toggle('new_right')">Benutzer Rechte</a></li>

                        </ul>


<div id="new_right" style="display:inline;">

		<form {$edit_Area.attributes}>


		{$edit_Area.hidden}

         <span style="color:red; width:200px;">{$edit_Area.area_name.error} </span>
                 <br>
                  <br>
                 {$edit_Area.area_name.label}
                 {$edit_Area.area_name.html}
                <br/>
                 <h2> Benutzer   </h2>







                 <table border=0 cellspacing="1" cellpadding="1" id="usertabelle">
			<tr>

                <th >Benutzername:</th>



            </tr>

	{section name=aktive_user loop=$aktive_user}

    		<tr bgcolor="{cycle values=#cycleColors#}">
				<td>
                                {assign var=perm_user_id value=$aktive_user[aktive_user.index].perm_user_id}
                                {$aktive_user[aktive_user.index].handle}    
                                </td>
                                
                                <td >

                                   {$edit_Area.$perm_user_id.html}

                                </td>


			</tr>
	{/section}

	</table>

           <br />
              {$edit_Area.abbrechenArea.html}
           {$edit_Area.saveUserRight.html}



		</form>

</div>

<div id="bearbeiten" style="display: none;">

		<form {$edit_Area.attributes}>

		  {$edit_Area.hidden}
		
		
         <br/>

 	 {$edit_Area.create.html}
 	 {$edit_Area.create.label}
          <br/>

        {$edit_Area.edit.html}
        {$edit_Area.edit.label}
          <br/>

        {$edit_Area.view.html}
        {$edit_Area.view.label}
          <br/>

        {$edit_Area.delete.html}
       {$edit_Area.delete.label}
          <br/>









		{$edit_Area.hidden}




         <span style="color:red; width:200px;">{$edit_Area.area_name.error} </span>





                 <br/>
                 <br/>
                {$edit_Area.deleteArea.html}
                {$edit_Area.abbrechenArea.html}
                {$edit_Area.updateArea.html}

		</form>
		
</div>

</fieldset>