<?php

// Make sure the file is valid and get contents
if (isset($_FILES['upload']) && is_uploaded_file($_FILES['upload']['tmp_name'])) {

    $debug = False; // True or False

    if (isset($_POST['debug'])){
        $debug = True;
    }

    //DEBUG:
    if ($debug == True) {
        echo '<h1>Debugging on</h1>';
        echo '<strong>File:</strong> ' . var_dump($_FILES['upload']) . '<br>';
        echo '<strong>Filename:</strong> ' . $_FILES['upload']['name'] . '<br>';
        echo '<strong>File Type:</strong> ' . $_FILES['upload']['type'] . '<br>';
        echo '<strong>Temp Name:</strong> ' . $_FILES['upload']['tmp_name'] . '<br>';
        echo '<strong>Error:</strong> ' . $_FILES['upload']['error'] . '<br>';
        echo '<strong>File Size:</strong> ' . $_FILES['upload']['size'] . '<br>';
        echo '<strong>Canvas Height:</strong> ' . $_POST['height'] . '&quot;<br>';
        echo '<strong>Canvas Width:</strong> ' . $_POST['width'] . '&quot;<br>';
        echo '<strong>Canvas Depth:</strong> ' . $_POST['depth'] . '&quot;<br>';
        echo '<strong>DPI:</strong> ' . $_POST['dpi'] . '&quot;<br>';
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
        canvasr($_FILES['upload']['tmp_name'], $_POST['height'], $_POST['width'], $_POST['depth'], $_POST['dpi'], $debug);

    }

} else {
    echo 'No File Detected. Please go back and upload a jpg file.';
}

function canvasr($file, $height, $width, $depth, $dpi, $debug) {

    if (!isset($dpi)) {
        $dpi = 300;
    }

    $pxWidth = $width * $dpi;
    $pxHeight = $height * $dpi;
    $pxDepth = $depth * $dpi;

    $cWidth = $pxWidth + ($pxDepth * 2);
    $cHeight = $pxHeight + ($pxDepth * 2);

    $image = $_FILES['upload']['name'];
    $filename = basename($image, ".jpg");
    $newImage = $filename . '-canvas.jpg';

    $sourceImage = $file;
    $output = 'JPG:-';

    if ($debug == True) {
        $sourceImage = $image;
        $output = $newImage;
    }

    // Set Imagemagick path:
    if (file_exists('/usr/bin/convert')) {
        $convert = '/usr/bin/convert';
    } elseif (file_exists('/usr/local/bin/convert')) {
        $convert = '/usr/local/bin/convert';
    } elseif (file_exists('/opt/local/bin/convert')) {
        $convert = '/opt/local/bin/convert';
    } else {
        echo 'Error: Can not find the path to ImageMagick convert.';
    }

    // Scale, crop and mirror edges
    $cmd = "$convert -units PixelsPerInch '$sourceImage' -density $dpi -resize '$pxWidth x $pxHeight ^' -gravity center -crop '$pxWidth x $pxHeight +0+0' -virtual-pixel mirror -set option:distort:viewport '$cWidth x $cHeight -$pxDepth -$pxDepth' -distort SRT 0 +repage $output";

    if ($debug == True) {

        print $cmd;

    } else {

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