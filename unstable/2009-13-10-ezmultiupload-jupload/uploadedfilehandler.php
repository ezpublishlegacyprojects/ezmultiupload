<?php

class eZUploadedFileHandler
{
    /***
     * merge files into a new file
     */
    public static function mergeFile( $uploadedItemList, $targetPath)
    {
        //create file
        $handle = null;
        try
        {
            $handle = fopen( $targetPath, "wb" );
       
            //read the content and write to the merge file one by one
            $byteSize=8192;
            foreach( $uploadedItemList as $uploadedItem)
            {
                //read one and write to the merged file
                $partFilePath = $uploadedItem->directory()."/".$uploadedItem->name();
                try
                {
                    $partHandle = fopen( $partFilePath, "rb");
                    while( !feof( $partHandle ) )
                    {
                         $content = fread( $partHandle, $byteSize );
                         fwrite( $handle,  $content, $byteSize);
                    }
                    fclose( $partHandle );
                }
                catch(Exception $ex)
                {
                    fclose( $partHandle );
                    throw $ex;
                }
                //delete the file
//                unlink($partFilePath);
            }
            fclose( $handle );
        }
        catch(Exception $ex)
        {
            fclose( $handle );
            throw $ex;
        }
    }
}
?>