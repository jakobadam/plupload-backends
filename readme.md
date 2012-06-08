Plupload Backends
=================

What is Plupload Backends?
--------------------------

Plupload Backends provides complete working ready to apache deploy
solutions for plupload. 

Plupload Backends currently contains backends for Java, PHP and
Python. The backends works for both the usual runtimes (html4, html5,
etc.) and the [plupload java
runtime](https://github.com/jakobadam/plupload-java-runtime) that
support unlimited sized integrity checked uploads.

Dependencies
------------
This projects assumes that [plupload](https://github.com/moxiecode/plupload) and 
[plupload java
runtime](https://github.com/jakobadam/plupload-java-runtime) is at
the same directory level as this project. 

Python
------

### Setup ###
        sudo apt-get install python-werkzeug
        make init

### Run (local python server) ###

Development server for quick start:

       python dev_appserver.py

Point browser to http://localhost:8080/index.html

### Run (apache) ###

The project comes bundled with a apache configuration for quick setup.
It uses mod wsgi:

       sudo apt-get install libapache2-mod-wsgi
       sudo a2enmod wsgi

You should symlink `python/apache/apache2.conf` from this project into the apache
`sites-available` dir. On ubuntu something like this:

       sudo ln -s ~/plupload-backends/public/python/apache/apache2.conf /etc/apache2/sites-available/plupload

Enable the configuration:

       sudo a2ensite plupload
       sudo apache2ctl restart

Point browser to http://localhost/

Note: The [bundled apache configuration](https://github.com/jakobadam/plupload-backends/blob/master/apache/apache2.conf) expects that the `python-backend` is checked out at
`/srv/www/plupload-backends`. Change the paths in the apache configuration if it is checked out elsewhere.

PHP
---

### Run (locally with XAMPP) ###

      ln -s ~/plupload-backends/public ~/xamp/htdocs/plupload

Point browser to http://localhost/plupload
