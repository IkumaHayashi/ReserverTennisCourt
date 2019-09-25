# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
# NOTE: DO *NOT* EDIT THIS FILE.  IT IS GENERATED.
# PLEASE UPDATE Dockerfile.txt INSTEAD OF THIS FILE
# !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
FROM selenium/node-chrome-debug:3.141.59-titanium
LABEL authors=SeleniumHQ

USER root
RUN apt-get update
RUN apt-get install -y php7.2 php7.2-common php7.2-cli php7.2-fpm php7.2-mysql \
                        php7.2-dev php7.2-mbstring php7.2-zip php7.2-curl \
                        vim composer

USER seluser

#====================================
# Scripts to run Selenium Standalone
#====================================
COPY start-selenium-standalone.sh /opt/bin/start-selenium-standalone.sh

#==============================
# Supervisor configuration file
#==============================
COPY selenium.conf /etc/supervisor/conf.d/


EXPOSE 4444
EXPOSE 5900
