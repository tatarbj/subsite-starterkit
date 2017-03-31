node {

    // Requires "Pipeline Utility Steps" plugin.
    def defaults = readProperties file: 'build.properties.dist'

    if (!fileExists('build.properties')){
        echo 'File build.properties not found, loading build.properties.dist.'
        def props = readProperties defaults: defaults'
    }
    else {
        echo 'File build.properties found, merging with build.properties.dist.'
        def props = readProperties defaults: defaults, file: 'build.properties'
    }

    // Load needed properties into environment variables.
    env.PROJECT_ID = props["project.id"]
    env.PLATFORM_PACKAGE_REFERENCE = props["platform.package.reference"]
    env.SUBSITE_NAME = props["subsite.name"]
    env.PHING_PROJECT_BUILD_DIR = props["phing.project.build.dir"]

    Random random = new Random()
    tokens = "${env.WORKSPACE}".tokenize('/')
    def date = sh(returnStdout: true, script: 'date +%Y%m%d%H%M%S')
    env.SITE_PATH = tokens[tokens.size()-1]
    env.HTTP_MOCK_PORT = random.nextInt(50000) + 10000
    if (env.WD_PORT == '0') {
        env.WD_PORT = env.HTTP_MOCK_PORT.toInteger() + 1
    }
    env.WD_HOST_URL = "http://${env.WD_HOST}:${env.WD_PORT}/wd/hub"
    env.DB_NAME = "${env.PROJECT_ID}".replaceAll('-','_').trim() + '_' + sh(returnStdout: true, script: 'date | md5sum | head -c 4').trim()
    env.RELEASE_NAME = "${env.PROJECT_ID}_" + "${date}".trim() + "_${env.PLATFORM_PACKAGE_REFERENCE}"
    def buildLink = "<${env.BUILD_URL}consoleFull|${env.PROJECT_ID} #${env.BUILD_NUMBER}>"

    stage('Init') {
        deleteDir()
        checkout scm
        setBuildStatus("Build started.", "PENDING");
        slackSend color: "good", message: "${env.SUBSITE_NAME} build ${buildLink} started."
    }

    try {
        wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {
            stage('Check') {
                sh 'composer install --no-suggest --no-interaction --ansi'
                //sh 'COMPOSER_CACHE_DIR=/dev/null composer install --no-suggest --no-interaction --ansi'
                sh './bin/phing setup-php-codesniffer quality-assurance -logger phing.listener.AnsiColorLogger'
            }


            stage('Build') {
                withCredentials([
                    [$class: 'UsernamePasswordMultiBinding', credentialsId: 'mysql', usernameVariable: 'DB_USER', passwordVariable: 'DB_PASS']
                ]) {
                    sh "./bin/phing build-dev -D'behat.base_url'='$BASE_URL/$SITE_PATH/platform/' -logger phing.listener.AnsiColorLogger"
                    sh "./bin/phing install-dev -D'drupal.db.name'='$DB_NAME' -D'drupal.db.user'='$DB_USER' -D'drupal.db.password'='$DB_PASS' -logger phing.listener.AnsiColorLogger"
                }
            }

            stage('Test') {
                timeout(time: 2, unit: 'HOURS') {
                    if (env.WD_BROWSER_NAME == 'phantomjs') {
                        sh "phantomjs --webdriver=${env.WD_HOST}:${env.WD_PORT} &"
                    }
                    sh "./bin/behat -c tests/behat.yml --colors --strict"
                }
            }

            stage('Package') {
                sh "./bin/phing build-dist -logger phing.listener.AnsiColorLogger"
                sh "cd ${PHING_PROJECT_BUILD_DIR}"

                sh "tar -czf ${env.RELEASE_PATH}/${env.RELEASE_NAME}.tar.gz ."
                setBuildStatus("Build complete.", "SUCCESS");
                slackSend color: "good", message: "${env.SUBSITE_NAME} build ${buildLink} completed."
            }
        }
    } catch(err) {
        setBuildStatus("Build failed.", "FAILURE");
        slackSend color: "danger", message: "${env.PROJECT_ID} build ${buildLink} failed."
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

