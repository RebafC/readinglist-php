<?php

return [
    ['GET', '/',                            '\App\Controllers\MainController#index'],
    ['GET', '/delete/{id}',                 '\App\Controllers\MainController#delete'],
    ['GET', '/activate/{id}',               '\App\Controllers\MainController#activate'],
    ['GET', '/showdeleted',                 '\App\Controllers\MainController#showdeleted'],
    ['GET', '/showxml',                     '\App\Controllers\MainController#showXml'],
    ['GET', '/add[/{url}[/title/{title}]]', '\App\Controllers\MainController#add'],
    ['GET', '/redirect',                    '\App\Controllers\MainController#redirect'],
];
