<?php

$message = 'My '
    . 'Message '
    . 'Multiline';
trigger_error($message, E_USER_DEPRECATED);
-----
<?php

$message = 'Spryker: My '
    . 'Message '
    . 'Multiline';
trigger_error($message, E_USER_DEPRECATED);
