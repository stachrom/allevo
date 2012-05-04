// multi-uploader implementation for finishers.ch main upload
YUI.add('multi-uploader', function (Y) {

Y.on('domready', function () {

var uploader,
    selectedFiles = {},
	path = {};


Y.log( "is going to be executed....");
 
var swfURL =  "/request/uploader.swf";


if (Y.UA.ie >= 6) {
	swfURL += "?t=" + Y.guid();
}

uploader = new Y.Uploader({boundingBox:"#uploaderOverlay", swfURL: swfURL});	

uploader.on("uploaderReady", setupUploader);
uploader.on("fileselect", fileSelect);
uploader.on("uploadprogress", updateProgress);
uploader.on("uploadcomplete", uploadComplete);
uploader.on("uploadcompletedata", uploadCompleteData);





function setupUploader (event) {
	uploader.set("multiFiles", true);
	uploader.set("simLimit", 6);
	uploader.set("log", true);
	
	var fileFilters = new Array(
				{description:"Images", extensions:"*.jpg;*.jpeg;*.png;*.gif"},
				{description:"Videos", extensions:"*.avi;*.mov;*.mpg"}
					); 
	
    uploader.set("fileFilters", fileFilters); 
}

function fileSelect (event) {



	var fileData = event.fileList;	
    
	for (var key in fileData) {
	        if (!selectedFiles[fileData[key].id]) {
			   var output = "<tr><td>" + fileData[key].name + "</td><td>" + 
			                fileData[key].size + "</td><td><div id='div_" + 
			                fileData[key].id + "' class='progressbars'></div></td></tr>";
			   Y.one("#filenames tbody").append(output);
			  
			   var progressBar = new Y.ProgressBar({id:"pb_" + fileData[key].id, layout : '<div class="{labelClass}"></div><div class="{sliderClass}"></div>'});
			       progressBar.render("#div_" + fileData[key].id);
			       progressBar.set("progress", 0);
               
               selectedFiles[fileData[key].id] = true;
			}
	}

}

function updateProgress (event) {
	var pb = Y.Widget.getByNode("#pb_" + event.id);
	pb.set("progress", Math.round(100 * event.bytesLoaded / event.bytesTotal));
	
	Y.log("progress: "+ Math.round(100 * event.bytesLoaded / event.bytesTotal));
}

function uploadComplete (event) {

	var pb = Y.Widget.getByNode("#pb_" + event.id);
	pb.set("progress", 100);

}

function uploadCompleteData (event) {

	uploader.clearFileList();

   
}


function uploadFiles (event) {
	uploader.uploadAll("/request/upload.php", "POST", {cookie: "PHPSESSID=" + Y.Cookie.get("PHPSESSID"), pfad: path} );
}

Y.one("#uploadFilesLink").on("click", uploadFiles);


});
}, '1.0.0', {requires: [
   'uploader', 'gallery-progress-bar', 'cookie'
]});