<?php

if (!defined("STATUS_ON")) define("STATUS_ON", 1);
if (!defined("STATUS_OFF")) define("STATUS_OFF", 0);

/*
 * The key is equal to the key of the translation file kulara.php
 */
return [
    'status' => [
        STATUS_ON => ['key' => 'status_on', 'value' => STATUS_ON],
        STATUS_OFF => ['key' => 'status_off', 'value' => STATUS_OFF],
    ]

];

