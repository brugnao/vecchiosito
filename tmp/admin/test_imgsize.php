<?php 
$size = getimagesize("http://www.example.com/gifs/logo.gif");

// if the file name has space in it, encode it properly
$size = getimagesize("http://www.example.com/gifs/lo%20go.gif");

?> 