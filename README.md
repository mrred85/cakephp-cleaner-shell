# CakePHP Cleaner Shell

CleanerShell is a CakePHP shell utility for cleaning "tmp", "logs", "cache", "tests" and other directories and unuseful files.

## Install Platform
- Copy the `src > Shell > CleanerShell.php` to your **Shell** folder.
- Run shell using `bin/cake cleaner` command.

### Requirements
- PHP >= 7.1.x
- CakePHP >= 3.5.x

## Example
Usage of `CleanerShell` and options:
```
$ bin/cake cleaner
Clean and remove unuseful items from project
Files: ".DS_Store", "empty", "._*"
Directories: cache, logs, sessions, tests
---------------------------------------------------------------
[A]ll
[B]ake
[C]ache
[D]S_Store
[E]mpty
[K]lear Cache
[L]ogs
[O]SX macOS dot files
[S]essions
[T]ests
[DE] DS_Store & empty
[DK] DebugKit SQLite Database
[TW] Twig View cache
---------------------------------------------------------------
[Q]uit
---------------------------------------------------------------
What do you want to clean? (A/B/C/D/E/K/L/O/S/T/Q/DE/DK/TW)
>
```

Enjoy ;)
