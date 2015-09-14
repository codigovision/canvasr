<?php

$debug = False; // True or False

if ($debug == True) echo '<h1>Debugging on</h1>';

// Make sure the file is valid and get contents
if (isset($_FILES['upload']) && is_uploaded_file($_FILES['upload']['tmp_name'])) {

    //DEBUG:
    if ($debug == True) {
        echo '<strong>Filename:</strong> ' . $_FILES['upload']['name'] . '<br>';
        echo '<strong>File Type:</strong> ' . $_FILES['upload']['type'] . '<br>';
        echo '<strong>Temp Name:</strong> ' . $_FILES['upload']['tmp_name'] . '<br>';
        echo '<strong>Error:</strong> ' . $_FILES['upload']['error'] . '<br>';
        echo '<strong>File Size:</strong> ' . $_FILES['upload']['size'] . '<br>';
        echo '<strong>Canvas Height:</strong> ' . $_POST['height'] . '&quot;<br>';
        echo '<strong>Canvas Width:</strong> ' . $_POST['width'] . '&quot;<br>';
        echo '<strong>Canvas Depth:</strong> ' . $_POST['depth'] . '&quot;<br>';
    }

    if ($_FILES['upload']['type'] !== 'image/jpeg') {

        echo 'File must be a jpg. Current file is: ' . $_FILES['upload']['type'];

    } elseif (!isset($_POST['height']) || !is_numeric($_POST['height'])) {

        echo 'You must enter a numeric height';

    } elseif (!isset($_POST['width']) || !is_numeric($_POST['width'])) {

        echo 'You must enter a numeric width';

    } elseif (!isset($_POST['depth']) || !is_numeric($_POST['depth'])) {

        echo 'You must enter a numeric depth';

    } else {

        // Process Image
        canvasr($_FILES['upload']['tmp_name'], $_POST['height'], $_POST['width'], $_POST['depth'], $debug);

    }

} else {
    echo 'No File Detected. Please go back and upload a jpg file.';
}

function canvasr($file, $height, $width, $depth, $debug) {

    $dpi = 300;

    $pxWidth = $width * $dpi;
    $pxHeight = $height * $dpi;
    $pxDepth = $depth * $dpi;

    $cWidth = $pxWidth + ($pxDepth * 2);
    $cHeight = $pxHeight + ($pxDepth * 2);

    $newImage = $_FILES['upload']['name'];

    // Scale and crop
    //exec("convert -units PixelsPerInch $file -density $dpi -resize '$pxWidth x $pxHeight ^' -gravity center -crop '$pxWidth x $pxHeight +0+0' '$newImage'");

    //Mirror Edges
    //exec("convert '$newImage' -virtual-pixel mirror -set option:distort:viewport '$cWidth x $cHeight -$pxDepth -$pxDepth' -distort SRT 0 +repage '$newImage'");

    //echo '<img src="'.$newImage.'" style="max-width:100%"/>';

    // Scale, crop and mirror edges
    $cmd = "convert -units PixelsPerInch $file -density $dpi -resize '$pxWidth x $pxHeight ^' -gravity center -crop '$pxWidth x $pxHeight +0+0' -virtual-pixel mirror -set option:distort:viewport '$cWidth x $cHeight -$pxDepth -$pxDepth' -distort SRT 0 +repage JPG:-";

    if ($debug !== True) {

        header('Pragma: public'); 	// required
        header('Expires: 0');		// no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($_FILES['upload']['tmp_name'])).' GMT');
        header('Cache-Control: private',false);
        header('Content-Type: '.$_FILES['upload']['type']);
        header('Content-Disposition: attachment; filename="'.basename($newImage).'"');
        header('Content-Transfer-Encoding: binary');
        //header('Content-Length: '.filesize($_FILES['upload']['tmp_name']));	// provide file size
        header('Connection: close');
        passthru($cmd, $retval);
        exit();

    }
}