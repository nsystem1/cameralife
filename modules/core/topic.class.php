<?php
  # the class for getting and using topics
  
class Topic extends Search
{
  var $name;

  function Topic($name)
  {
    global $cameralife;

    $this->name = $name;

    Search::Search('');
    $this->mySearchAlbumCondition = "topic = '".$this->name."'";
  }

  function GetSmallIcon()
  {
    return array('href'=>'topic.php&#63;name='.$this->name, 
                 'name'=>$this->name,
                 'image'=>'small-topic');
  }
}

?>
