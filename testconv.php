<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<HEAD>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Mariusz Jackowski z5</title>
</HEAD>
<body>
<?php

use CloudConvert\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;

$cloudconvert = new CloudConvert(['api_key' => 'API_KEY']);

$job = (new Job())
   ->addTask(
       (new Task('convert', 'task-1'))
         ->set('input_format', 'avi')
         ->set('output_format', 'mp4')
         ->set('engine', 'ffmpeg')
         ->set('input', ["export-1"])
         ->set('video_codec', 'x264')
         ->set('crf', 23)
         ->set('preset', 'medium')
         ->set('subtitles_mode', 'none')
         ->set('audio_codec', 'aac')
         ->set('audio_bitrate', 128)
         ->set('engine_version', '4.4.1')
         ->set('filename', 'exapmleOutputName')
     ); 

$cloudconvert->jobs()->create($job);
?>
</body>
</html>