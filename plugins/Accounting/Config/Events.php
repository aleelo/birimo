<?php

namespace Accounting\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    helper("accounting");
});