
<?php
/**
This is the prototype of LFU(large file upload)

Use Cases

1)Large file support & PHP handle(Apache handle)
2)File resume
	
3)Multi file select
1.select not only one file
2.upload
3.check the progress bar showing the progress of one file's upload and all files's upload
4.break and resume
4)script client
1.change the layout
2.change the color
3.change the filter
4.remove files, pause the upload
 */

session_start();

$maxSize=500000000;

if(!isset($_SESSION['currentPart']))
{
    $_SESSION['currentPart'] = -1;
}

function acceptChunkFile($tempDir, $dir, $file, $juPart=1, $juFinal=0)
{
    //////////////////////
    //if not the end of the chunk, save them into a temperary directory
    //
    
    $fileName= $file["name"];
    //if not finished and $juPart is not the next of $currentPart, it means it's broken, continue from currentPart
    if( $juPart < $_SESSION['currentPart'] && $fileName == $_SESSION['currentFilename'] )
    {
        return;
    }
    else
    {
        //compose a name
        $filePath = $file["tmp_name"];
        $targetName = $fileName . ".part" . $juPart;
        $_SESSION['currentPart']= $juPart;
        $_SESSION['currentFilename']= $fileName;
        $moveResult = move_uploaded_file( $filePath, $tempDir . "/" . $targetName);
        
        //move file into temparay directory
        
        ////////////////////////////////////////
        //if it's the end of file, merge them
        //
        if( $juFinal==1 )
        {
            //create file
            $targetFilePath=$dir . "/" . $fileName;
            $handle = fopen( $targetFilePath, "wb" );
            //read the content and write to the merge file one by one
            $byteSize=2000;
            for( $i=1; $i<=$juPart; $i++ )
            {
                //read one and write to the merged file
                $partFilePath = $tempDir . "/" . $fileName . ".part" . $i;
                $partHandle = fopen( $partFilePath, "rb");
                while( !feof( $partHandle ) )
                {
                     $content = fread( $partHandle, $byteSize );
                     fwrite( $handle,  $content, $byteSize);
                }
                fclose( $partHandle );
                //delete the file
                unlink($partFilePath);
            }
            fclose( $handle );
            
            //set status
            unset($_SESSION['currentPart']);
            unset($_SESSION['currentFilename']);
        }
    }
    
}


if( $_FILES )
{
    $result = "This is the result:\n";
    foreach( $_FILES as $key => $file )
    {
        $serverDir = $_SERVER['SCRIPT_FILENAME'];
        $serverDir = substr($serverDir, 0, strripos( $serverDir, "/" ) );
        //support chunking
        if( isset($_GET["jupart"]) && isset($_GET["jufinal"]) )
        {
            $juPart = $_GET["jupart"];
            $juFinal = $_GET["jufinal"];
            $tmpDir= $serverDir . "/tmp";
            $targetDir = $serverDir . "/uploaded";
            acceptChunkFile( $tmpDir, $targetDir, $file, $juPart, $juFinal);
        }
        else
        {
            $tmpFilePath = $file["tmp_name"];
            $targetFile = $serverDir . "/uploaded/" . $file["name"];
            move_uploaded_file( $tmpFilePath, $targetFile );
            
        }
        
        foreach($file as $fileItem => $fileValue )
        {
            $result = $result."$fileItem => $fileValue \n";
        }
    }
    $myFile = "uploadresult.txt";
    $fh = fopen($myFile, 'w') or die("can't open file");
    fwrite($fh, $result);
    fclose($fh);

    echo "SUCCESS";
}
else
{
    ?>

<html>
<title>LFU(Large File Upload) Prototype</title>
<body>
<div>This is the Large File Upload prototype:</div>
<div><applet code="wjhk.jupload2.JUploadApplet" name="lfuApplet"
	archive="resources/wjhk.jupload.jar" width="600" mayscript height="400">
	<param name="postURL" value="jupload.php"></param>
	<param name="maxChunkSize" value="1000000"></param>
	<param name="maxFileSize" value="10000000000"></param>
	<param name="nbFilesPerRequest" value="5"></param> 
	<!-- <param name="allowedFileExtensions" value="jpg/gif/jpeg"></param> -->
	<param name="afterUploadURL" value="javascript:alert('%body%');"></param>
	<param name="lookAndFeel" value="system"></param>
	<param name="showLogWindow" value="false"></param>
	Java runtime machine needed </applet></div>
<script>
//var lfuApplet=document.getElementByName("lfuApplet");
//alert(lfuApplet.width);
//lfuApplet.width=800;
</script>
</body>
</html>
    <?php
}
?>