<?php
require_once( "uploadedfileitem.php" );
require_once( "uploadedfilehandler.php" );

 class eZUploadedFile
{
    /**
     * @var boolean
     */
    protected $finished;
    protected $fileID; //id to identify file
        
    protected $targetDirectory;
    protected $targetName;
    protected $clientFile;
    
    protected $uploadedItemList;
    
    protected $tmpName;
    protected $tmpDirectory;
     
    public function __construct( $finished=false, $fileID=null, $targetName=null, $targetDirectory=null, $clientFile=null, $uploadedItemList=null, $tmpName=null, $tmpDirectory=null)
    {
        $this->finished=$finished;
        $this->fileID=$fileID;
        $this->path=$path;
        $this->targetName=$targetName;
        $this->targetDirectory=$targetDirectory;
        
        $this->tmpName=$tmpName;
        $this->tmpDirectory=$tmpDirectory;
        
        if(isset($uploadedItemList))
        {
            $this->uploadedItemList=$clientFile;
        }
        else
        {
            $this->uploadedItemList=array();
        }
        
    }
    
    public function upload( $file, $part = false, $isFinal = false )
    {
        //if not chunking, upload it
        if( ( !$part ) && ( !$isFinal ) )
        {
            $phpTmpName = $file["tmp_name"];
            $targetPath = $this->targetDirectory ."/". $this->targetName;
            try
            {
                move_uploaded_file( $phpTmpName, $targetPath );   
                $this->finished=true;
            }
            catch(Exception $e)
            {
                $message = "upload file " . $this->clientFile->fileName . " error!";
                throw new Exception( $message, 0, $e );
            }
        }
        else
        {
           
            /////////////
            //upload part into server
            
            //if there is not this "part", upload it, otherwise do nothing
            if( !$this->isUploadedItem( $part ) )
            {
                $uploadedFileItem = new eZUploadedFileItem( $this, $part, $isFinal);
                try
                {
                     $uploadedFileItem->upload( $file );
                     array_push( $this->uploadedItemList, $uploadedFileItem);
                }
                catch(Exception $ex)
                {
                    $message = "uploaded file ". $this->clientFile->fileName . " error";
                    throw new Exception( $message, 0 ,$ex );
                }
            }
            
            /////////////
            //if it's the final part, merge them
            if( $isFinal==1 )
            {
                try
                {
                    $targetPath = $this->targetDirectory . "/" . $this->targetName;
                    eZUploadedFileHandler::mergeFile( $this->uploadedItemList, $targetPath);
                    $this->finished = true;
                }
                catch(Exception $ex)
                {
                    $message = "merge file ". $this->clientFile->fileName ." error";
                    throw new Exception($message, 0, $ex );
                }
            }
            
        }
        
     
    }
    
    public function isUploadedItem($part)
    {
        $result = false;
        foreach( $this->uploadedItemList as $uploadedItem )
        {
            if( $uploadedItem->part() === $part )
            {
                $result = true;
            }
        }
        return $result;
    }
    
    protected function mergeFile()
    {
        
    }
    
    public function uploadChunkingFile($file, $juPart, $juFinal, $sessionID)
    {
        //check if there is previous uploaded item
        
        
        //upload one part into temp directory
        $uploadedItem = new eZUploadedItem();
        $uploadedItem->upload();
        array_push( $uploadedList, uploadedItem);
        
        
    }
    
    /**
     * get the upload file list from server's record
     * @param $uploadSession
     * @return unknown_type
     */
    private function getUploadedItemList( $sessionID )
    {
        $uploadSession= new eZUploadSession($sessionID);
        $idList = $uploadSession->fileIDList();
        $id = eZUploadSession::generateID( $clientFile );
        
    }
    
    public function finished()
    {
        return $this->finished;
    }
    
    public function setFinished($finished)
    {
        $this->finished=$finished;
    }
    
    public function uploadedList()
    {
        return $this->uploadedList;
    }
    
    public function fileID()
    {
        return $this->fileID;
    }

    public function setFileID($fileID)
    {
        $this->fileID = $fileID;
    }
    
    public function targetDirectory()
    {
        return $this->targetDirectory;
    }
    
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory=$targetDirectory;
    }
    
    public function targetName()
    {
        return $this->targetName;
    }
    
    public function setTargetName($targetName)
    {
        $this->targetName=$targetName;
    }
    
    public function clientFile()
    {
        return $this->clientFile;
    }
    
    public function setClientFile($clientFile)
    {
        $this->clientFile=$clientFile;
    }
    
    public function uploadedItemList()
    {
        return $this->uploadedItemList;
    }
    
    public function tmpName()
    {
        return $this->tmpName;
    }
    
    public function setTmpName($tmpName)
    {
        $this->tmpName=$tmpName;
    }
    
    public function tmpDirectory()
    {
        return $this->tmpDirectory;
    }
    
    public function setTmpDirectory($tmpDirectory)
    {
        $this->tmpDirectory=$tmpDirectory;
    }
    
}
?>