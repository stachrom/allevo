<?php

$email  = array_key_exists('email', $_GET)   ? (string) trim($_GET['email']) : false ;
$add_attendee  = array_key_exists('add_attendee', $_GET)   ? (string) trim($_GET['add_attendee']) : false ;


if( $add_attendee == "Teilnehmen"  and $email != false ){

	if(!filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		$email_error ="<span style=\"color:red;\">Keine gültige E-Mail-Adresse</span>";
	}elseif( !ereg("^[[:space:][a-zA-Z0-9_.äöüèéàÜÖÄ]{2,50}$", $_GET['name'])){
		$name_error = "<span style=\"color:red;\">Kein gültiger Name</span>"; 
	}else{
	
		$http_client = new Horde_Http_Client($rpc_options);	
	
		try {
			
		$results_before_update = Horde_Rpc::request(
			'jsonrpc',
			$GLOBALS['rpc_endpoint'],
			'calendar.eventFromUID',
			$GLOBALS['http_client'],
			array('uid'    => $eventUID, 
				  'remote' => true)
			);
		
		}catch (Exception $e) {	

		//echo 'Caught exception: ',  $e->getMessage(), "\n";

		}

		$event = $results_before_update->result->json;
	
	
		if($event){

$veventText ="
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
DTSTART:".$event->s."
DTEND:".$event->e."
UID:".$eventUID."
X-ATTENDEE;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;CN=".$_GET['name'].":mailto:".$email."
END:VEVENT
END:VCALENDAR";


			$rpc_methode_replace  = 'calendar.replace';
			
			$rpc_parameters_repalce = array( 
					'uid' => $eventUID,
					'content' =>  $veventText,
					'contentType'  => 'text/x-vcalendar'	
			 );


			try {
				$result = Horde_Rpc::request(
					'jsonrpc',
					$GLOBALS['rpc_endpoint'],
					$GLOBALS['rpc_methode_replace'],
					$GLOBALS['http_client'],
					$GLOBALS['rpc_parameters_repalce']
					);
				
			}catch (Exception $e) {	
				echo 'Caught exception: ',  $e->getMessage(), "\n";
			}

		}

		header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?eventUID=".$eventUID."&email=".$email."",TRUE,301);

	}
}


