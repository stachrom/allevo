
<form id="anmeldeformular" action="request/upload.php" method="post" >
<div class="yui3-g">

 	<div class="yui3-u-1-3" > 
		<div class="content">
			<h2>Adresse</h2>
			<label for="name">Name:</label>
				<input   name="namen" id="namen" type="text" required > <br>
			<label for="vorname">Vorname:</label>	
				<input   name="vornamen" id="vornamen" type="text" required > <br>                          
			<label for="name">Strasse:</label>
				<input   name="strasse" id="strasse" type="text" required > <br> 
			<label for="plz">PLZ:</label>
				<input   name="plz" id="plz" type="number" min="1001" max="9999" maxlength="4" required >  <br>
			<label for="ort">Ort:</label>
				<input   name="ort" id="ort" type="text" required >  <br> <br>
			<h2>Foto</h2>
			<div id="selectFilesButtonContainer"></div>				
		</div>
	</div>
		
 	<div class="yui3-u-1-3"> 
		<div class="content" id="single-content">
			<h2>Kontakt</h2>
			<input name="action" type="hidden" value="sendmail"> 

			<label for="telefon[telm]">Handy:</label>
				<input  name="telefon[telm]" id="telefon[telm]" type="tel"><br>
			<label for="name">Tel Privat</label>
				<input name="telefon[telp]"id="telefon[telp]" type="text"><br>
			<label for="name">Tel Geschäft</label>	
				<input  name="telefon[telg]" type="text"><br>
			<label for="name">Beruf</label>		
				<input name="beruf" id="beruf" type="text"><br>
			<label for="name">Email</label>	
				<input  name="email" type="email" required > <br><br>
				
				
			<h2>Geburtsdatum</h2>	
	<select name="geburtsdatum[d]">
	<option value="1">01</option>
	<option value="2">02</option>
	<option value="3">03</option>
	<option value="4">04</option>
	<option value="5">05</option>
	<option value="6">06</option>
	<option value="7">07</option>
	<option value="8">08</option>
	<option value="9">09</option>
	<option value="10">10</option>
	<option value="11">11</option>
	<option value="12">12</option>
	<option value="13">13</option>
	<option value="14">14</option>
	<option value="15">15</option>
	<option value="16">16</option>
	<option value="17">17</option>
	<option value="18">18</option>
	<option value="19">19</option>
	<option value="20">20</option>
	<option value="21">21</option>
	<option value="22">22</option>
	<option value="23">23</option>
	<option value="24">24</option>
	<option value="25">25</option>
	<option value="26">26</option>
	<option value="27">27</option>
	<option value="28">28</option>
	<option value="29">29</option>
	<option value="30">30</option>
	<option value="31">31</option>
</select>-<select name="geburtsdatum[F]">
	<option value="1">Januar</option>
	<option value="2">Februar</option>
	<option value="3">März</option>
	<option value="4">April</option>
	<option value="5">Mai</option>
	<option value="6">Juni</option>
	<option value="7">Juli</option>
	<option value="8">August</option>
	<option value="9">September</option>
	<option value="10">Oktober</option>
	<option value="11">November</option>
	<option value="12">Dezember</option>
