<?php

class ezUploadedFileItem
{
    /**
     * the uploaded file object referecnce
     * @var eZUploadedFile
     */
    protected $uploadedFile;
    
    /**
     * name of uploadedFileItem
     * @var unknown_type
     */
    protected $name;
    
    /**
     * the directory where the part locates, duplicate from eZUploadedFile
     * @var string
     */
    protected $directory;
    
    
    /**
     * part of uploadedFileItem
     * @var unknown_type
     */
    protected $part;
    
    /**
     * if the item is the last
     * @var unknown_type
     */
    protected $isLast;
    
    
    public function __construct( $uploadedFile, $part=0, $isLast=false )
    {
      $this->uploadedFile = $uploadedFile;
      $this->part=$part;
      
      $this->directory = $uploadedFile->tmpDirectory();
      
      $tmpName = $uploadedFile->tmpName();
      $tmpName = $tmpName.".part".$this->part;
      $this->name=$tmpName;
      
      $this->isLast=$isLast;   
    }
    
    public function upload($file)
    {
        //tmpName
        $phpTmpName=$file["tmp_name"];
        //compose the temparay path (not php temp path for upload)

        $tmpDirectory = $this->uploadedFile->tmpDirectory();
        $tmpPath = $tmpDirectory."/".$this->name;
        //move the file into temparary path
        try
        {
            move_uploaded_file( $phpTmpName, $tmpPath );
        }
        catch(Exception $e)
        {
            throw $e;
        }
    }
    
    public function name()
    {
        return $this->name;
    }
    
    public function part()
    {
        return $this->part;
    }
    
    public function directory()
    {
        return $this->directory;
    }
    
    public function isLast()
    {
        return $this->isLast();
    }
   
}


?>