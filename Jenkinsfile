#!groovy

node('master') {
    wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {

        if (!fileExists('build.properties')) {
            echo "File build.properties not found. Can not proceed."
            exit
        }
        echo "File build.properties found, merging with build.properties.dist."
        def defaults = readProperties file: 'build.properties.dist'
        def props = readProperties defaults: defaults, file: 'build.properties'


        withEnv([
            "PROJECT_ID=props['project.id']",
            "PLATFORM_PACKAGE_REFERENCE=props['platform.package.reference']",
            "SUBSITE_NAME=props['subsite.name']",

            "WD_HOST_URL=http://127.0.0.1:8647/wd/hub",
            "BUILD_ID_UNIQUE=${PROJECT_ID}".replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date |  md5sum | head -c 5').trim(),
            "RELEASE_NAME=${PROJECT_ID}_" + sh(returnStdout: true, script: 'date +%Y%m%d%H%M%S').trim() + "_${PLATFORM_PACKAGE_REFERENCE}",
            "RELEASE_PATH=/var/jenkins_home/releases/${PROJECT_ID}",
            "BUILDLINK=<${BUILD_URL}consoleFull|${PROJECT_ID} #${BUILD_NUMBER}>"

        ]) {

        stage('Init') {
            deleteDir()
            checkout scm

            setBuildStatus("Build started.", "PENDING");
            slackSend color: "good", message: "${SUBSITE_NAME} build ${BUILDLINK} started."
            //sh "docker run --name $BUILD_ID_UNIQUE -eCOMPOSER_CACHE_DIR=/var/jenkins_home/cache/composer -v ${env.WORKSPACE}:/web -v/var/jenkins_home/cache:/var/jenkins_home/cache -v /var/jenkins_home/releases:/var/jenkins_home/releases -v/usr/share/jenkins/composer:/usr/share/jenkins/composer -w /web -d dev-server:latest"
            //sh "./bin/phing start-container -D'jenkins.cache.dir'='/var/jenkins_home/cache' -D'jenkins.workspace.dir'='${envWORKSPACE}' -D'docker.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
        }

        try {
            stage('Check') {
                sh "./bin/phing start-containers -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE composer --version"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE composer install --no-suggest --no-interaction --ansi"
                //sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing setup-php-codesniffer quality-assurance -logger phing.listener.AnsiColorLogger"
            }


            stage('Build') {
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing build-dev -logger phing.listener.AnsiColorLogger"
            }

            stage('Test') {
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing install-dev -D'drupal.db.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing setup-behat -logger phing.listener.AnsiColorLogger"
                timeout(time: 2, unit: 'HOURS') {
                    sh "docker exec -u jenkins $BUILD_ID_UNIQUE phantomjs --webdriver=127.0.0.1:8643 &"
                    sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/behat -c tests/behat.yml --colors --strict"
                }
            }

            stage('Package') {
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing build-release -D'project.release.path'='${RELEASE_PATH}' -D'project.release.name'='${RELEASE_NAME}' -logger phing.listener.AnsiColorLogger"
                setBuildStatus("Build complete.", "SUCCESS");
                slackSend color: "good", message: "${SUBSITE_NAME} build ${BUILDLINK} completed."
            }
        } catch(err) {
            setBuildStatus("Build failed.", "FAILURE");
            slackSend color: "danger", message: "${PROJECT_ID} build ${BUILDLINK} failed."
            throw(err)
        } finally {
            sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing drush-sql-drop -logger phing.listener.AnsiColorLogger"
            sh "docker stop $BUILD_ID_UNIQUE && docker rm \$(docker ps -aq -f status=exited)"
            //sh "./bin/phing stop-container -D'docker.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
        }
        }
    }
}

void setBuildStatus(String message, String state) {
    step([
        $class: "GitHubCommitStatusSetter",
//        contextSource: [$class: "ManuallyEnteredCommitContextSource", context: "${BUILD_CONTEXT}"],
        errorHandlers: [[$class: "ChangingBuildStatusErrorHandler", result: "UNSTABLE"]],
        statusResultSource: [$class: "ConditionalStatusResultSource", results: [[$class: "AnyBuildResult", message: message, state: state]]]
    ]);
}
