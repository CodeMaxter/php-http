<?php

echo '<pre>';
file_put_contents('server.txt', print_r($_POST, true));

die('data saved');