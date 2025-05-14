<?php

return [
    ['GET', '/',                            ['App\Controllers\ListController', 'index']],
    ['GET', '/showdeleted',                 ['App\Controllers\ListController', 'showdeleted']],
    ['GET', '/showxml',                     ['App\Controllers\ListController', 'showXml']],
    ['GET', '/install/{dbase}',             ['App\Controllers\InstallController', 'index']],
    ['GET', '/delete/{id}',                 ['App\Controllers\MaintainController', 'delete']],
    ['GET', '/remove/{id}',                 ['App\Controllers\MaintainController', 'remove']],
    ['GET', '/activate/{id}',               ['App\Controllers\MaintainController', 'activate']],
    ['GET', '/add',                         ['App\Controllers\MaintainController', 'verifyadd']],
    ['POST', '/add',                         ['App\Controllers\MaintainController', 'add']],
    ['GET', '/redirect',                    ['App\Controllers\RedirectController', 'redirect']],
];
