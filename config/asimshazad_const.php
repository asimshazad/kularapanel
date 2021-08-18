<?php

if (!defined("STATUS_ON")) define("STATUS_ON", 1);
if (!defined("STATUS_OFF")) define("STATUS_OFF", 0);
if (!defined("STATUS_DRAFT")) define("STATUS_DRAFT", 2);
/*
 * Collection custom name
 */
if (!defined("MAIN_COLLECTION_NAME")) define("MAIN_COLLECTION_NAME", "main");
if (!defined("GALLERY_COLLECTION_NAME")) define("GALLERY_COLLECTION_NAME", "gallery");
if (!defined("DEFAULT_COLLECTION_NAME")) define("DEFAULT_COLLECTION_NAME", "default");

/*
 * The key is equal to the key of the translation file asimshazad.php
 */
return [
    'status' => [
        STATUS_OFF => 'status_off',
        STATUS_ON => 'status_on',
    ]

];

