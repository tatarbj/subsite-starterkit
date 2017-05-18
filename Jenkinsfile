
node {
  wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {
    deleteDir()
    checkout scm 
    sh '[ -d platform ] || mkdir platform'
    sh './resources/drone exec'
  }
}
