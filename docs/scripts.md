## Composer and/or git hook scripts

Only scripts that are executable will be actually executed.

### Composer hooks:
You can add scripts to the `resources/composer/scripts/<hook-name>/` folder. These
scripts will be executed in alphabetical order. The enabled composer hooks are:
- pre-install-cmd
- post-install-cmd
- pre-update-cmd
- post-update-cmd

### Git hooks:
You can add scripts to the `resources/git/hooks/<hook-name>/`
directory for each hook defined in `.git/hooks/<hook-name>.sample`. Upon the
command `composer install` any folder that contains scripts will have its hook
activated. Available hooks are:
- applypatch-msg
- commit-msg
- post-update
- pre-applypatch
- pre-commit
- prepare-commit-msg
- pre-push
- pre-rebase
- update
