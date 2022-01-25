<?php

trigger_error('My Message ' . 'Foo ' . 'Bar', E_USER_DEPRECATED);
-----
<?php

trigger_error('Spryker: My Message ' . 'Foo ' . 'Bar', E_USER_DEPRECATED);
