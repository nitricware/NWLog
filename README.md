# NWLog 1.0.2
## Introduction
NWLog is a set of functions that allows you to easily log status in your script.
## License
The NWLog system is distributed under the **MIT License** which allows you to use it privately and commercially, to distribute, modify and sublicense it. You may not hold me liable and must include my name in the credits of your work.
NWLog was created by **Kurt Höblinger** as **NitricWare**.
## Usage
Use *Composer* to install NWLog to your project via the ```composer require nitricware/nwlog``` command.
Alternatively you can also just include the NWLog.php-file.
```php
require "./path/to/NWLog.php“;
use NitricWare\NWWriteLog;
use NitricWare\NWDeleteLog;
use NitricWare\NWPrintLog;
```
## Functions
For information about the functions, please check the documentation inside the .php-file!
### With NWLog you can:
* log status
* read log
* delete log

## Changelog
v1.0.2
- added custom backtrace
- bugfixes

v1.0.1
- bugfixes

v1.0
- initial release