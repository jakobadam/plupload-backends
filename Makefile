PLUPLOAD_JAVARUNTIME_PATH = ../plupload-java-runtime
PLUPLOAD_PATH = ../plupload

APPLET_JS_URL = https://raw.github.com/jakobadam/applet.js/master/applet.js

build:
	mkdir -p public/js
	mkdir -p public/applet

	cd $(PLUPLOAD_JAVARUNTIME_PATH); ant
	cp $(PLUPLOAD_JAVARUNTIME_PATH)/plupload.java.jar public/applet

	ln -s ../$(PLUPLOAD_PATH)/src/javascript/jquery.plupload.queue/css public/css
	ln -s ../$(PLUPLOAD_PATH)/src/javascript/jquery.plupload.queue/img public/img

	ln -s ../../$(PLUPLOAD_PATH)/src/javascript/plupload.js public/js
	ln -s ../../$(PLUPLOAD_PATH)/src/javascript/plupload.html4.js public/js
	ln -s ../../$(PLUPLOAD_PATH)/src/javascript/plupload.html5.js public/js
	ln -s ../../$(PLUPLOAD_JAVARUNTIME_PATH)/src/javascript/plupload.java.js public/js
	ln -s ../../$(PLUPLOAD_JAVARUNTIME_PATH)/src/javascript/dojo.plupload.queue.js public/js
	cd public/js; wget -q -N --no-check-certificate $(APPLET_JS_URL)
	mkdir uploads

clean:
	rm -rf public/js
	rm -rf public/css
	rm -rf public/img
	rm -rf uploads
