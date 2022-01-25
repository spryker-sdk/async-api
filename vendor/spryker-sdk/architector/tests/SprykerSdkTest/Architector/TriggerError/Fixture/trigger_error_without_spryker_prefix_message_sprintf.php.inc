<?php

$message = 'My Message';
trigger_error(sprintf('Foo bar %s', $message), E_USER_DEPRECATED);
-----
<?php

$message = 'My Message';
trigger_error(sprintf('Spryker: Foo bar %s', $message), E_USER_DEPRECATED);
