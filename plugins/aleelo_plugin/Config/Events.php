<?php

namespace aleelo_plugin\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function () {
    helper("demo_general");
});