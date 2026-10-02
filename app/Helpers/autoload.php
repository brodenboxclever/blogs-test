<?php

foreach (glob(__DIR__.'/*') as $helper_file) {
    include_once $helper_file;
}
