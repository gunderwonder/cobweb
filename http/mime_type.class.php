<?php
/**
 * @version $Id$ 
 */

/**
 * @package    Cobweb
 * @subpackage HTTP
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 */
abstract class MIMEType {
	
	const HTML  = 'text/html; charset=UTF-8';
	const JSON  = 'application/json; charset=UTF-8';
	const XHTML = 'application/xhtml+xml; charset=UTF-8';
	const RSS = 'application/rss+xml; charset=UTF-8';
	const JAVASCRIPT = 'application/x-javascript';
	const JPEG = 'image/jpg';
	const PNG = 'image/png';
	const TIFF = 'image/tiff';
	const CSS = 'text/css';
	const XML = 'application/xml; charset=UTF-8';
	const WORD = 'application/msword';
	const EXEL = 'application/vnd.ms-excel';
	const POWERPOINT = 'application/vnd.ms-powerpoint';
	const RTF = 'application/rtf';
	const PDF = 'application/pdf';
	const TEXT = 'text/plain; charset=UTF-8';
	const MPEG = 'video/mpeg';
	const MP3 = 'audio/mpeg3';
	const WAW = 'audio/wav';
	const AIFF = 'audio/aiff';
	const AVI = 'video/msvideo';
	const WMV = 'video/x-ms-wmv';
	const QUICKTIME = 'video/quicktime';
	const ZIP = 'application/zip';
	const TAR = 'application/x-tar';
	const FLASH = 'application/x-shockwave-flash';
	
	
	/**
	 * Guesses the MIME type based on its filename.
	 * 
	 * @param   string $filename name of the file to guess
	 * @return  string           the MIME type string of the filename
	 * 
	 * @see   http://no.php.net/manual/en/function.mime-content-type.php#84361
	 */
	public static function guess($filename) {
		$suffix = substr($filename, strrpos($filename, '.') + 1, strlen($filename) - 1);
		
        switch(strtolower($suffix)) {
            case "js" :
                return self::JAVASCRIPT;

            case "json" :
                return self::JSON;

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return self::JPEG;

            case "png":
				return self::PNG;
            case "gif":
				return self::GIF;
            case "bmp":
				return self::BMP;
            case "tiff" :
				return self::TIFF;

            case "css":
                return self::CSS;

            case "xml":
                return self::XML;

            case "doc":
            case "docx":
                return self::WORD;

            case "xls":
            case "xlt":
            case "xlm":
            case "xld":
            case "xla":
            case "xlc":
            case "xlw":
            case "xll":
                return self::EXEL;

            case "ppt":
            case "pps":
                return self::POWERPOINT;

            case "rtf":
                return self::RTF;

            case "pdf":
                return self::PDF;

            case "html":
            case "htm":
            case "php":
                return self::HTML;

            case "txt":
                return self::TEXT;

            case "mpeg":
            case "mpg":
            case "mpe":
                return self::MPEG;

            case "mp3":
                return self::MP3;

            case "wav":
                return self::WAV;

            case "aiff":
            case "aif":
                return self::AIFF;

            case "avi":
                return self::AVI;

            case "wmv":
                return self::WMV;

            case "mov":
                return self::QUICKTIME;

            case "zip":
                return self::ZIP;

            case "tar":
                return self::TAR;

            case "swf":
                return self::FLASH;

            default:
				return self::TEXT;
        }
    }
}