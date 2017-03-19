node('linux') {

    load "/var/lib/jenkins/.envvars/subsite-starterkit.groovy"
    Random random = new Random()
    env.PROJECT = 'subsite-starterkit'
    tokens = "${env.WORKSPACE}".tokenize('/')
    env.SITE_PATH = tokens[tokens.size()-1]
    env.DB_NAME = "${env.PROJECT}".replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date | md5sum | head -c 4').trim()
    env.RELEASE_NAME = "${env.JOB_NAME}".replaceAll('%2F','-').replaceAll('/','-').trim()
    env.HTTP_MOCK_PORT = random.nextInt(50000) + 10000
    if (env.WD_PORT == '0') {
        env.WD_PORT = env.HTTP_MOCK_PORT.toInteger() + 1
    }
    env.WD_HOST_URL = "http://${env.WD_HOST}:${env.WD_PORT}/wd/hub"

    stage('Init') {
        deleteDir()
        checkout scm
        setBuildStatus("Build started.", "PENDING");
        slackSend color: "good", message: "<${env.BUILD_URL}|${env.RELEASE_NAME} build ${env.BUILD_NUMBER}> started."
    }

    try {
        stage('Check') {
            sh 'composer install --no-suggest'
            sh './bin/phing setup-php-codesniffer quality-assurance'
        }


        stage('Build') {
            //sh 'COMPOSER_CACHE_DIR=/dev/null composer install --no-suggest'
            withCredentials([
                [$class: 'UsernamePasswordMultiBinding', credentialsId: 'mysql', usernameVariable: 'DB_USER', passwordVariable: 'DB_PASS']
            ]) {
                sh "./bin/phing build-dev -Dcomposer.bin=`which composer`"
                sh "./bin/phing install-dev -D'drupal.db.name'='$DB_NAME' -D'drupal.db.user'='$DB_USER' -D'drupal.db.password'='$DB_PASS'"
            }
        }

        stage('Test') {
            wrap([$class: 'AnsiColorBuildWrapper', colorMapName: 'xterm']) {
                timeout(time: 2, unit: 'HOURS') {
                    if (env.WD_BROWSER_NAME == 'phantomjs') {
                        sh "phantomjs --webdriver=${env.WD_HOST}:${env.WD_PORT} &"
                    }
                    sh './bin/phing setup-behat link-behat'
                    sh './bin/behat -c tests/behat.yml --colors --strict'
                }
            }
        }

        stage('Package') {
            sh "./bin/phing build-dist -Dcomposer.bin=`which composer`"
            sh "cd build && tar -czf ${env.RELEASE_PATH}/${env.RELEASE_NAME}.tar.gz ."
            setBuildStatus("Build complete.", "SUCCESS");
            slackSend color: "good", message: "<${env.BUILD_URL}|${env.RELEASE_NAME} build ${env.BUILD_NUMBER}> complete."
        }

    } catch(err) {
        setBuildStatus("Build failed.", "FAILURE");
        slackSend color: "danger", message: "<${env.BUILD_URL}|${env.RELEASE_NAME} build ${env.BUILD_NUMBER}> failed."
        throw(err)
    } finally {
        withCredentials([
            [$class: 'UsernamePasswordMultiBinding', credentialsId: 'mysql', usernameVariable: 'DB_USER', passwordVariable: 'DB_PASS']
        ]) {
            sh 'mysql -u $DB_USER --password=$DB_PASS -e "DROP DATABASE IF EXISTS $DB_NAME;"'
        }
    }
}

void setBuildStatus(String message, String state) {
    step([
        $class: "GitHubCommitStatusSetter",
        contextSource: [$class: "ManuallyEnteredCommitContextSource", context: "${env.BUILD_CONTEXT}"],
        errorHandlers: [[$class: "ChangingBuildStatusErrorHandler", result: "UNSTABLE"]],
        statusResultSource: [$class: "ConditionalStatusResultSource", results: [[$class: "AnyBuildResult", message: message, state: state]]]
    ]);
}

