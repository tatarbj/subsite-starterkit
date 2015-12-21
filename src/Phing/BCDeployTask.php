<?php

/**
 * @file
 * Contains \NextEuropa\build\Phing\BCDeployTask.
 */

namespace NextEuropa\Phing;

use GitWrapper\GitWrapper;
use GitWrapper\GitWorkingCopy;

require_once 'phing/Task.php';

/**
 * A Phing task to generate a backwards compatible version for deployment.
 *
 * This is a temporary workaround to make sure that websites built on version
 * 2.1 of the platform can still be deployed using the scripts that were made
 * for version 2.0 and below. This will become obsolete once the deployment
 * procedure is updated to the new 2.1 workflow.
 *
 * In version 2.1 a new build system has been introduced, and the website needs
 * to be built before it can be deployed. This wasn't necessary in version 2.0
 * and before, when the development repositories only contained the code that
 * was intended to be deployed on production, exactly like the original SVN
 * based workflow that was used in the past.
 *
 * This task will create a build and push it to a temporary git repository that
 * mimics the old SVN structure.
 *
 * @see https://webgate.ec.europa.eu/CITnet/jira/browse/NEXTEUROPA-6985
 */
class BCDeployTask extends \Task {

  /**
   * The git wrapper.
   *
   * @var \GitWrapper\GitWrapper
   */
  protected $gitWrapper;

  /**
   * The git repository of the subsite.
   *
   * @var \GitWrapper\GitWorkingCopy
   */
  protected $subsiteRepository;

  /**
   * Pushes the deployment build to the temporary git repository.
   */
  public function main() {
    // Check if all required properties are present.
    $this->checkRequiredProperties();

    // Check if the master branch is checked out.
    $head = $this->subsiteRepository->getBranches()->head();
    if ($head !== 'master') {
      // throw new \BuildException("In order to deploy the 'master' branch should be checked out. Currently the '$head' branch is checked out. Please check out 'master'.");
    };

    // Do a fetch to ensure we have the latest code.
    $this->subsiteRepository->fetch();

    // Check if the master branch is up to date with the remote.
    var_dump($this->subsiteRepository->isUpToDate());
  }

  /**
   * Checks if all properties required for generating the makefile are present.
   *
   * @throws \BuildException
   *   Thrown when a required property is not present.
   */
  protected function checkRequiredProperties() {
    $required_properties = ['subsiteRepository'];
    foreach ($required_properties as $required_property) {
      if (empty($this->$required_property)) {
        throw new \BuildException("Missing required property '$required_property'.");
      }
    }
  }

  /**
   * Returns the GitWrapper singleton.
   *
   * @return \GitWrapper\GitWrapper
   *   The git wrapper.
   */
  protected function getGitWrapper() {
    if (empty($this->gitWrapper)) {
      $this->gitWrapper = new GitWrapper();
    }
    return $this->gitWrapper;
  }

  /**
   * Sets the git repository of the subsite.
   *
   * @param string $subsiteRepository
   *   The path to the git repository of the subsite.
   */
  public function setSubsiteRepository($subsiteRepository) {
    $this->subsiteRepository = $this->getGitWrapper()->workingCopy($subsiteRepository);
  }

}
