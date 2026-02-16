pipeline {
  agent any

  environment {
    REGISTRY = "registry.local:5000"
    IMAGE = "registry.local:5000/exam-ci4"
    TEST_SERVER = "deploy@10.10.100.10"   // bisa ganti kalau mau test server lain
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Build Docker Compose') {
      steps {
        sh """
          docker compose build
        """
      }
    }

    stage('Tag Image') {
      steps {
        sh """
          docker tag exam-ci4-docker-php ${IMAGE}:sha-${GIT_COMMIT}
          docker tag exam-ci4-docker-php ${IMAGE}:${BRANCH_NAME}
        """
      }
    }

    stage('Login Registry') {
      steps {
        withCredentials([usernamePassword(credentialsId: 'registry-creds', usernameVariable: 'USER', passwordVariable: 'PASS')]) {
          sh "echo $PASS | docker login ${REGISTRY} -u $USER --password-stdin"
        }
      }
    }

    stage('Push Image') {
      steps {
        sh """
          docker push ${IMAGE}:sha-${GIT_COMMIT}
          docker push ${IMAGE}:${BRANCH_NAME}
        """
      }
    }

    stage('Deploy Test Server') {
      when {
        branch 'develop'
      }
      steps {
        sshagent(['dev-ssh-key']) {
          sh """
          ssh -o StrictHostKeyChecking=no ${TEST_SERVER} '
            cd /opt/exam &&
            docker pull ${IMAGE}:develop &&
            docker stop exam-ci4-test || true &&
            docker rm exam-ci4-test || true &&
            docker run -d --name exam-ci4-test \
              -p 8090:80 \
              ${IMAGE}:develop
          '
          """
        }
      }
    }

  }

  post {
    always {
      sh "docker logout ${REGISTRY} || true"
    }
  }
}
