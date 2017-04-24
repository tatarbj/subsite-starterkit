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

        def siteName = props['subsite.name']
        def buildId = props['project.id'].replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date |  md5sum | head -c 5').trim()
        def buildLink = "<${env.BUILD_URL}consoleFull|${props['project.id']} #${env.BUILD_NUMBER}>"
        def releaseName = props['project.id'] + "_" + sh(returnStdout: true, script: 'date +%Y%m%d%H%M%S').trim() + "_${props['platform.package.reference']}"
        def releasePath = "/usr/share/subsites/releases/${props['project.id']}"

        withEnv([
            "WORKSPACE=${env.WORKSPACE}",
            "WD_HOST_URL=http://127.0.0.1:8647/wd/hub",
            "BUILD_ID_UNIQUE=${buildId}",
        ]) {

            stage('Init') {
                deleteDir()
                checkout scm
                setBuildStatus("Build started.", "PENDING");
                slackSend color: "good", message: "${siteName} build ${buildLink} started."
                sh "docker-compose -f resources/docker/docker-compose.yml up -d"
             }

            try {
                stage('Check') {
                    dockerExecute('composer', 'install --no-suggest --no-interaction')
                    //dockerExecute('./bin/phing', 'setup-php-codesniffer quality-assurance') 
                }


                stage('Build') {
                    dockerExecute('./bin/phing', "build-dev -D'behat.wd_host.url'='http://127.0.0.1:4444/wd/hub' -D'behat.browser.name'='chrome'")
                }

                stage('Test') {
                    dockerExecute('./bin/phing', "install-dev -D'drupal.db.host'='mysql' -D'drupal.db.name'='${env.BUILD_ID_UNIQUE}'")
                    dockerExecute('./bin/phing', 'setup-behat')
                    timeout(time: 2, unit: 'HOURS') {
                        //dockerExecute('phantomjs', '--webdriver=127.0.0.1:8643 &')
                        dockerExecute('./bin/behat', '-c tests/behat.yml --strict')
                    }
                }

                stage('Package') {
                    dockerExecute('./bin/phing', "build-release -D'project.release.path'='${releasePath}' -D'project.release.name'='${releaseName}'")
                    setBuildStatus("Build complete.", "SUCCESS");
                    slackSend color: "good", message: "${siteName} build ${buildLink} completed."
                }
            } catch(err) {
                setBuildStatus("Build failed.", "FAILURE");
                slackSend color: "danger", message: "${siteName} build ${buildLink} failed."
                throw(err)
            } finally {
                sh "docker-compose -f resources/docker/docker-compose.yml down"
            }
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

def dockerExecute(String executable, String command) {
    switch("${executable}") {
        case "./bin/phing":
            color = "-logger phing.listener.AnsiColorLogger"
            break
        case "./bin/behat":
            color = "--colors"
            break
        case "composer":
            color = "--ansi"
            break
        default:
            color = ""
            break
    }
    sh "docker exec ${BUILD_ID_UNIQUE}_php ${executable} ${command} ${color}"
}