function get_kronolith_event($eventUID = false){

	global $smarty;
	global $email;
	global $_allevo_config;
	global $date;
	global $email_error;
	global $name_error;

	// Horde Kronolith API Call --> calendar/eventFromUID
	$rpc_parameters = array(
			'uid' => $eventUID,
			'remote' => true
	);

	try {
	// Process the request
	$http_client = new Horde_Http_Client($GLOBALS['rpc_options']);

	$result = Horde_Rpc::request(
		'jsonrpc',
		$GLOBALS['rpc_endpoint'],
		'calendar.eventFromUID',
		$http_client,
		$rpc_parameters
		);

	}catch (Exception $e) {	

	// wenn der event gelöscht oder nicht auffindbar ist, dann permanent redirect auf index.php
	//echo 'Caught exception: ',  $e->getMessage(), "\n";
	
	header("Location: http://".$_SERVER['HTTP_HOST']."/index.php",TRUE,301);


	}


	
	if (!is_a($result->result, 'PEAR_Error') and is_object($result->result) ){
	  
		 
		$event    = $result->result->json;
		$eventUid = $result->result->uid;	 
	 	$start = strtotime($event->s);
		$end =   strtotime($event->e);
		$date =  strtotime($date.'T'.$event->st);

		$smarty->assign('kronololith_event_title', $event->title, true);

// fill php buffer. --> $kronolith_event_buffer
ob_start();	 
	 echo'
	 <article >
	 <div class="content">
    <header id="event_header">
  
					<h1 style="display:inline" >'. $event->t .'</h1>
					<span style="float:right; margin:2px 0px 0px 5px;">'.strftime('%A %d. %B %H:%M %Y', $date).'</span><br>
					<span style="float:right; font-size:0.75em; margin:0px;">
					<a href="http://horde4.finishers.ch/kronolith/event.php?view=ExportEvent&eventID='.$event->id.'&calendar='.$event->c.'&timestamp='.$start.'&event=ExportEvent " >Termin exportieren</a>
					</span>

	</header>	
			<table class="event">	
			<tr>
				<td class="event_title">Veranstaltungsort:</td>
				<td class="event_title">Dauer:</td>
				<td class="event_title">Kategorie:</td>
				<td class="event_title">Organisator:</td>
          </tr>
			 <tr>
				<td>'.$event->l.'</td>
				<td>'.strftime('%H:%M ', $start).' - '.strftime('%H:%M', $end).' Uhr <br>'.secondsToTime(strtotime($event->et) - strtotime($event->st)).' </td>
				<td><ul>';
				
					foreach($event->tg as $key => $tags){
						echo'<li class="tag">'.$tags. '</li>';
					}

				echo'</ul></td>
				<td>';
				echo $event->cid;
				
				
					
			echo'</td>
          </tr>
			 <tr>
				<td colspan="4" class="event_title"><strong>Beschreibung:</strong> </td>
			</tr>
			<tr>
				<td colspan="4">';

				$filter = Horde_Text_Filter::filter($event->d, 'text2html', array('parselevel' => Horde_Text_Filter_Text2html::MICRO, 'class' => 'text') );

				echo $filter;

	echo'<br><br></td></tr>';
		
	if($event->u){
		echo '<tr>
				<td colspan="4" class="event_title"><strong>Link:</strong> <a href="'.$event->u.'">'.$event->u.'</a> </td>
			</tr>';
	}
		
	echo'<tr>
			<td colspan="4" style="padding:0;">
			<table class="attende">
			<tr>
			   <th></th>
		    	<th class="event_title">Teilnehmer</th>
				<th class="event_title">Teilnahme</th>
				<th class="event_title">Antwort</th>
			</tr>';
if($event->at){

$i=1;						
	foreach($event->at  as $key3 => $value3){
	
		if($value3->e){

			if($email === $value3->e){

				$css_row_mark='style="background:#CBFBC6;"';

			}else{
			
				$css_row_mark='';
			
			}
		
	echo"
			
		<tr>
		   <td $css_row_mark >".$i++.". </td>
			<td $css_row_mark >".$value3->l ."</td>
			<td $css_row_mark >";
			switch ($value3->a) {
				case 1:
					echo "notwendig";
					break;
				case 2:
					echo "freiwillig";
					break;
				case 3:
					echo "keine";
					break;
			}
		echo"
			</td>
			<td $css_row_mark >";
			switch ($value3->r) {
				case 1:
					echo "keine";
					break;
				case 2:
					echo "bestätigt";
					break;
				case 3:
					echo "abwesend";
					break;
				case 4:
					echo "unter Vorbehalt";
					break;
			}
		echo'
		</td>
	</tr>';
		}
	}// ende foreach
} // end if no attendee
	echo'</table>
		</td>
	</tr>

	<tr>
	<td colspan="4" >';

	if(!$event->r and strtotime($event->s) >= strtotime(date("YmdHi")) ){
		echo'
			<fieldset > 
			<legend> <b> Eintragen:</b></legend>
				<form action="'.$_SERVER['PHP_SELF'].'" method="get" >
					<input name="eventUID" type="hidden" value="'.$eventUID.'">
						<label for="attende_name">Name:<em>*</em>'.$name_error.'</label>
					<input name="name" id="attende_name" type="text" value="'.$_GET['name'].'" autofocus="autofocus" placeholder="roman stachura"  required >
						<label for="attende_mail">Email:<em>*</em>'.$email_error.'</label>
					<input name="email"  id="attende_mail" type="email" placeholder="info@finishers.ch"  required >					
					<input type="submit" value="Teilnehmen"  name="add_attendee">
					<p><em>*</em>E-Mail und Name sind zwingend. </p>
				</form>
			</fieldset>';
	}else{			
		echo'
			<div class="wichtig"> 
				<b>Anmeldung ist geschlossen.</b> 
			</div>';					
	}
	echo'</td>
		</tr>
		</table>
	</div>
</article>';


	 $kronolith_event_buffer = ob_get_contents();
	 ob_end_clean();
	}

     $smarty->assign('kronolith_event', $kronolith_event_buffer, true);

}


?>