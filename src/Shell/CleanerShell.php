<?php

/**
 * Cleaner Shell
 *
 * @link https://github.com/mrred85/cakephp-cleaner-shell
 * @copyright 2016 - present Victor Rosu. All rights reserved.
 * @license Licensed under the MIT License.
 */

namespace App\Shell;

use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @package App\Shell
 */
class CleanerShell extends Shell
{
    /**
     * Main
     *
     * @return void
     */
    public function main()
    {
        $this->info('Clean and remove unuseful items from project');
        $this->info('Files: ".DS_Store", "empty", "._*"');
        $this->info('Directories: cache, logs, sessions, tests');
        $this->hr();
        $this->out('[A]ll');
        $this->out('[B]ake');
        $this->out('[C]ache');
        $this->out('[D]S_Store');
        $this->out('[E]mpty');
        $this->out('[K]lear Cache');
        $this->out('[L]ogs');
        $this->out('[O]SX / macOS dot files');
        $this->out('[S]essions');
        $this->out('[T]ests');
        $this->out('[DE] DS_Store & empty');
        $this->out('[DK] DebugKit SQLite Database');
        $this->out('[TW] Twig View cache');
        $this->hr();
        $this->out('[Q]uit', 2);

        $opts = strtoupper(
            $this->in(
                'What do you want to clean?',
                ['A', 'B', 'C', 'D', 'E', 'K', 'L', 'O', 'S', 'T', 'Q', 'DE', 'DK', 'TW']
            )
        );

        switch ($opts) {
            case 'A':
                $this->removeBake();
                $this->removeCache();
                $this->removeTwigViewCache();
                $this->removeDS();
                $this->removeEmpty();
                $this->removeLogs();
                $this->removeMacOSDot();
                $this->removeSessions();
                $this->removeTests();
                $this->removeDebugKitDB();
                break;
            case 'B':
                $this->removeBake();
                break;
            case 'C':
                $this->removeCache();
                break;
            case 'D':
                $this->removeDS();
                break;
            case 'E':
                $this->removeEmpty();
                break;
            case 'K':
                $elms = Cache::configured();
                if ($elms) {
                    $this->clearCache($elms);
                }
                break;
            case 'L':
                $this->removeLogs();
                break;
            case 'O':
                $this->removeMacOSDot();
                break;
            case 'S':
                $this->removeSessions();
                break;
            case 'T':
                $this->removeTests();
                break;
            case 'Q':
                $this->_stop();
                break;
            case 'DE':
                $this->removeDS();
                $this->removeEmpty();
                break;
            case 'DK':
                $this->removeDebugKitDB();
                break;
            case 'TW':
                $this->removeTwigViewCache();
                break;
            default:
                $this->err('You have made an invalid selection.');
        }
        $this->hr();
        $this->main();
    }

    /**
     * Remove ".DS_Store" files (macOS)
     *
     * @return void
     */
    private function removeDS()
    {
        $dir = ROOT;
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile() && $path->getFilename() == '.DS_Store') {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('DS files deleted!');
    }

    /**
     * Remove "._*" files (macOS)
     *
     * @return void
     */
    private function removeMacOSDot()
    {
        $dir = ROOT;
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile() && strpos($path->getFilename(), '._') === 0) {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Dot files deleted!');
    }

    /**
     * Remove "empty" files
     *
     * @return void
     */
    private function removeEmpty()
    {
        $dir = ROOT;
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile() && $path->getFilename() == 'empty') {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Empty files deleted!');
    }

    /**
     * Remove files from "tmp/cache" folder
     *
     * @param string|null $path Cache path
     * @return void
     */
    private function removeCache($path = null)
    {
        $dir = TMP . 'cache';
        if ($path) {
            $dir .= DS . ltrim($path, DS);
        }
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile()) {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Cache files deleted!');
    }

    /**
     * Remove files from "tmp/cache/twigView" folder
     *
     * @return void
     */
    private function removeTwigViewCache()
    {
        $dir = TMP . 'cache' . DS . 'twigView';
        if (file_exists($dir)) {
            foreach (new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $path) {
                if ($path->isFile()) {
                    $res = new File($path->getPathname(), false);
                    $res->delete();
                    $res->close();
                    $this->out($path->getPathname());
                }
            }
            foreach (new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $path) {
                if ($path->isDir()) {
                    $res = new Folder($path->getPathname(), false, 0777);
                    $res->delete();
                    $this->out($path->getPathname());
                }
            }
            $this->success('Twig view cache deleted!');
        } else {
            $this->warn('Twig view cache directory does not exists!');
        }
    }

    /**
     * Clear cache element(s)
     *
     * @param string|array $elms Cache element
     * @return void
     */
    private function clearCache($elms)
    {
        if (is_array($elms)) {
            foreach ($elms as $elm) {
                Cache::clear(false, $elm);
                $this->success('Cache ' . $elm . ' cleared!');
            }
        } elseif (is_string($elms)) {
            Cache::clear(false, $elms);
            $this->success('Cache ' . $elms . ' cleared!');
        }
    }

    /**
     * Remove files from "tmp/sessions" folder
     *
     * @return void
     */
    private function removeSessions()
    {
        $dir = TMP . 'sessions';
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile()) {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Sessions files deleted!');
    }

    /**
     * Remove files from "tmp/bake" folder
     *
     * @return void
     */
    private function removeBake()
    {
        $dir = TMP . 'bake';
        if (file_exists($dir)) {
            foreach (new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            ) as $path) {
                if ($path->isFile()) {
                    $res = new File($path->getPathname(), false);
                    $res->delete();
                    $res->close();
                    $this->out($path->getPathname());
                }
            }
            $this->success('Bake deleted!');
        } else {
            $this->warn('Bake directory does not exists!');
        }
    }

    /**
     * Remove all tests files
     *
     * @return void
     */
    private function removeTests()
    {
        $dir = TMP . 'tests';
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile()) {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Tests files deleted!');
    }

    /**
     * Remove logs
     *
     * @return void
     */
    private function removeLogs()
    {
        $dir = LOGS;
        foreach (new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $path) {
            if ($path->isFile()) {
                $res = new File($path->getPathname(), false);
                $res->delete();
                $res->close();
                $this->out($path->getPathname());
            }
        }
        $this->success('Logs deleted!');
    }

    /**
     * Remove DebugKit Database
     *
     * @return void
     */
    private function removeDebugKitDB()
    {
        $file = new File(TMP . 'debug_kit.sqlite', false);
        if ($file->exists()) {
            $file->delete();
            $this->success('DebugKit Database deleted!');
        }
        $file->close();
    }
}
