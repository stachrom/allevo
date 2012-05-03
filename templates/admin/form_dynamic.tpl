
{$form.javascript}


<div id="dynamic-form"> was auch immer ...</div >
{if $form.errors}
<div class="formErrorteaser">

<b>Fehlermeldung:</b>
<ul>

{foreach key=name item=error from=$form.errors}
    <li ><a href="#{$name}">{$error}</a></li>
{/foreach}

</ul>
</div>
{/if}

<div id="message">

<form{$form.attributes}>
	{$form.hidden} 
    
    {foreach item=sec key=i from=$form.sections}
      <fieldset>
    		<legend>{$sec.header}</legend> 
         	<ol>    
        		{foreach item=element from=$sec.elements}

               {if $element.type eq "submit" or $element.type eq "reset"}
                  {if not $form.frozen}
                     <li>{$element.html}</li>
                  {/if}
                <!-- this is a group --> 
                
                
                
                
                
                {elseif $element.style eq "group_horizontal"}
                
                   		<li>
                           <fieldset> 
                              <legend>{$element.label} {if $element.required}<em>*</em>{/if}</legend>
                                {foreach key=gkey item=gitem from=$element.elements} 
                                   <label for="$gitem.label" class="grouphorizontal{if $element.error}formError{/if}" > {$gitem.html} {$gitem.label} {if $gitem.required} <em>*</em> {/if}</label>
                                      {if $element.separator}
                                        {cycle values=$element.separator}
                                      {/if}
                                {/foreach}
                           </fieldset>           
                        </li>
      
                {elseif $element.type eq "group"}
                        <li>
                           <fieldset> 
                              <legend>{$element.label} {if $element.required}<em>*</em>{/if}</legend>
                                {foreach key=gkey item=gitem from=$element.elements}
                                   <label for="$gitem.label" {if $element.error}class="formError"{/if}>{$gitem.html} {$gitem.label}
                                   {if $gitem.required}<em>*</em>{/if}
                                   </label>
                                      {if $element.separator}
                                        {cycle values=$element.separator}
                                      {/if}
                                {/foreach}
                           </fieldset>           
                        </li>
                   <!-- normal elements -->  
                  
                       
               	{else}
                     <li>
                        <label for="{$element.name}">
                           {if $element.error}<a name="{$element.name}" class="formError">{$element.label}</a>
                           {else}
                           {$element.label} {if $element.required}<em>*</em>{/if}
                           {/if}
                        </label>
                       {$element.html}
                       {if $element.label_note}<p>{$element.label_note}</p>  {/if}
                     </li>
              {/if}      
        {/foreach}
        </ol>
      </fieldset>   
  {/foreach}
    
    {if $form.requirednote and not $form.frozen}


       {$form.requirednote}

    {/if}

</form> </div> 
