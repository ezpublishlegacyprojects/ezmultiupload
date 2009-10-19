<?php

class eZClientFileInfo
{
    /**
     * file name of client
     * @var unknown_type
     */
    protected $fileName;
    
    public function fileName()
    {
        return $this->fileName;
    }
    
    public function setFileName($file)
    {
        $this->fileName=$file["tmp_name"];
        
    }
    
    /**
     * the id generated of file
     * @var unknown_type
     */
    protected $fileID;
    
    public function fileID()
    {
        return $this->fileID;
    }
    
    /**
     * constructor
     * @param $fileName
     * @return unknown_type
     */
    public function __construct( $file )
    {
        $this->fileName=$file["name"];
        $this->fileID=eZClientFileInfo::generateFileID($this);
    }
    
    /**
     * generate ID from a clientFileInfo, this can be changed for better algoritm
     * by default, it returns a "guid+filename"
    */
    public static function generateFileID($clientFileInfo)
    {
        return "__" . $clientFileInfo->fileName();
    }
}
?>