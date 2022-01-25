<?php

$message = 'My ' . 'Message';
trigger_error($message, E_USER_DEPRECATED);
-----
<?php

$message = 'Spryker: My ' . 'Message';
trigger_error($message, E_USER_DEPRECATED);
