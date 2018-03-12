<?php

// HTTP Basic authentication.
define( 'HTTP_AUTH_USERNAME', '** EDIT ME **' );
define( 'HTTP_AUTH_PASSWORD', '** EDIT ME **' );

define( 'URL_REGEX', '/^https?:\/\/(www\.)?headstar.com\//' );

define( 'URL', filter_input( INPUT_GET, 'url', FILTER_VALIDATE_URL ) );

define( 'WORDWRAP', 76 );

define( 'STRIP_REGEX', '/This entry was posted on .+/m' );

// End.
