<?php

$data= json_encode($_POST).json_encode($_GET);
file_put_contents('./test.txt', $data);

return true;