</select>-<select name="geburtsdatum[Y]">
	<option value="1930">1930</option>
	<option value="1931">1931</option>
	<option value="1932">1932</option>
	<option value="1933">1933</option>
	<option value="1934">1934</option>
	<option value="1935">1935</option>
	<option value="1936">1936</option>
	<option value="1937">1937</option>
	<option value="1938">1938</option>
	<option value="1939">1939</option>
	<option value="1940">1940</option>
	<option value="1941">1941</option>
	<option value="1942">1942</option>
	<option value="1943">1943</option>
	<option value="1944">1944</option>
	<option value="1945">1945</option>
	<option value="1946">1946</option>
	<option value="1947">1947</option>
	<option value="1948">1948</option>
	<option value="1949">1949</option>
	<option value="1950">1950</option>
	<option value="1951">1951</option>
	<option value="1952">1952</option>
	<option value="1953">1953</option>
	<option value="1954">1954</option>
	<option value="1955">1955</option>
	<option value="1956">1956</option>
	<option value="1957">1957</option>
	<option value="1958">1958</option>
	<option value="1959">1959</option>
	<option value="1960">1960</option>
	<option value="1961">1961</option>
	<option value="1962">1962</option>
	<option value="1963">1963</option>
	<option value="1964">1964</option>
	<option value="1965">1965</option>
	<option value="1966">1966</option>
	<option value="1967">1967</option>
	<option value="1968">1968</option>
	<option value="1969">1969</option>
	<option value="1970">1970</option>
	<option value="1971">1971</option>
	<option value="1972">1972</option>
	<option value="1973">1973</option>
	<option value="1974">1974</option>
	<option value="1975">1975</option>
	<option value="1976">1976</option>
	<option value="1977">1977</option>
	<option value="1978">1978</option>
	<option value="1979">1979</option>
	<option value="1980">1980</option>
	<option value="1981">1981</option>
	<option value="1982">1982</option>
	<option value="1983">1983</option>
	<option value="1984">1984</option>
	<option value="1985">1985</option>
	<option value="1986">1986</option>
	<option value="1987">1987</option>
	<option value="1988">1988</option>
	<option value="1989">1989</option>
	<option value="1990">1990</option>
	<option value="1991">1991</option>
	<option value="1992">1992</option>
	<option value="1993">1993</option>
	<option value="1994">1994</option>
	<option value="1995">1995</option>
	<option value="1996">1996</option>
	<option value="1997">1997</option>
	<option value="1998">1998</option>
	<option value="1999">1999</option>
	<option value="2000">2000</option>
	<option value="2001">2001</option>
	<option value="2002">2002</option>
	<option value="2003">2003</option>
	<option value="2004">2004</option>
	<option value="2005">2005</option>
	<option value="2006">2006</option>
	<option value="2007">2007</option>
	<option value="2008">2008</option>
	<option value="2009">2009</option>
	<option value="2010">2010</option>
	<option value="2011">2011</option>
	<option value="2012">2012</option>
</select>	
		</div>
 	</div>
	
	
	<div class="yui3-u-1-3" > 
		<div class="content">

		
			<h2>Mitgliedschaft</h2>
			
			<ul>                                                          
				<li><input value="P" type="radio" id="qf_daef66" name="mitgliedschaft"><label for="qf_daef66"> Passiv 40.- <br> </label>  </li>                         
				<li><input value="G" type="radio" id="qf_5f350a" name="mitgliedschaft"><label for="qf_5f350a"> Gönner <br> </label> </li>                          
				<li><input value="A" type="radio" id="qf_068cfe" name="mitgliedschaft" checked ><label for="qf_068cfe"> Aktiv 180.- <br> </label> </li>                          
				<li><input value="W" type="radio" id="qf_a84db9" name="mitgliedschaft"><label for="qf_a84db9"> Walker 115.- <br> </label> </li>                          
				<li><input value="F" type="radio" id="qf_b8331b" name="mitgliedschaft"><label for="qf_b8331b"> Familie 255.- *<br> </label></li>                           
				<li><input value="J" type="radio" id="qf_e93c9f" name="mitgliedschaft"><label for="qf_e93c9f"> Junioren 100.- <br> </label> </li> 
			</ul>	
			<div style="font-size: 80%;">*Familie mit Kinder bis 20 Jahren</div>

		</div>
	</div>
</div>




<div class="yui3-g">

 	<div class="yui3-u-2-3" > 
		<div class="content">
			<div id="filelist">
			  <table id="filenames">
				<thead>
				   <tr><th>File Name</th><th>File grösse </th><th>Prozent übertragen</th><th>Deine übertragenen Daten</th></tr>
				   <tr id="nofiles">
					<td colspan="4" id="ddmessage">
						<strong>No files selected.</strong>
					</td>
				   </tr>
				</thead>
				<tbody>
				</tbody>
			  </table>
