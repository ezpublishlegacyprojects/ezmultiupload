==Configuration

Need to configure some settings in php.ini
There are three important settings for the prototype:

post_max_size = 20M   // suggest at least 20M
upload_max_filesize = 20M  // suggest at least 20M
error_reporting = E_ALL & ~E_NOTICE //igore notice error message