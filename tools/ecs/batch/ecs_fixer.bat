:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
php vendor\bin\ecs check vendor/markocupic/contao-frontend-user-notification/src --fix --config vendor/markocupic/contao-frontend-user-notification/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-frontend-user-notification/contao --fix --config vendor/markocupic/contao-frontend-user-notification/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-frontend-user-notification/config --fix --config vendor/markocupic/contao-frontend-user-notification/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-frontend-user-notification/templates --fix --config vendor/markocupic/contao-frontend-user-notification/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-frontend-user-notification/tests --fix --config vendor/markocupic/contao-frontend-user-notification/tools/ecs/config.php
