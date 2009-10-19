<?php 
require_once( "uploadsetting.php" );

class eZUploadStorage
{
    //an array of uploadsession
//    protected $uploadSessionList;
    
    //saved file
    private $savedIn;
    
    
    public function __construct()
    {
        $this->saveIn = eZUploadSetting::getScriptDirectory() .
                             "/" . eZUploadSetting::$storageFile;
        
//         if( !isset( $uploadSessionList ) )
//         {
//             $this->uploadSessionList = array();
////             $this->saveAll();
//         }
//         else
//         {
//             $this->uploadSessionList = $uploadSessionList;
//         }
    }
    
    public function loadObjectsFromFile()
    {
        $result = null;
        try
        {
            $fh = fopen($this->saveIn, "rb");
            $objectStream='';
            while(!feof($fh))
            {
                $objectStream .= fread($fh,1000);
            }
            fclose($fh);
            $result = unserialize( $objectStream );
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
        //if there is nothing in file, create an array
        if(!isset($result))
        {
            $result = array();
        }
        return $result;
    }
    
    public function saveAll($uploadSessionList)
    {
        try
        {
            $objectStream = serialize($uploadSessionList);
            $fh = fopen($this->saveIn, 'w');
            fwrite($fh, $objectStream);
            fclose($fh);
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
    
    public function getUploadSessionByID($sessionID)
    {
        $uploadSessionList=$this->loadObjectsFromFile();
        $result = $uploadSessionList[$sessionID];
        return $result;
    }
    
    public function updateUploadSession($uploadSession)
    {
        $uploadSessionList=$this->loadObjectsFromFile();
        $uploadSessionList[$uploadSession->sessionID()]=$uploadSession;
        $this->saveAll($uploadSessionList);
    }
    
    
    public static function create()
    {
        static $storage;
        if(!is_object($storage))
        {
            $storage = new eZUploadStorage();
        }
        return $storage;
    }
   
}

?>