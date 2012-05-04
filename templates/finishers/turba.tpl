<div class="yui3-g">
 		<div class="yui3-u-1-5" > 
			<div class="content">
         <label for="ac-input">Filter:</label><br>
  			<input id="ac-input" type="text">
         <br>
         <output name="anzahlmitglieder" for="member" id="selectionOutput"></output> Mitglieder
         <br>
         <br>
         
         <ul  class="turbafilter">
        <li><strong>Namen:</strong>
               <ul>
                  <li>Vorname</li> 
                  <li>Nachname </li> 
               </ul>
        </li>
         <li><strong>Funktion:</strong>
               <ul>
                  <li>vorstand</li> 
                  <li>beisitzer </li> 
                  <li>trainer</li> 
               </ul>
        </li>
        <li><strong>Anrede:</strong>
             <ul>
               <li>herr</li>  
               <li>frau</li>
               <li>familie</li>   
        		</ul>
        </li>
        </ul>
        
        <ul class="turbafilter">
         <li><strong>Mitgliederstatus:</strong>
             <ul>
               <li>passiv</li>  
               <li>aktiv</li> 
               <li>austritt</li>  
               <li>eintritt</li>  
               <li>gönner</li>  
               <li>ehrenmitglied</li>  
               <li>walker</li> 
         		<li> junior</li>   
        		</ul>
        </li>
       <li><strong>Wohnort:</strong>
            <ul>
               <li> stadt</li>   
        		</ul>
        </li>
      </ul>
      
      
   {literal}   
	<script>
            
            YUI().use('autocomplete-base', 'autocomplete-filters', 'dump', function (Y) {
            
            // Create a custom TurbaFilter class that extends AutoCompleteBase.
              var TurbaFilter = Y.Base.create('addressBookFilter', Y.Base, [Y.AutoCompleteBase], {
                initializer: function () {
                  this._bindUIACBase();
                  this._syncUIACBase();
                }
              }),
            
              filter = new TurbaFilter({
                inputNode: '#ac-input',
                minQueryLength: 0,
                queryDelay: 0,
            
                
                source: (function () {
                  var results = [];
            
                  Y.all('#addressBook > .contact').each(function (node) {
                    results.push({
                      node: node,
                      tags: node.getAttribute('data-tags')
                    });
                  });
            
                  return results;
                }()), 
                resultTextLocator: 'tags',
                resultFilters: 'phraseMatch'
              });
            
              filter.on('results', function (e) {
                Y.all('#addressBook > .contact').addClass('hidden');
            
                  var i = 0;
                  var selectionOutput = Y.one('#selectionOutput');
            
                Y.Array.each(e.results, function (result) {
                  selectionOutput.set('value', i=i+1);
                  result.raw.node.removeClass('hidden');
                });
              });
            
            });
            
            </script>
	{/literal} 
           
</div>
 		</div>
        
 		<div class="yui3-u-4-5"> 
			<div class="content" id="single-content">
                  {$turba_contacts}   
			</div>
 		</div>
</div>