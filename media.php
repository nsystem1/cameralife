<?php
  # 
  # Displays a photo
  # The file is got from the photostore "the hard" way and printed out
  # This file makes asset security possible since the user does not directly access the photos.
  #
  # Required GET variables: id, format (one of 'photo', 'thumbnail', 'scaled'), ver (mtime)
  #

  $features=array('database','security','imageprocessing', 'photostore');
  require "main.inc";

  $photo = new Photo($_GET['id']);
  $format = $_GET['format'];
  if (!is_numeric($_GET['ver'])) $cameralife->Error('Required number ver missing! Expected a number, got: '.htmlentities($_GET['ver']));
  $extension = $photo->extension;

  if (!$cameralife->Security->authorize('admin_file'))
  {
    if ($photo->Get('status')==1) $reason = "deleted";
    elseif ($photo->Get('status')==2) $reason = "marked as private";
    elseif ($photo->Get('status')==3) $reason = "uploaded but not revied";
    elseif ($photo->Get('status')==!0) $reason = "marked non-public";
    if ($reason) $cameralife->Error("Photo access denied: $reason");
  }

  if ($format == 'photo')
    list($file, $temp, $mtime) = $cameralife->PhotoStore->GetFile($photo, $format);
  elseif ($format == 'scaled')
    list($file, $temp, $mtime) = $cameralife->PhotoStore->GetFile($photo, $format);
  elseif ($format == 'thumbnail')
    list($file, $temp, $mtime) = $cameralife->PhotoStore->GetFile($photo, $format);
  else
    $cameralife->Error('Bad format parameter');

  if ($extension == 'jpg' || $extension == 'jpeg')
    header('Content-type: image/jpeg');
  elseif ($extension == 'png')
    header('Content-type: image/png');
  else
    $cameralife->Error('Unknown file type');

  header('Content-Disposition: inline; filename="'.htmlentities($photo->Get('description')).'.'.$extension.'";');
# header('Cache-Control: '.($photo['status'] > 0) ? 'private' : 'public');
  header('Content-Length: '.filesize($file));

  header("Date: ".gmdate("D, d M Y H:i:s", filemtime($file))." GMT");
  header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($file))." GMT");
  header("Expires: ".gmdate("D, d M Y H:i:s", time() + 2592000)." GMT"); // One month

  readfile($file);
  if ($temp) unlink($file);
?>
