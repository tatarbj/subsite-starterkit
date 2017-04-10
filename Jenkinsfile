node {
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
        env.BUILDLINK = "<${env.BUILD_URL}consoleFull|${env.PROJECT_ID} #${env.BUILD_NUMBER}>"
         
        setBuildStatus("Build started.", "PENDING");
        slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} started."
    }

    try {
        wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {
            stage('Check') {
                sh 'composer install --no-suggest --no-interaction --ansi'
                //sh 'COMPOSER_CACHE_DIR=/dev/null composer install --no-suggest --no-interaction --ansi'
                //sh './bin/phing setup-php-codesniffer quality-assurance -logger phing.listener.AnsiColorLogger'
            }


            stage('Build') {
                sh "./bin/phing build-dev -logger phing.listener.AnsiColorLogger"
            }

            stage('Test') {
                def workspace = pwd() 
                sh "./bin/phing start-container -D'workspace'='${workspace}' -logger phing.listener.AnsiColorLogger"
                //sh "docker build -t webserver ./resources/docker/docker-webserver"
                //sh "docker run --name dev-server -p 127.0.0.1:80:80 -v /opt/mysql:/var/lib/mysql -v ${workspace}:/web -w /web -d webserver"
                //sh "docker run --name dev-server -p 127.0.0.1:80:80 -v /opt/mysql:/var/lib/mysql -v ${workspace}:/web --env MYSQL_PASSWORD=password -w /web -d metalguardian/php-web-server"
                //sh "docker start dev-server"
                sh "docker exec dev-server ./bin/phing install-dev -D'drupal.db.host'='localhost' -D'drupal.db.name'='$DB_NAME' -D'drupal.db.user'='root' -D'drupal.db.password'='password' -logger phing.listener.AnsiColorLogger"
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
                env.RELEASE_PATH = "/var/jenkins_home/releases/${env.PROJECT_ID}"
                if (!fileExists(env.RELEASE_PATH)) {
                    sh "mkdir ${env.RELEASE_PATH}"
                }
                sh "tar -czf ${env.RELEASE_PATH}/${env.RELEASE_NAME}.tar.gz ."
                setBuildStatus("Build complete.", "SUCCESS");
                slackSend color: "good", message: "${env.SUBSITE_NAME} build ${env.BUILDLINK} completed."
            }
        }
    } catch(err) {
        setBuildStatus("Build failed.", "FAILURE");
        slackSend color: "danger", message: "${env.PROJECT_ID} build ${env.BUILDLINK} failed."
        throw(err)
    } finally {
        //sh './bin/phing stop-containers'
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

