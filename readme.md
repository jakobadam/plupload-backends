Plupload Python Backend
=======================

What is Plupload Python Backend
-------------------------------

Plupload Python Backend provides a complete working ready to apache deploy
solution for plupload. 

The backend is in Python and works for both the usual runtimes and the
[Plupload Java
Runtime](https://github.com/jakobadam/plupload-java-runtime) that
support unlimited sized integrity checked uploads.

Dependencies
------------

This projects assumes that plupload and plupload-java-runtime is at
the same directory level as this project. 

Setup
-----
        sudo apt-get install python-werkzeug
        make init

Run (local python server)
-------------------------

Development server for quick start:
            python dev_appserver.py
            point browser to http://localhost:8080/index.html

Run (apache)
-----------

The project comes bundled with a complete apache configuration for
quick setup. You should take a backup of your existing
configuration and symlink `apache2.conf` from this project into the
apache dir. On ubuntu something like this:

       sudo mv /etc/apache2.conf /etc/apache2.conf.bac 
       sudo ln -s /home/www/plupload-python-runtime/apache/apache2.conf /etc/apache2/apache2.conf
       sudo apache2ctl restart

Point browser to http://localhost/

Note: The (bundled apache configuration)[https://github.com/jakobadam/plupload-python-backend/blob/master/apache/apache2.conf] expects that the `plupload-python-backend` is checked out at
`/home/www/plupload-python-backend`. Change the paths in the configuration if it is checked out elsewhere.

