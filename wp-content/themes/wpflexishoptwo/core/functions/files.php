<?php

function prima_childtheme_file($file) {
	if ( ( PRIMA_DIR != THEME_DIR ) && file_exists(trailingslashit(THEME_DIR).$file) ) 
		$url = trailingslashit(THEME_URI).$file;
	else 
		$url = trailingslashit(PRIMA_URI).$file;
	return $url;
}

function prima_file_info($url = '', $output='filename') {
	if ( ! $url ) return;
	$filename = basename( $url );
	if ( ! $filename ) return;
	if ( $output == 'filename' ) return $filename;
	$ext = preg_match('/\.([^.]+)$/', $filename, $matches) ? strtolower($matches[1]) : false;
	if ( ! $ext ) return;
	if ( $output == 'ext' ) return $ext;
	$mimes = array(
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe' => 'image/jpeg',
		'gif' => 'image/gif',
		'png' => 'image/png',
		'bmp' => 'image/bmp',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'ico' => 'image/x-icon',
		'asf' => 'video/asf',
		'asx' => 'video/asf',
		'wax' => 'video/asf',
		'wmv' => 'video/asf',
		'wmx' => 'video/asf',
		'avi' => 'video/avi',
		'divx' => 'video/divx',
		'flv' => 'video/x-flv',
		'mov' => 'video/quicktime',
		'qt' => 'video/quicktime',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpe' => 'video/mpeg',
		'txt' => 'text/plain',
		'asc' => 'text/plain',
		'c' => 'text/plain',
		'cc' => 'text/plain',
		'h' => 'text/plain',
		'csv' => 'text/csv',
		'tsv' => 'text/tab-separated-values',
		'rtx' => 'text/richtext',
		'css' => 'text/css',
		'htm' => 'text/html',
		'html' => 'text/html',
		'mp3' => 'audio/mpeg',
		'm4a' => 'audio/mpeg',
		'm4b' => 'audio/mpeg',
		'mp4' => 'video/mp4',
		'm4v' => 'video/mp4',
		'ra' => 'audio/x-realaudio',
		'ram' => 'audio/x-realaudio',
		'wav' => 'audio/wav',
		'ogg' => 'audio/ogg',
		'oga' => 'audio/ogg',
		'ogv' => 'video/ogg',
		'mid' => 'audio/midi',
		'midi' => 'audio/midi',
		'wma' => 'audio/wma',
		'mka' => 'audio/x-matroska',
		'mkv' => 'video/x-matroska',
		'rtf' => 'application/rtf',
		'js' => 'application/javascript',
		'pdf' => 'application/pdf',
		'doc' => 'application/msword',
		'docx' => 'application/msword',
		'pot' => 'application/vnd.ms-powerpoint',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'pptx' => 'application/vnd.ms-powerpoint',
		'ppam' => 'application/vnd.ms-powerpoint',
		'pptm' => 'application/vnd.ms-powerpoint',
		'sldm' => 'application/vnd.ms-powerpoint',
		'ppsm' => 'application/vnd.ms-powerpoint',
		'potm' => 'application/vnd.ms-powerpoint',
		'wri' => 'application/vnd.ms-write',
		'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel',
		'mdb' => 'application/vnd.ms-access',
		'mpp' => 'application/vnd.ms-project',
		'docm' => 'application/vnd.ms-word',
		'dotm' => 'application/vnd.ms-word',
		'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml',
		'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml',
		'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml',
		'potx' => 'application/vnd.openxmlformats-officedocument.presentationml',
		'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml',
		'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml',
		'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml',
		'onetoc' => 'application/onenote',
		'onetoc2' => 'application/onenote',
		'onetmp' => 'application/onenote',
		'onepkg' => 'application/onenote',
		'swf' => 'application/x-shockwave-flash',
		'class' => 'application/java',
		'tar' => 'application/x-tar',
		'zip' => 'application/zip',
		'gz' => 'application/x-gzip',
		'gzip' => 'application/x-gzip',
		'exe' => 'application/x-msdownload',
		// openoffice formats
		'odt' => 'application/vnd.oasis.opendocument.text',
		'odp' => 'application/vnd.oasis.opendocument.presentation',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		'odg' => 'application/vnd.oasis.opendocument.graphics',
		'odc' => 'application/vnd.oasis.opendocument.chart',
		'odb' => 'application/vnd.oasis.opendocument.database',
		'odf' => 'application/vnd.oasis.opendocument.formula',
		// wordperfect formats
		'wp' => 'application/wordperfect',
		'wpd' => 'application/wordperfect'
	);
	$mime = ( ! isset($mimes[strtolower($ext)]) ) ? 'application/octet-stream' : $mimes[strtolower($ext)];
	if ( $output == 'mime' ) return $mime;
	$type = explode( '/', $mime );
	if ( $output == 'type' ) return $type[0];
}

add_filter('upload_mimes', 'prima_add_video_mimes');
function prima_add_video_mimes($mimes) {
    $mimes = array_merge($mimes, array(
        'flv' => 'video/x-flv',
        'mp4' => 'video/mp4'
    ));
    return $mimes;
}