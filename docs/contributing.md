## Contributing

### Contributing as non-maintainer:
1. Create a multisite ticket with the following values:
  * A short but descriptive title starting with “Feature: ”, “Bug: ” or “Task: “.
  * An extensive description of what is the exact problem or requested functionality.
  * The correct ticket type, “New feature”, Bug” or “Task”.
  * For fix versions enter “subsite-starterkit” and “yourproject”.
  * Add a link to the repository url of your subsite-starterkit fork on github.
2. In your fork of the subsite-starterkit create a branch called
{type}/{TICKETNUMBER} from within the “develop” branch. Where {type} stands for
feature, bugfix or task depending on the type of ticket you created. And as
{ticketnumber} add MULTISITE-XXXXX.
3. Create a pull request from that branch of your forked subsite-starterkit to the
develop branch of the subsite-starterkit project:
  *always allow maintainers of the project to commit to the branch of your fork.
4. Add a link to the pull request on the multisite ticket.
Note:
> external pull requests always have to be made on the “develop” branch. The
maintainers may choose to reallocate the pull request to the next minor release
branch (2.X.0). Others will be merged straight in to “develop” and frequently released
with a new revision number (2.1.X).

### Contributing as a maintainer:
1. Create a multisite ticket with the following values:
  * A short but descriptive title starting with “Feature: ”, “Bug: ” or “Task: “.
  * An extensive description of what is the exact problem or requested functionality.
  * The correct ticket type, “New feature”, Bug” or “Task”.
  * For fix versions enter “subsite-starterkit” and “projects”.
  * Add a link to the repository url of the subsite-starterkit on github.
2. Add a branch called {type}/{TICKETNUMBER} from within the develop branch.
3. Create a pull request to the develop or release branch accordingly.
4. Add a link to the pull request on the multisite ticket.
Note:
> to work on an external pull request you have to clone the forked repository
mentioned on the ticket and checkout the correct branch.

