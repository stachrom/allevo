<?php
   include('../set_env.php');

   include('fusiontable/contact.php');

   // If the request is a post, insert the data into the table
   if($_SERVER['REQUEST_METHOD'] == 'POST') {
   // Insert form data into table
      $insertresults = $ftclient->query(
         SQLBuilder::insert(
            $tableid, 
            array(
               'Name'=> trim($_POST['name']),
               'Sportart'=> trim($_POST['sportart']),
               'Vorname'=> trim($_POST['vorname']),
               'Jahrgang'=> date("Y") - (int) trim($_POST['alter']),
               'Email'=> trim($_POST['email']),
               'URL'=> trim($_POST['url']),
               'Wettkampfdatum'=> trim($_POST['wettkampfdatum']),
               'Wettkampftitel'=> trim($_POST['wettkampftitel']),
               'Wettkaempfe' => trim($_POST['wettkaempfe']),
               'Preisgeld'=> trim($_POST['preisgeld']),
               'Distanz'=> (float)str_replace(',', '.', $_POST['distanz']),
               'Rangoverall'=> trim($_POST['rangoverall']),
               'Rangkategorie'=> trim($_POST['rangkategorie']),
               'Teilnehmerkategorie'=> trim($_POST['teilnehmerkategorie']),
               'Teilnehmertotal'=> trim($_POST['teilnehmertotal']),
               'Time'=> ($_POST['h']*60*60 + $_POST['min']*60 + $_POST['sec']), //time in sec
               'Kategorie'=> trim($_POST['kategorie']),
               'Location' => trim($_POST['location']),
               'Timestamp' => time()
            )
         )
      );

      $insertresults = explode("\n", $insertresults);
     
      $host  = $_SERVER['HTTP_HOST'];
      $uri   = $_SERVER['PHP_SELF'];
      $extra = $_SERVER["QUERY_STRING"];
      $url = parse_url("http://$host$uri?$extra");
      
      if( in_array("rowid", $insertresults) ){
       
         $athlet_data = array(
            'name'=>$_POST['name'],
            'vorname'=>$_POST['vorname'],
            'email'=>$_POST['email'],
            'alter'=>$_POST['alter'],
            'success'=>1
         );
         
      }else{

         $athlet_data = array(
            'name'=>$_POST['name'],
            'vorname'=>$_POST['vorname'],
            'email'=>$_POST['email'],
            'alter'=>$_POST['alter'],
            'failure'=> 1,
            'message'=> strip_tags($insertresults['5'])
         );
      }

    
      if($_SERVER["QUERY_STRING"]){
         $_query_string = http_build_query( array_merge($_GET, $athlet_data) );
      }else{
         $_query_string = http_build_query( $athlet_data );
      }

      header("Location: $url[scheme]://$url[host]/index.php?$_query_string");
   }
?>