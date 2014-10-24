<?php
// Set params array
$params = $_REQUEST;
global $prli_link_meta;

if(isset($params['action']) and $params['action'] == 'export')
  require_once(dirname(__FILE__) . '/../../../../wp-load.php');

require_once 'prlipro-config.php';

if(empty($params['action']))
  require_once 'classes/views/prlipro-import-export/form.php';
else if($params['action'] == 'import')
{
  $filename = $_FILES['importedfile']['tmp_name'];
  $contents = file_get_contents($filename);
  $headers = array();
  $csvdata = array();
  $row = -1;
  $handle = fopen($filename, "r");
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
  {
    $num = count($data);
    for ($c=0; $c < $num; $c++)
    {
      if($row < 0)
        $headers[] = $data[$c];
      else if($row >= 0)
        $csvdata[$row][$headers[$c]] = $data[$c];
    }
    $row++;
  }
  fclose($handle);

  $total_row_count = count($csvdata);

  $successful_update_count = 0;
  $successful_create_count = 0;
  $no_action_taken_count   = 0;

  $creation_errors = array();
  $update_errors = array();

  foreach($csvdata as $csvrow)
  {
    if(!empty($csvrow['id']))
    {
      $record = $prli_link->get_link_min($csvrow['id'], ARRAY_A);

      if($record)
      {
        $update_record   = false; // assume there aren't any changes
        $update_keywords = false; // assume there aren't any changes
        foreach($csvrow as $csvkey => $csvval)
        {
          // We'll get to the keywords in a sec for now ignore them
          if($csvkey == 'keywords')
              continue;
          
          // If there's a change, flag for update
          if($csvval != $record[$csvkey])
          {
            $update_record = true;
            break;
          }
        }

        // Add Keywords
        if( $prlipro_options->keyword_replacement_is_on )
        {
          $keyword_str = $prli_keyword->getTextByLinkId( $csvrow['id'] );
          $keywords = explode( ",", $keyword_str );
          $new_keywords = explode(",",$csvrow['keywords']);

          if(count($keywords) == count($new_keywords))
          {
            for($i=0;$i < count($keywords);$i++)
              $keywords[$i] = trim($keywords[$i]);

            sort($keywords);

            for($i=0;$i < count($new_keywords);$i++)
              $new_keywords[$i] = trim($new_keywords[$i]);

            sort($new_keywords);

            for($i=0; $i < count($new_keywords); $i++)
            {
              if($keywords[$i] != $new_keywords[$i])
              {
                $update_keywords = true;
                break;
              }
            }
          }
          else
            $update_keywords = true;
        }

        $record_updated = false;
        if($update_record)
        {
          if( $record_updated = prli_update_pretty_link( $csvrow['id'],
                                                         $csvrow['url'],
                                                         $csvrow['slug'],
                                                         $csvrow['name'],
                                                         $csvrow['description'],
                                                         $csvrow['group_id'],
                                                         $csvrow['track_me'],
                                                         $csvrow['nofollow'],
                                                         $csvrow['redirect_type'],
                                                         $csvrow['param_forwarding'],
                                                         $csvrow['param_struct'] ) )
          {
            $successful_update_count++;
            $prli_link_meta->update_link_meta($newid, 'delay', (isset($csvrow['delay']))?(int)$csvrow['delay']:0);
          }
          else
            $update_errors[] = array('id' => $csvrow['id'], 'errors' => $prli_error_messages);
        }

        if($update_keywords)
        {
          // We don't want to update the keywords if there was an error
          // in the record's update that is, if the record was updated
          if($record_updated or !$update_record)
          {
            $prli_keyword->updateLinkKeywords($csvrow['id'], stripslashes($csvrow['keywords']));

            // If the record was never updated then increment the count
            if(!$update_record)
              $successful_update_count++;
          }
        }

        if(!$update_record and !$update_keywords)
          $no_action_taken_count++;
      }
    }
    else
    {
      if( $newid = prli_create_pretty_link(  $csvrow['url'],
                                             $csvrow['slug'],
                                             $csvrow['name'],
                                             $csvrow['description'],
                                             $csvrow['group_id'],
                                             $csvrow['track_me'],
                                             $csvrow['nofollow'],
                                             $csvrow['redirect_type'],
                                             $csvrow['param_forwarding'],
                                             $csvrow['param_struct'] ) )
      {
        $successful_create_count++;
        $prli_link_meta->update_link_meta($newid, 'delay', (isset($csvrow['delay']))?(int)$csvrow['delay']:0);

        if( $prlipro_options->keyword_replacement_is_on and !empty($csvrow['keywords']) )
          $prli_keyword->updateLinkKeywords($newid, stripslashes($csvrow['keywords']));
      }
      else
        $creation_errors[] = array('slug' => $csvrow['slug'], 'errors' => $prli_error_messages);
    }

    $prli_error_messages = array();
  }
  require_once 'classes/views/prlipro-import-export/import.php';
}
else if($params['action'] == 'export')
{
  $links = $wpdb->get_results("SELECT id,url,slug,name,description,group_id,redirect_type,track_me,param_forwarding,param_struct FROM {$prli_link->table_name}", ARRAY_A);

  // Add Keywords
  if( $prlipro_options->keyword_replacement_is_on )
  {
    for($i=0; $i < count($links); $i++)
      $links[$i]['keywords'] = $prli_keyword->getTextByLinkId( $links[$i]['id'] );
  }

  require_once 'classes/views/prlipro-import-export/export.php';
}
