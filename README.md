zeigher

  Is a Video/Text/Ebook/PDF/Music/Image platform for people who wants to share there files with the World Wide Web (or only some frinds).
  
Minimal Requirements
  - PHP Webserver (NGINX/Apache)
  - MySQL/MariaDB Database
  
Recomendet Requirements
  - PHP Webserver (NGINX/Apache)
  - MySQL/MariaDB Database
  - Linux Root Server
  - ffmpeg
  
Supported Languages
  - English
  - German/Deutsch

Install ffmpeg 
  - centos7:

  yum -y install http://li.nux.ro/download/nux/dextop/el7/x86_64/nux-dextop-release-0-5.el7.nux.noarch.rpm
  yum install ffmpeg

Nginx Config
server {
    #Redirect to HTTPS
    listen      80;
    server_name zeigher.xyz;
    rewrite     ^   https://$server_name$request_uri? permanent;
}
server {
    listen       443 ssl http2;
    server_name  zeigher.xyz;
    client_max_body_size        10G;
    ssl_certificate <your_cert>;
    ssl_certificate_key <your_key>;

    root   /var/www/html;
    index index.php;

    #When you use Let's Encrypt
    #location /.well-known {
    #    root   /var/www/cert;
    #    allow all;
    #}

    location / {
        try_files $uri /index.php?f=$uri&$args;
    }

    location ~ index\.php$ {
        allow all;
        fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        include fastcgi_params;
    }

    location ~ \. {
        internal;
    }

    error_log /var/log/nginx/zeigher.log;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

}
