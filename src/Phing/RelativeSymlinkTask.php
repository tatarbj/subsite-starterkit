<?php

namespace NextEuropa\Phing;

require_once 'phing/Task.php';

use SymlinkTask;
use FileSystem;
use Project;
use PhingFile;

/**
 * Generates relative symlinks based on a target / link combination.
 * Can also symlink contents of a directory, individually
 *
 * Single target symlink example:
 * <code>
 *     <rel-sym target="/some/shared/file" link="${project.basedir}/htdocs/my_file" />
 * </code>
 *
 * Symlink entire contents of directory
 *
 * This will go through the contents of "/my/shared/library/*"
 * and create a symlink for each entry into ${project.basedir}/library/
 * in a relative fashion.
 * <code>
 *     <rel-sym link="${project.basedir}/library">
 *         <fileset dir="/my/shared/library">
 *             <include name="*" />
 *         </fileset>
 *     </symlink>
 * </code>
 */
class RelativeSymlinkTask extends \SymlinkTask
{
    /**
     * Given an existing path, convert it to a path relative to a given starting path.
     *
     * @param string $endPath   Absolute path of target
     * @param string $startPath Absolute path where traversal begins
     *
     * @return string Path of target relative to starting path
     */
    public function makePathRelative($endPath, $startPath)
    {
        // Normalize separators on Windows
        if ('\\' === DIRECTORY_SEPARATOR) {
            $endPath = str_replace('\\', '/', $endPath);
            $startPath = str_replace('\\', '/', $startPath);
        }

        // Split the paths into arrays
        $startPathArr = explode('/', trim($startPath, '/'));
        $endPathArr = explode('/', trim($endPath, '/'));

        // Find for which directory the common path stops
        $index = 0;
        while (isset($startPathArr[$index]) && isset($endPathArr[$index]) && $startPathArr[$index] === $endPathArr[$index]) {
            ++$index;
        }

        // Determine how deep the start path is relative to the common path (ie, "web/bundles" = 2 levels)
        $depth = count($startPathArr) - $index;

        // Repeated "../" for each level need to reach the common path
        $traverser = str_repeat('../', $depth);

        $endPathRemainder = implode('/', array_slice($endPathArr, $index));

        // Construct $endPath from traversing to the common path, then to the remaining $endPath
        $relativePath = $traverser.('' !== $endPathRemainder ? $endPathRemainder.'/' : '');

        return '' === $relativePath ? './' : $relativePath;
    }

    /**
     * Create the actual link
     *
     * @param  string $target
     * @param  string $link
     * @return bool
     */
    protected function symlink($target, $link)
    {
        $fs = FileSystem::getFileSystem();
        $target = rtrim($this->makePathRelative($target, dirname($link)), '/');

        if (is_link($link) && @readlink($link) == $target) {
            $this->log('Link exists: ' . $link, Project::MSG_INFO);

            return true;
        }

        if (file_exists($link) || is_link($link)) {
            if (!$this->getOverwrite()) {
                $this->log('Not overwriting existing link ' . $link, Project::MSG_ERR);

                return false;
            }

            if (is_link($link) || is_file($link)) {
                $fs->unlink($link);
                $this->log('Link removed: ' . $link, Project::MSG_INFO);
            } else {
                $fs->rmdir($link, true);
                $this->log('Directory removed: ' . $link, Project::MSG_INFO);
            }
        }

        $this->log('Linking: ' . $target . ' to ' . $link, Project::MSG_INFO);

        return $fs->symlink($target, $link);
    }
}
