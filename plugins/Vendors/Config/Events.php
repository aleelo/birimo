<?php

namespace Sales_and_crm\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    helper("demo_general");
});