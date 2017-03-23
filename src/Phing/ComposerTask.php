<?php

namespace NextEuropa\Phing;

require_once "phing/Task.php";

/**
 * Extension on the phing composer task that checks for paths.
 */
class ComposerFallbackTask extends \ComposerTask
{
    /**
     * Executes the Composer task.
     */
    public function main()
    {
        $composerFile = new SplFileInfo($this->getComposer());
        if (false === $composerFile->isFile()) {
            exec('which composer', $composerLocation, $return);
            if ($return === 0) {
              $this->setComposer($composerLocation[0]);
            }
            else {
              throw new BuildException(sprintf('Composer binary not found, path is "%s"', $composerFile));
            }
        }

        $commandLine = $this->prepareCommandLine();
        $this->log("executing " . $commandLine);

        passthru($commandLine, $return);

        if ($return > 0) {
            throw new BuildException("Composer execution failed");
        }
    }
}
