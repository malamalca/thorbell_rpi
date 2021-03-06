# Installation instructions


## Initial
```
sudo apt update
sudo apt full-upgrade

sudo timedatectl set-timezone Europe/Ljubljana
timedatectl status

sudo nano /boot/config.txt
   dtoverlay=disable-bt

sudo systemctl disable hciuart.service
sudo systemctl disable bluealsa.service
sudo systemctl disable bluetooth.service

sudo systemctl stop bluetooth
sudo systemctl disable avahi-daemon
sudo systemctl stop avahi-daemon
sudo systemctl disable triggerhappy
sudo systemctl stop triggerhappy

#enable www-data to reboot device
sudo visudo
   www-data ALL = NOPASSWD: /sbin/reboot, /sbin/halt

```

## Change default username PI to THORBELL

```
// set password for root
sudo passwd

sudo nano /etc/ssh/sshd_config
   PermitRootLogin yes
sudo service ssh restart


// login as root

usermod -l thorbell pi
usermod -m -d /home/thorbell thorbell
sudo passwd -l root
sudo nano /etc/ssh/sshd_config
  #PermitRootLogin yes
```

## Enable Raspicam
```
sudo nano /boot/config.txt
// add folowing lines and reboot
start_x=1
gpu_mem=128
```


## Lighttpd

```
sudo apt-get -y install lighttpd

sudo apt install php7.3 php7.3-fpm php7.3-cgi
sudo lighttpd-enable-mod fastcgi-php
sudo apt-get install php7.3-curl php7.3-mbstring php7.3-pdo php7.3-sqlite3 php7.3-xml php7.3-intl php7.3-bcmath

sudo service lighttpd force-reload

sudo nano /etc/lighttpd/lighttpd.conf

// change document root
server.document-root        = "/var/www/thorbell_rpi/webroot"
// add at the end
server.modules += ( "mod_rewrite", "mod_proxy" )
$HTTP["url"] =~ "^/stream/video.mjpeg" {
    server.stream-response-body = 1
    proxy.server = ( "" => ( ( "host" => "127.0.0.1", "port" => "9090" ) ) )
}

url.rewrite-once = (
    "^/(css|files|img|js|stats)/(.*)$" => "/$1/$2",
    "^/(?!stream[/])(.*)$" => "/index.php/$1"
)

```

### PAM for auth 
```
sudo apt-get install php-pear
sudo apt-get install php7.3-dev

sudo nano /etc/apt/sources.list
# uncomment sources
sudo apt-get build-dep pam

sudo apt-get install libpam0g-dev
sudo pecl install pam

# add extension "pam" to php.ini!!!
sudo service lighttpd restart

sudo cp /etc/pam.d/login /etc/pam.d/php
sudo nano /etc/pam.d/php
// add after "auth       requisite  pam_nologin.so"
   auth       sufficient /lib/arm-linux-gnueabihf/security/pam_unix.so shadow nodelay
   account    sufficient /lib/arm-linux-gnueabihf/security/pam_unix.so
   
sudo chgrp www-data /etc/shadow
# for debug: cat /var/log/auth.log

```

## Website
```
sudo apt install git

// to home dir
wget -O composer-setup.php https://getcomposer.org/installer
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

cd /var/www/
sudo git clone https://github.com/malamalca/thorbell_rpi
sudo chown -R www-data:www-data thorbell_rpi/
cd /var/www/thorbell_rpi
sudo -u www-data composer --no-dev update
cp /var/www/thorbell_rpi/config/app_default.php /var/www/throbell_rpi/config/app.php

```

## Samba (development)
```
sudo apt-get install samba samba-common-bin
sudo nano /etc/samba/smb.conf 
  [www]
  Comment = WWW
  Path = /home/thorbell/camera_wwwroot
  Browseable = yes
  Writeable = Yes
  only guest = no
  create mask = 0777
  directory mask = 0777
  Public = yes

sudo smbpasswd -a thorbell

sudo service smbd restart
sudo service nmbd restart
```

## UV4L
```
curl https://www.linux-projects.org/listing/uv4l_repo/lpkey.asc | sudo apt-key add -
echo "deb https://www.linux-projects.org/listing/uv4l_repo/raspbian/stretch stretch main" | sudo tee /etc/apt/sources.list.d/uv4l.list
sudo apt update
sudo apt install uv4l uv4l-raspicam uv4l-raspicam-extras
sudo apt install uv4l-webrtc
sudo service uv4l_raspicam restart
sudo service uv4l_raspicam status
sudo mv openssl.cnf /etc/uv4l/openssl.cnf
// not needed? sudo nano /etc/systemd/system/uv4l_raspicam.service 
// not needed? sudo systemctl daemon-reload && sudo service uv4l_raspicam start
openssl genrsa -out selfsign.key 2048 && openssl req -new -x509 -key selfsign.key -out selfsign.crt -sha256
mv selfsign.* /etc/uv4l/
sudo mv selfsign.* /etc/uv4l/

sudo service uv4l_raspicam start
sudo service uv4l_raspicam restart
```
```
// uv4l config
sudo cp /etc/uv4l/uv4l-raspicam.conf /etc/uv4l/uv4l-raspicam.conf.default
sudo nano /etc/uv4l/uv4l-raspicam.conf
// edit uv4l config
driver = raspicam
auto-video_nr = yes
frame-buffers = 4
encoding = mjpeg
width = 640
height = 480
framerate = 15
rotation = 180 #depending on your hardware setup
server-option = --user-password=thrcam
server-option = --port=9090
server-option = --bind-host-address=0.0.0.0
server-option = --use-ssl=no
server-option = --ssl-private-key-file=/etc/uv4l/selfsign.key
server-option = --ssl-certificate-file=/etc/uv4l/selfsign.crt
server-option = --enable-webrtc-video=no
server-option = --enable-webrtc-audio=yes
server-option = --webrtc-vad=yes
server-option = --webrtc-echo-cancellation=yes
server-option = --webrtc-max-playout-delay=34
server-option = --enable-www-server=no
server-option = --www-root-path=/usr/share/uv4l/demos/doorpi/
server-option = --www-index-file=index.html
server-option = --www-port=8888
server-option = --www-bind-host-address=0.0.0.0
server-option = --www-use-ssl=no
server-option = --www-ssl-private-key-file=/etc/uv4l/selfsign.key
server-option = --www-ssl-certificate-file=/etc/uv4l/selfsign.crt
server-option = --www-webrtc-signaling-path=/webrtc
```

## System Service
```
sudo nano thorbell.service /etc/systemd/system/thorbell.service

[Unit]
Description=Thorbell

[Service]
ExecStart=/usr/bin/php   /home/thorbell/camera_wwwroot/src/Console/thorbell.php
WorkingDirectory=/home/thorbell/camera_wwwroot
User=thorbell
Restart=always

[Install]
WantedBy=default.target  

systemctl daemon-reload
sudo systemctl enable thorbell.service
sudo systemctl start thorbell.service
```
