<?php
require_once("multifilerequest.php");
require_once("uploadsession.php");
require_once("clientfileinfo.php");
require_once("uploadedfile.php");
require_once("uploadedfileitem.php");

session_start();
$uSessionID = $_SESSION["u_session_id"];
if(!isset($uSessionID))
{
    $uSessionID=eZUploadSession::generateSessionID();
    $_SESSION["u_session_id"]=$uSessionID;        
}

if($_GET["action"]=="status")
{
    $uploadSession = eZUploadSession::getUploadSessionBySessionID( $uSessionID );
    if(isset( $uploadSession ))
    { 
        $filename = $_GET["filename"];
        $fileID = eZClientFileInfo::generateFileID( new eZClientFileInfo( $filename ) );
        
        $uploadedFile = $uploadSession->getUploadedFileByFileID( $fileID );
    
        $resultArr=array();
        if( isset($uploadedFile) )
        {
            foreach( $uploadedFile->uploadedItemList() as $item )
            {
                array_push($resultArr,$item->part());
            }
        }
        $result = implode( ",",$resultArr);
        if($result=="")
        {
            $result=-1;
        }
        echo $result;
    }
    else
    {
        echo "-1";
    }
    return;
}

if($_FILES)
{   
    try
    {
        $multiFile=new eZMultiFileRequest( $_FILES, $_GET, $uSessionID );
        $multiFile->uploadFile();
        echo "SUCCESS";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    return;
}

?>
<html>
<title>LFU(Large File Upload) Prototype</title>
<body>
<div>This is the Large File Upload prototype:</div>
<div><applet code="wjhk.jupload2.JUploadApplet" name="lfuApplet"
	archive="resources/jupload_extension.jar" width="600" mayscript height="400">
	<param name="postURL" value="upload.php"></param>
	<param name="maxChunkSize" value="1000000"></param>
	<param name="maxFileSize" value="10000000000"></param>
	<param name="nbFilesPerRequest" value="5"></param> 
	<!-- <param name="allowedFileExtensions" value="jpg/gif/jpeg"></param> -->
	<param name="afterUploadURL" value="javascript:alert('%body%');"></param>
	<param name="lookAndFeel" value="system"></param>
	<param name="showLogWindow" value="false"></param>
	Java runtime machine needed </applet></div>
<script>
</script>
</body>
</html>