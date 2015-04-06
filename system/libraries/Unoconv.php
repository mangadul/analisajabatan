<?php

//namespace Unoconv;

/**
* Unoconv class wrapper
*
* @author Rafael Goulart <rafaelgou@gmail.com>
* @see http://tech.rgou.net/
*/
class CI_Unoconv {

    /**
    * Basic converter method
    * 
    * @param string $originFilePath Origin File Path
    * @param string $toFormat       Format to export To
    * @param string $outputDirPath  Output directory path
    */
    public static function convert($originFilePath, $outputDirPath, $toFormat)
    {
        $command = "'C:\\Program Files\\OpenOffice 4\\program\python.exe' C:\xampp\htdocs\anforjab\bin\unoconv --format %s --output %s %s";
        $command = sprintf($command, $toFormat, $outputDirPath, $originFilePath);
        system($command, $output);
        return $output;
    }

    /**
    * Convert to PDF
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToPdf($originFilePath, $outputDirPath)
    {
        return self::convert($originFilePath, $outputDirPath, 'pdf');
    }

    /**
    * Convert to DOC
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToDoc($originFilePath, $outputDirPath)
    {
        return self::convert($originFilePath, $outputDirPath, 'doc');
    }

    /**
    * Convert to DOCX
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToDocx($originFilePath, $outputDirPath)
    {
        return self::convert($originFilePath, $outputDirPath, 'docx');
    }
	
    /**
    * Convert to TXT
    * 
    * @param string $originFilePath Origin File Path
    * @param string $outputDirPath  Output directory path
    */
    public static function convertToTxt($originFilePath, $outputDirPath)
    {
        return self::convert($originFilePath, $outputDirPath, 'txt');
    }

}