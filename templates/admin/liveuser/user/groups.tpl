<h2>Neue Groupe erstellen</h2>



<h2>Gruppe Löschen</h2>



<h2>Gruppe Bearbeiten</h2>

<form {$Add_Group.attributes}>	

  <div>		
{$Add_Group.group_name.html}
		
				 
{if $Add_Group.group_name.error}
{$Add_Group.group_name.error}
{/if}
		
		
{$Add_Group.hidden}
{$Add_Group.LiveUseraddGroup.html}
{$Add_Group.gruppen.html}
{$Add_Group.hidden}
{$Add_Group.LiveUserdeleteGroup.html}

  </div>
  </form>	
  

