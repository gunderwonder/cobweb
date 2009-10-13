<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package    Cobweb
 * @subpackage HTTP
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 */
abstract class MIMEType {
	
	const HTML  = 'text/html';
	const JSON  = 'application/json';
	const XHTML = 'application/xhtml+xml';
	const RSS = 'application/rss+xml';
	const XML = 'application/xml';
	const TEXT = 'text/plain';
	
	const JAVASCRIPT = 'application/x-javascript';
	const JPEG = 'image/jpg';
	const PNG = 'image/png';
	const GIF = 'image/gif';
	const BMP = 'image/bmp';
	const PSD = 'image/psd';
	const TIFF = 'image/tiff';
	const CSS = 'text/css';
	const WORD = 'application/msword';
	const EXEL = 'application/vnd.ms-excel';
	const POWERPOINT = 'application/vnd.ms-powerpoint';
	const RTF = 'application/rtf';
	const PDF = 'application/pdf';
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
		// if (function_exists('finfo_open')) {
		// 	$info = finfo_open(FILEINFO_MIME);
		//     		$mime_type = finfo_file($info, $filename);
		// 	finfo_close($info);
		// 	if ($mime_type) 
		// 		return $mime_type;
		// }
		
		// if (function_exists('mime_content_type') && $result = mime_content_type($filename))
		// 	return $result;
		
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