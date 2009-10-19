<?php
require_once("multifilerequest.php");
session_start();

if($_FILES)
{
    $uSessionID = $_SESSION["u_session_id"];
    if(!isset($uSessionID))
    {
        $uSessionID=eZUploadSession::generateSessionID();
        $_SESSION["u_session_id"]=$uSessionID;        
    }
   
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
	<param name="postURL" value="upload.php"></param>
	<param name="maxChunkSize" value="15000000"></param>
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
<?php
}
?>