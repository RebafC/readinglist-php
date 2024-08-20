<?php

return [
    ['GET', '/',                            '\App\Controllers\MainController#index'],
    ['GET', '/showdeleted',                 '\App\Controllers\MainController#showdeleted'],
    ['GET', '/add[/{url}[/title/{title}]]', '\App\Controllers\MainController#add'],
    ['GET', '/redirect',                    '\App\Controllers\MainController#redirect'],
];
