<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: global - v0.2
 */

// Do not expose which PHP version we're using.
header_remove('X-Powered-By');

// Set the default timezone for the server.
date_default_timezone_set('America/New_York');

// Prevent direct access to includes/
if (!defined('BACKEND')) {
    http_response_code(404);
    die('File not found.');
}