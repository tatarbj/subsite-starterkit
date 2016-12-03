<?php

/**
 * @file
 * Contains NextEuropa\Phing\CheckStarterkitTask.
 */

namespace NextEuropa\Phing;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;
use QualityAssurance\Component\Console\Command\CheckStarterkitCommand;

require_once 'phing/Task.php';

/**
 * Phing task to check if the starterkit is updated to the latest version.
 */
class CheckStarterkitTask extends \Task {

  /**
   * The path to the repository of the starterkit.
   *
   * @var string
   */
  protected $starterkitRepository;

  /**
   * The starterkit branch to check.
   *
   * @var string
   */
  protected $starterkitBranch;

  /**
   * The git remote of the starterkit.
   *
   * @var string
   */
  protected $starterkitRemote;

  /**
   * The project base directory.
   *
   */
  protected $projectBasedir;

  /**
   * Runs the check-starterkit command provided by the qa-automation tools.
   *
   * @see ../../vendor/ec-europa/qa-automation/src/Command/CheckStarterkitCommand.php
   */
  public function main() {
    // Check if all required properties are present.
    $this->checkRequiredProperties();

    // Setup our qa-automation application for the check-starterkit command.
    $application = new Application();
    $application->setAutoExit(false);
    $application->add(new CheckStarterkitCommand());

    // Setup the check-starterkit command input array.
    $input = new ArrayInput(array(
      'command' => 'check-starterkit',
      '--starterkit.branch' => $this->starterkitBranch,
      '--starterkit.remote' => $this->starterkitRemote,
      '--starterkit.repository' => $this->starterkitRepository,
      '--project.basedir' => $this->projectBasedir,
      '--ansi',
      // @todo: Make interaction configurable in the build properties.
      //'--no-interaction'
    ));

    // Open up the console output.
    $output = new ConsoleOutput();

    // Run the application.
    $application->run($input, $output);
  }

  /**
   * Checks if all required properties are present.
   *
   * @throws \BuildException
   *   Thrown when a required property is not present.
   */
  protected function checkRequiredProperties() {
    $required_properties = [
      'starterkitBranch',
      'starterkitRemote',
      'starterkitRepository',
      'projectBasedir',
    ];
    foreach ($required_properties as $required_property) {
      if (empty($this->$required_property)) {
        throw new \BuildException("Missing required property '$required_property'.");
      }
    }
  }

  /**
   * Sets the git repository of the starterkit.
   *
   * @param string $starterkitRepository
   *   The path to the git repository of the starterkit.
   */
  public function setStarterkitRepository($starterkitRepository) {
    $this->starterkitRepository = $starterkitRepository;
  }

  /**
   * Sets the git branch of the starterkit that will be checked.
   *
   * @param string $starterkitBranch
   *   The branch name.
   */
  public function setStarterkitBranch($starterkitBranch) {
    $this->starterkitBranch = $starterkitBranch;
  }

  /**
   * Sets the git remote of the starterkit.
   *
   * @param string $starterkitRemote
   *   The remote name.
   */
  public function setStarterkitRemote($starterkitRemote) {
    $this->starterkitRemote = $starterkitRemote;
  }

  /**
   * Sets the project base working directory.
   *
   * @param string $projectBasedir
   *   The path to the project base working directory.
   */
  public function setProjectBasedir($projectBasedir) {
    $this->projectBasedir = $projectBasedir;
  }
}
