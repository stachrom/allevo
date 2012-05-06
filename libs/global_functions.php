<?php
   // time funktion.
   function secondsToTime($seconds) {
      // extract hours
      $hours = floor($seconds / (60 * 60));

      // extract minutes
      $divisor_for_minutes = $seconds % (60 * 60);
      $minutes = floor($divisor_for_minutes / 60);

      // extract the remaining seconds
      $divisor_for_seconds = $divisor_for_minutes % 60;
      $seconds = ceil($divisor_for_seconds);

      // return the final array
      $obj = array(
         "h" => (int) $hours,
         "m" => (int) $minutes,
         "s" => (int) $seconds,
      );

      // 2 digit string:  leading 0 ? 
      foreach($obj as $key => $value){
         if($value < 10){
            $obj2Digits[$key] = (string)"0".$value;
         }else{
            $obj2Digits[$key] = (string) $value;
         }
      }

      // all day long? 
      if( $obj2Digits['h'] ==23 and $obj2Digits['m']== 59 ){
         $string = "ganztägig";
      }else{
         $string = $obj2Digits['h'].":".$obj2Digits['m'].":".$obj2Digits['s'];
      }

      return $string;
   }
   
   // read the img form the directory.
   function check_dir($dir_img){
      if (is_dir($dir_img)) {
          if ($dh = opendir($dir_img)) {
              while (($file = readdir($dh)) !== false) {
                $server_bilder[] = $file;
              }
              closedir($dh);
              return $server_bilder;
          }
      }
   }

   function unserialize_content($content=0, $single_content=false){

      global $server_bilder;
      global $mdb2;
      global $smarty;
      $tager = new Content_Tagger($mdb2);

      if (is_array($content)){

         foreach($content as $key => $value){

            $content[$key] = unserialize($value)  ? unserialize($value) : $value;

               if( $key == 'query_string' and $single_content == true){
               
                     if(!is_array($temp_array = unserialize($value))){
                         
                     }else{

                        if (array_key_exists('start', $temp_array)) {
                           $start = $temp_array['start'];
                        }else{
                           $start = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                        }
                        
                        if (array_key_exists('stop', $temp_array)){
                           $stop = $temp_array['stop'];
                        }else{
                           $stop = mktime(0, 0, 0, date("m"), date("d")+6, date("Y"));
                        }
                     
                        foreach(unserialize($value) as $k => $value){
                        
                           switch ($k) {
                              case "trainings":
                                 $tags = explode(" ", $value);
                                 $smarty->assign('calendar_tags', $tags , true);
                                 break;
                              case "eventUID":
                                 global $pattern;
                                 $eventUID  =  preg_match($pattern['eventUID'], trim($value))  ? trim($value)  : false;
                                 
                                 if($eventUID){
                                    get_kronolith_event($eventUID);
                                 }
                                 break;
                              case "turbaID":
                                 get_turba_contact($value);
                                 break;	  
                           }
                        }
                     }
                     
                     if ($single_content == true){
                     
                        $smarty -> assign('stopDate',  date("Y.m.d", $stop), true);
                        $smarty -> assign('startDate', date("Y.m.d", $start), true);
                        
                        $range = $stop - $start;
                        
                        do {
                           $calendar_dates[] = date("Y,m,d", $start+$range);
                           $range = $range - (60*60*24); 
                        } while ($range > 0);
                        
                           $calendar_dates[] = date("Y,m,d", $start);
                           $smarty -> assign('calendar_dates',  implode("/", $calendar_dates), true);
                     }
               }
               
               if( $key == 'gallery'){
                  if(!is_array(unserialize($value) )  ) {
                     $content['gallery'] = false ;
                  }else{
                     // content von db und filesystem abgleichen
                     $content['gallery'] = array_intersect($content['gallery'], $server_bilder);
                  }
               }
               if( $key == 'sidepictures'){ 
                  if(!is_array(unserialize($value))){
                     $content['sidepictures'] = false;
                  }else{
                     // content von db und filesystem abgleichen 
                     $content['sidepictures'] = array_intersect($content['sidepictures'], $server_bilder);
                  } 
               }
               if( $key == 'uuid'){
                  $query = "SELECT object_id FROM rampage_objects WHERE object_name ='".$value."'";
                         
                  if($objectId = $mdb2->queryOne($query)){
                     $args['offset'] = $offset;
                     $args['limit']  = $limit;
                     $args['q']      = "";
                     $args['objectId'] = $objectId;
                     
                     $content['tags'] = $tager->getTags($args);
                  }
               }	
            }
            return $content;
      }else{
         // there is no content. what shall we do?
      }
   }
?>