<br>			  
			
			<h2>Bonus / Malus:</h2>
			<p>
				Für jeden geleisteten Helfereinsatz werden SFr. 40. - rückerstattet. Die Rückerstattung wird jeweils mit dem
				nächsten zu bezahlenden Vereinsbeitrag verrechnet. Falls ein Mitglied den Verein verlässt, verfallen allfällige Rückerstattungen.
				Der Maximale Betrag der Rückerstattung kann den Vereinsbeitrag nicht übersteigen. Als Helfereinsätze gelten die vom Vorstand
				definierten Anlässe, Ausnahmen kann der Vorstand bewilligen. Passive und Gönner sind von der Regelung ausgenommen.
			</p>

			  
			</div>
			
		

		</div>
	</div>
	
	<div class="yui3-u-1-3" > 
		<div id="content">
		

			<h2>Bemerkungen</h2>
			<textarea rows="5" style="width:100%" name="message"></textarea>
			<br>
			<p>
				<input name="kontakt_alle[all]" type="checkbox" value="1" id="qf_ff55d5">                            
				Ich möchte eine Kopie der Nachricht erhalten  
			</p>
			<br>			
		
			<div id="uploaderContainer">
				<div id="uploadFilesButtonContainer">
				  <button type="button" id="uploadFilesButton" 
						  class="yui3-button" style="width:250px; height:25px;">Daten Senden</button>
				</div> 
				<div id="overallProgress">
				</div>
			</div>
		</div> 
	</div>
	
	
	
</div>	
	
</form>

	
{literal}

<script>

