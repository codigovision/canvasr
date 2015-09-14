<!doctype html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Canvasr - Image Mirroring Tool for Wrapped Canvas Printing</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->

<div class="container">
    <div class="page-header">
        <h1>Canvasr - Image Mirroring Tool</h1>
    </div>
    <div class="alert alert-info" role="alert">Please upload an image.</div>
    <div id="form">

        <form action="canvasr.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label id="upload" for="upload">Select a file:</label>
                <input type="file" id="upload" name="upload" required />
            </div>

            <div class="form-group">
                <label for="canvas">Canvas Height:</label>
                <input type="number" step="any" name="height" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="canvas">Canvas Width:</label>
                <input type="number" step="any" name="width" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="canvas">Canvas Depth:</label>
                <input type="number" step="any" name="depth" class="form-control" required />
            </div>

            <input type="hidden" name="action" value="upload"/>

            <input type="submit" value="submit" title="Submit" class="global-submit btn btn-primary"/>

        </form>

    </div>

</div>


</body>
</html>