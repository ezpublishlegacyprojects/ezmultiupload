<?php

class eZUploadSetting
{
    public static $tempDirectory="tmp";
    
    public static $targetDirectory="uploaded";

    public static $storageFile = "storage.txt";
    
    public static function getScriptDirectory()
    {
        $serverDir = $_SERVER['SCRIPT_FILENAME'];
        $serverDir = substr($serverDir, 0, strripos( $serverDir, "/" ) );
        return $serverDir;
    }
}

?>