<?php

namespace App\Scripts;

use Composer\Script\Event;
use Exception;

class ComposerScripts
{
    /**
     * Handle the post-autoload-dump Composer event.
     *
     * @param  \Composer\Script\Event  $event
     * @return void
     */
    public static function postAutoloadDump(Event $event)
    {
        $extra = $event->getComposer()->getPackage()->getExtra();

        // Check required packages
        $requiredPackages = @$extra['laravel']['required-commands'] ?? [];
        $brokenPackages = [];
        foreach ($requiredPackages as $cmd) {
            $exists = self::commandExist($cmd);
            if (!$exists) {
                array_push($brokenPackages, $cmd);
            }
        }
        if (count($brokenPackages) > 0) {
            throw new Exception("Command `" . implode('`, `', $brokenPackages) . "` doens't exists, please install package or setting the correct environment");
        }
    }

    protected static function commandExist($cmd)
    {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));
        return !empty($return);
    }
}
