
node {
  wrap([$class: 'AnsiColorBuildWrapper', cxolorMapName: 'xterm']) {
    checkout scm 
    sh '[ -d platform ] || mkdir platform'
    sh './resources/drone exec'
  }
}
