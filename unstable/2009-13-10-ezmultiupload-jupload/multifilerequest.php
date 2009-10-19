<?php
require_once( "uploadsession.php" );
require_once( "uploadedfile.php" );
require_once( "clientfileinfo.php" );
require_once( "uploadsetting.php" );

class eZMultiFileRequest
{
    /**
     * the files requested
     * @var http file object array
     */
    protected $fileList;
    
    /**
     * the session of user
     * @var http session object
     */
    protected $sessionID;
    
    /**
     * the GET parameters
     * @var unknown_type
     */
    protected $getParameters;
    
    public function __construct( $fileList, $getParameters, $sessionID )
    {
        $this->fileList = $fileList;
        $this->getParameters = $getParameters;
        $this->sessionID = $sessionID;
    }
    
    /**
     * upload file
     * @return unknown_type
     */
    public function uploadFile()
    {
        $juPart= $this->getParameters["jupart"];
        $juFinal= $this->getParameters["jufinal"];
        $uploadSession = eZUploadSession::getUploadSessionBySessionID( $this->sessionID );
        if(!isset( $uploadSession ))
        {
            $uploadSession = new eZUploadSession( $this->sessionID );
        }
        ////////////////////////////////
        //if there are juPart and juFinal parameter, it's chunking
        //then it's sure that there is only one file
        /////
        if( isset( $juPart ) && isset( $juFinal ) )
        {
            if( count( $this->fileList ) !==1 )
            {
                throw new Exception("upload error: file should be one");
            }
            $file = $this->fileList["File0"];
            //get the status
            $clientFileInfo=new eZClientFileInfo( $file );
            $fileID = $clientFileInfo->fileID();
            $uploadedFile = $uploadSession->getUploadedFileByFileID($fileID);
            if(!isset( $uploadedFile ))
            {
                $uploadedFile = new eZUploadedFile();
                $uploadedFile->setFinished(false);
                $uploadedFile->setFileID($fileID);
                $uploadedFile->setClientFile($clientFileInfo);
                $uploadedFile->setTmpName($clientFileInfo->fileName());
                $uploadedFile->setTargetName($clientFileInfo->fileName());
                //target directory
                $scriptDirectory=eZUploadSetting::getScriptDirectory();
                $uploadedFile->setTmpDirectory($scriptDirectory. "/". eZUploadSetting::$tempDirectory );
                $uploadedFile->setTargetDirectory($scriptDirectory . "/" . eZUploadSetting::$targetDirectory );
            }
            
            //upload it and save the session
            try
            {
                $uploadedFile->upload( $file, $juPart, $juFinal );
                $uploadSession->updateUploadedFile( $uploadedFile);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
            
        }
        else
        {
            ///////////////////////////////
            //if there is no chunking,upload one by one
            //save the session
            foreach( $this->fileList as $file )
            {
                //upload 
                $clientFileInfo = new eZClientFileInfo( $file );
                $fileID= $clientFileInfo->fileID();
                
                $uploadedFile = $uploadSession->getUploadedFileByFileID($fileID);
                if( !isset($uploadedFile) )
                {
                    $uploadedFile = new eZUploadedFile();
                    $uploadedFile->setFinished(false);
                    $uploadedFile->setFileID($fileID);
                    $uploadedFile->setClientFile($clientFileInfo);
                    $uploadedFile->setTmpName($clientFileInfo->fileName());
                    $uploadedFile->setTargetName($clientFileInfo->fileName());
                    //target directory
                    $scriptDirectory=eZUploadSetting::getScriptDirectory();
                    $uploadedFile->setTmpDirectory($scriptDirectory. "/". eZUploadSetting::$tempDirectory );
                    $uploadedFile->setTargetDirectory($scriptDirectory . "/" . eZUploadSetting::$targetDirectory );
                }
                try
                {
                    $uploadedFile->upload( $file );
                    //add into upload session list
                    $uploadSession->updateUploadedFile($uploadedFile);
                }
                catch( Exception $ex )
                {
                    throw $ex;
                
                }
            }
        }

    }
}
?>