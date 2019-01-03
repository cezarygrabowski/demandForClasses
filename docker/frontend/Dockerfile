FROM centos:7

RUN yum install -y gcc-c++ make git net-tools && \
    curl -sL https://rpm.nodesource.com/setup_10.x | bash - && \
    yum install -y nodejs && \
    npm install -g npm && \
    npm install -g --unsafe-perm @angular/cli

RUN useradd demand && \
    mkdir -p /var/www/frontend

ADD entrypoint.sh /home/demand/entrypoint.sh

RUN chown demand. /home/demand/entrypoint.sh

#### CHANGE WORKDIR
WORKDIR /var/www/frontend

USER demand

ENTRYPOINT /home/demand/entrypoint.sh