YUI({

	"modules" : {
		   'gallery-form-values' : {
			 fullpath : 'http://finishers.stachura.ch/js/yui3-gallery/gallery-form-values.js',
			 requires : ['plugin','node-pluginhost','base-build']
					}
			}
}).use("uploader", "dump", 'gallery-form-values', "json-stringify",  function(Y) {

	Y.one("#overallProgress").set("text", "Uploader type: " + Y.Uploader.TYPE);

   if (Y.Uploader.TYPE != "none" && !Y.UA.ios) { 
       var uploader = new Y.Uploader({width: "250px", 
                                      height: "25px", 
                                      multipleFiles: true,
                                      swfURL: "request/yui3.5/flashuploader.swf?t=" + Math.random(),
                                      uploadURL: "request/upload.php",
                                      simLimit: 2
                                     });    
       var uploadDone = false;

       if (Y.Uploader.TYPE == "html5") {
          uploader.set("dragAndDropArea", "body");

          Y.one("#ddmessage").setContent("<strong>Drag and drop Foto auf die Seite.</strong>");   

          uploader.on(["dragenter", "dragover"], function (event) {
              var ddmessage = Y.one("#ddmessage");
              if (ddmessage) {
                ddmessage.setContent("<strong>Drag Files entdeckt, drop it!</strong>");
                ddmessage.addClass("yellowBackground");
              }
           });
    
           uploader.on(["dragleave", "drop"], function (event) {
              var ddmessage = Y.one("#ddmessage");
              if (ddmessage) {
                ddmessage.setContent("<strong>Drag and drop Foto auf die Seite.</strong>");
                ddmessage.removeClass("yellowBackground");
              }
           });
       }

       uploader.render("#selectFilesButtonContainer");

       uploader.after("fileselect", function (event) {

          var fileList = event.fileList;
          var fileTable = Y.one("#filenames tbody");
		Y.one("#overallProgress").set("text", "");		  
		  
          if (fileList.length > 0 && Y.one("#nofiles")) {
            Y.one("#nofiles").remove();
          }

          if (uploadDone) {
            uploadDone = false;
            fileTable.setContent("");
          }
          
          var perFileVars = {};
		  var merged;

        Y.each(fileList, function (fileInstance) {
		
            fileTable.append("<tr id='" + fileInstance.get("id") + "_row" + "'>" + 
                             "<td class='filename'>" + fileInstance.get("name") + "</td>" + 
                             "<td class='filesize'>" + fileInstance.get("size") + "</td>" + 
                             "<td class='percentdone'>Hasn't started yet</td>" +
                             "<td class='serverdata'>&nbsp;</td>"); 
							 
			perFileVars[fileInstance.get("id")] = {filename: fileInstance.get("name"), action: "sendmail"};

			
		});
	
        uploader.set("postVarsPerFile", Y.merge(uploader.get("postVarsPerFile"), perFileVars));

       });

       uploader.on("uploadprogress", function (event) {
            var fileRow = Y.one("#" + event.file.get("id") + "_row");
                fileRow.one(".percentdone").set("text", event.percentLoaded + "%");
       });

       uploader.on("uploadstart", function (event) {
	   
	   
	   
	   
            uploader.set("enabled", false);
            Y.one("#uploadFilesButton").addClass("yui3-button-disabled");
            Y.one("#uploadFilesButton").detach("click");
       });

       uploader.on("uploadcomplete", function (event) {
            var fileRow = Y.one("#" + event.file.get("id") + "_row");
                fileRow.one(".percentdone").set("text", "Finished!");
                fileRow.one(".serverdata").setContent(event.data);
       });

       uploader.on("totaluploadprogress", function (event) {
                Y.one("#overallProgress").setContent("Total uploaded: <strong>" + event.percentLoaded + "%" + "</strong>");

                if (event.bytesLoaded == event.bytesTotal) {
                     uploadDone = true;
                     uploader.set("enabled", true);
                     uploader.set("fileList", []);
                     Y.one("#uploadFilesButton").removeClass("yui3-button-disabled");
                     Y.one("#uploadFilesButton").on("click", function () {
						if (!uploadDone && uploader.get("fileList").length > 0) {
                             uploader.uploadAll();
                          }
                     });
                     Y.one("#overallProgress").set("text", "Alle Daten erfolgreich übertragen");
					 var formular = document.forms["anmeldeformular"];
					 formular.reset();
                     uploadDone = true;
                }
       });
	   
	    Y.on('domready', function () {
	
			  });

       Y.one("#uploadFilesButton").on("click", function () {
	   
	   
	   	Y.log('id  '+ Y.dump( uploader.get("fileList").length));
	   

		if (uploader.get("fileList")[0]) {
			var perFileVars2 = {};
			var frm = Y.one('#anmeldeformular');

			frm.plug(Y.Form.Values);
				
			var FormData = frm.values.getValues();
			var perFileVars = {};


			perFileVars2[uploader.get("fileList")[0].get("id")] = FormData;
			
			Y.log('formname '+ Y.dump(perFileVars2));

			Y.log('formname '+ Y.dump(uploader.get("postVarsPerFile")));

			merged = Y.merge(uploader.get("postVarsPerFile"), perFileVars2);
			
			Y.log('merged '+ Y.dump(merged));

			uploader.set("postVarsPerFile", Y.merge(uploader.get("postVarsPerFile"), merged));


			var formular = document.forms["anmeldeformular"];
			
			//formular.submit();
			var sendform = false;	
			
			for (var i = 0 ; i <=  formular.length ; i++){

				if( formular[i] != undefined && formular[i].checkValidity() == false){
					//Y.log('formname '+ Y.dump(formular[i]));
					//Y.log('validiti '+ Y.dump(formular[i].checkValidity()));
				}else if(formular[i] != undefined && formular[i].checkValidity() == true){
					
				}

			}
			
			if(formular.checkValidity()){
				 sendform = true;
			}else{
				Y.one("#overallProgress").setContent("<p class='alert'> Bitte füllen Sie die Roten Felder aus.</p>"); 
			}
			
		
		}else if( uploader.get("fileList").length == 0){
		
			Y.one("#overallProgress").append("<p class='alert'> Bitte fügen Sie ein Foto hinzu. </p> ");

		}

	

        if (!uploadDone && uploader.get("fileList").length > 0 && sendform == true) {
            uploader.uploadAll();
        }

	
    
 
		
		
		
       });       
   } 
   else {
       Y.one("#uploaderContainer").set("text", "Die Foto-upload Technologie wird auf dieser Plattform nicht unterstützt"); 
	   Y.one("#uploaderContainer").append("<br><input name='kontakt_alle[kontakt_formular]' value='Senden' type='submit'>");	
	   
   }
   


});

</script>

{/literal} 