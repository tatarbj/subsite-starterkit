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
            env.PHING_PROJECT_BUILD_DIR = props["phing.project.build.dir"]
            env.WD_BROWSER_NAME = "phantomjs"
            env.WD_PORT = 0
            env.WD_HOST = "127.0.0.1"
            env.TERM = "xterm"
            TERM = "xterm"

            Random random = new Random()
            tokens = "${env.WORKSPACE}".tokenize('/')
            def date = sh(returnStdout: true, script: 'date +%Y%m%d%H%M%S')
            env.SITE_PATH = tokens[tokens.size()-1]
            env.HTTP_MOCK_PORT = random.nextInt(50000) + 10000
            if (env.WD_PORT == '0') {
                 env.WD_PORT = env.HTTP_MOCK_PORT.toInteger() + 1
            }
            env.WD_HOST_URL = "http://${env.WD_HOST}:${env.WD_PORT}/wd/hub"
            env.BUILD_ID_UNIQUE = "${env.PROJECT_ID}".replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date | md5sum | head -c 5').trim()
            env.RELEASE_NAME = "${env.PROJECT_ID}_" + "${date}".trim() + "_${env.PLATFORM_PACKAGE_REFERENCE}"
            env.BUILDLINK = "<${env.BUILD_URL}consoleFull|${env.PROJECT_ID} #${env.BUILD_NUMBER}>"

            setBuildStatus("Build started.", "PENDING");
            slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} started."
        }

        try {
            stage('Check') {
                //sh 'composer clear-cache'
                sh 'composer install --no-suggest --no-interaction --ansi'
                //sh './bin/phing setup-php-codesniffer quality-assurance -logger phing.listener.AnsiColorLogger'
            }


            stage('Build') {
                sh "./bin/phing build-dev -logger phing.listener.AnsiColorLogger"
            }

            stage('Test') {
                def workspace = pwd()
                sh "./bin/phing start-container -D'jenkins.workspace.dir'='${workspace}' -D'jenkins.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing install-dev -D'drupal.db.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
                sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/phing setup-behat -logger phing.listener.AnsiColorLogger"
                timeout(time: 2, unit: 'HOURS') {
                    if (env.WD_BROWSER_NAME == 'phantomjs') {
                        sh "docker exec -u jenkins $BUILD_ID_UNIQUE phantomjs --webdriver=${env.WD_HOST}:${env.WD_PORT} &"
                    }
                    sh "docker exec -u jenkins $BUILD_ID_UNIQUE ./bin/behat -c tests/behat.yml --colors --strict"
                }
            }

            stage('Package') {
                sh "./bin/phing build-dist"
                sh "cd ${PHING_PROJECT_BUILD_DIR}"
                env.RELEASE_PATH = "/var/jenkins_home/releases/${env.PROJECT_ID}"
                if (!fileExists(env.RELEASE_PATH)) {
                    sh "mkdir -p ${env.RELEASE_PATH}"
                }
                sh "tar -czf ${env.RELEASE_PATH}/${env.RELEASE_NAME}.tar.gz ."
                setBuildStatus("Build complete.", "SUCCESS");
                slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} completed."
            }
        } catch(err) {
            setBuildStatus("Build failed.", "FAILURE");
            slackSend color: "danger", message: "${env.PROJECT_ID} build ${env.BUILDLINK} failed."
            throw(err)
        } finally {
            sh "./bin/phing stop-container -D'jenkins.container.name'='$BUILD_ID_UNIQUE' -logger phing.listener.AnsiColorLogger"
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

