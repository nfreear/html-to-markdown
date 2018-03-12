<?php
/**
 * A minimal HTML to Markdown web-service,
 * with HTTP Basic authentication.
 *
 * @copyright  © Nick Freear, 12-March-2018.
 * @link http://local.wp:8080/html-to-md/?url=http://www.headstar.com/eablive/%3Feab_bulletin=february-2018
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Intervention\Httpauth\Httpauth;
use League\HTMLToMarkdown\HtmlConverter;
use duzun\hQuery;

httpBasicAuth();

if ( ! defined( 'URL_REGEX' )) _die( 'Error, missing config/ define.' );

if ( ! URL || ! preg_match( URL_REGEX, URL )) _die( 'Error, unsupported or missing URL.' );

$doc = hQuery::fromFile( URL, false, httpGetContext() );

$post = $doc->find( '#main .post' );

$converter = new HtmlConverter([
	'strip_tags' => true, 'hard_break' => true, 'header_style' => 'atx', 'remove_nodes' => '.postmetadata' ]);

$markdown = $converter->convert( $post->html() );

header( 'Content-Type: text/plain; charset=utf-8' );
header( 'X-Link: ' . URL );

echo wordwrap( preg_replace( STRIP_REGEX, '', $markdown ), WORDWRAP );

printf( "\n---\n\n<%s>\n", URL );

// ----------------------------------------------------------------------

function httpBasicAuth() {
	Httpauth::make([
		'type' => 'basic',
		'realm' => 'Markdown',
		'username' => HTTP_AUTH_USERNAME,
		'password' => HTTP_AUTH_PASSWORD,
	])->secure();
}

function httpGetContext($postdata = null) {
    // $postdata = is_array( $postdata ) ? http_build_query( $postdata ) : $postdata;
    return stream_context_create([
        'http' => [
            'method' => 'GET', // 'POST',
            /* 'user_agent' => 'CloudEngine/1.0-beta +https://github.com/nfreear',
            'proxy' => HTTP_PROXY,
            'header' => [
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen( $postdata ),
            ],
            'content' => $postdata, */
            'timeout' => 8, // Seconds.
        ]
    ]);
}

function _die( $message = 'Unknown Error', $http_status = 500 ) {
	header( 'HTTP/1.1 ' . $http_status );
	echo "<!doctype html> <title> Error </title> <meta name=robots content=noindex />\n";
	die( $message );
}

// End.
