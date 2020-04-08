<?php

if (!defined("STATUS_ON")) define("STATUS_ON", 1);
if (!defined("STATUS_OFF")) define("STATUS_OFF", 0);
/*
 * Collection custom name
 */
if (!defined("MAIN_COLLECTION_IMAGE")) define("MAIN_COLLECTION_IMAGE", "main");
if (!defined("DEFAULT_IMAGE_COLLECTION")) define("DEFAULT_IMAGE_COLLECTION", "default");

/*
 * The key is equal to the key of the translation file kulara.php
 */
return [
    'status' => [
        STATUS_OFF => 'status_off',
        STATUS_ON => 'status_on',
    ]

];

