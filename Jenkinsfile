#!groovy

node('master') {
    wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {
        stage('Init') {
            deleteDir()
            checkout scm

            if (!fileExists('build.properties')) {
                echo "File build.properties not found. Can not proceed."
                exit
            }
            echo "File build.properties found, merging with build.properties.dist."
            def defaults = readProperties file: 'build.properties.dist'
            def props = readProperties defaults: defaults, file: 'build.properties'

            // Load needed properties into environment variables.
            env.PROJECT_ID = props["project.id"]
            env.PLATFORM_PACKAGE_REFERENCE = props["platform.package.reference"]
            env.SUBSITE_NAME = props["subsite.name"]

            env.WD_HOST_URL = "http://127.0.0.1:8647/wd/hub"
            env.BUILD_ID_UNIQUE = "${env.PROJECT_ID}".replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date | md5sum | head -c 5').trim()
            env.RELEASE_NAME = "${env.PROJECT_ID}_" + sh(returnStdout: true, script: 'date +%Y%m%d%H%M%S').trim() + "_${env.PLATFORM_PACKAGE_REFERENCE}"
            env.RELEASE_PATH = "/var/jenkins_home/releases/${env.PROJECT_ID}"
            env.BUILDLINK = "<${env.BUILD_URL}consoleFull|${env.PROJECT_ID} #${env.BUILD_NUMBER}>"

            setBuildStatus("Build started.", "PENDING");
            slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} started."
            sh "docker run --name $BUILD_UNIQUE_ID -p 127.0.0.1:80:80 -v ${env.WORKSPACE}:/web -v/var/jenkins_home/cache:/var/jenkins_home/cache -w /web -d dev-server:latest"
            //sh "./bin/phing start-container -D'jenkins.cache.dir'='/var/jenkins_home/cache' -D'jenkins.workspace.dir'='${env.WORKSPACE}' -D'docker.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
        }

        try {
            stage('Check') {
                //sh "docker exec -u jenkins $BUILD_ID_UNIQUE composer clear-cache"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE composer install --no-suggest --no-interaction --ansi"
                //sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing setup-php-codesniffer quality-assurance -logger phing.listener.AnsiColorLogger"
            }


            stage('Build') {
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing build-dev -logger phing.listener.AnsiColorLogger"
            }

            stage('Test') {
                sh "./bin/phing start-container -D'jenkins.cache.dir'='/var/jenkins_home/cache' -D'jenkins.workspace.dir'='${env.WORKSPACE}' -D'docker.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing install-dev -D'drupal.db.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing setup-behat -logger phing.listener.AnsiColorLogger"
                timeout(time: 2, unit: 'HOURS') {
                    sh "docker exec -u jenkins $BUILD_ID_UNIQUE phantomjs --webdriver=127.0.0.1:8643 &"
                    sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/behat -c tests/behat.yml --colors --strict"
                }
            }

            stage('Package') {
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing build-release -logger phing.listener.AnsiColorLogger"
                setBuildStatus("Build complete.", "SUCCESS");
                slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} completed."
            }
        } catch(err) {
            setBuildStatus("Build failed.", "FAILURE");
            slackSend color: "danger", message: "${env.PROJECT_ID} build ${env.BUILDLINK} failed."
            throw(err)
        } finally {
            sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing drush-sql-drop"
            sh "docker stop $BUILD_ID_UNIQUE && docker rm $(docker ps -aq)"
            //sh "./bin/phing stop-container -D'docker.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
        }
    }
}

void setBuildStatus(String message, String state) {
    step([
        $class: "GitHubCommitStatusSetter",
//        contextSource: [$class: "ManuallyEnteredCommitContextSource", context: "${env.BUILD_CONTEXT}"],
        errorHandlers: [[$class: "ChangingBuildStatusErrorHandler", result: "UNSTABLE"]],
        statusResultSource: [$class: "ConditionalStatusResultSource", results: [[$class: "AnyBuildResult", message: message, state: state]]]
    ]);
}
