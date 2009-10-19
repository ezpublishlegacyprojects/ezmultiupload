<?php

require_once( "uploadstorage.php" );

class eZUploadSession
{   
    
    protected $sessionID;
    protected $uploadFileList;
    
    function __construct($sessionID)
    {
        $this->sessionID = $sessionID;
        $this->uploadFileList = array();
    }
    
    public function sessionID()
    {
        return $this->sessionID;
    }
    
    
    public function updateUploadedFile( $uploadedFile )
    {
        //save to this object
        $this->uploadFileList[$uploadedFile->fileID()] = $uploadedFile;
        
        //save to storage
        $storage = eZUploadStorage::create();
        $storage->updateUploadSession($this);

    }
    
//    public function updateUploadedFile( $uploadedFile )
//    {
//        //if uploadFile is not in the list ,add it
//        foreach($this->uploadFileList as $key => $uf)
//        {
//            if( $uf->fileID()==$uploadedFile->fileID() )
//            {
//                $this->uploadFileList[$key]=$uploadedFile;
//            }
//        }
//        
//        //save
//        $storage = eZUploadStorage::create();
//        $storage->updateUploadSession($this);
//    }
    
    public function getUploadedFileByFileID($fileID)
    {
        $result=null;
        foreach($this->uploadFileList as $key => $uf)
        {
            if( $uf->fileID()==$fileID )
            {
                $result=$uf;
            }
        }
        return $result;
    }
    
    public static function getUploadSessionBySessionID($sessionID)
    {
        $storage=eZUploadStorage::create();
        $result=$storage->getUploadSessionByID($sessionID);
        return $result;
    }
    
    
    /**
     * return a uique id for a session
     * @return unknown_type
     */
    public static function generateSessionID()
    {
        return uniqid();
    }
//    //if there is in db, fetch it, otherwise ,create a new one
//    public static function create($sessionID)
//    {
//        $uploadSession = null;
//        $storage = new eZUploadStorage();
//        $list = $storage->uploadSessionList();
//        foreach ( $list as $uSession )
//        {
//            if( $sessionID == $uSession->sessionID() )
//            {
//                $uploadSession= $uSession;
//            }
//        }
//        if( !isset($uploadSession) )
//        {
//            $uploadSession = new eZUploadSession($sessionID);
//            
//        }
//        
//        
//        return ;
//        
//    }
}
?>