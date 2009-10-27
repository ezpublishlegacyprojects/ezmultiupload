== How to run ?
run upload.php


== Configuration
Need to configure some settings in php.ini and file permissions

- php.ini
There are three important settings for the prototype:

post_max_size = 20M   // suggest at least 20M
upload_max_filesize = 20M  // suggest at least 20M
error_reporting = E_ALL & ~E_NOTICE //igore notice error message

- file permission
Give php the permission to write the directories. "tmp" is the temprary directory for chunking, "uploaded" is the directory upladed to. storage.txt is the file saving upload states.
In linux:
chmod 777 tmp
chmod 777 uploaded 
chmod 777 storage.txt

== Bugs
When file name has space, the resuming might be not working.

== Bugs fixed
- fixed 1: Resuming will result in corrupted file. File size is correct, but file is corrupted (md5 sum of uploaded file doesn't match original file)
- fixed 2: Progressbar is wrong when resuming:
    * Upload a file, unplug network when reaching 60%
    * Resume upload. Progressbar will go slowly from 0%-40%... Then it will jump from 40% to 100%
    * Expected result : progressbar should jump from 0% to 60%, then go slowly from 60% to 100%