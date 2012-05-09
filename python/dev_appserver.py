#!/usr/bin/env python
import os

os.environ['ROOT_PATH'] = os.path.abspath(os.path.dirname(__file__))

from werkzeug import run_simple
from upload import app

ROOT_PATH = os.environ['ROOT_PATH']
STATIC_FILES = {
    '/': os.path.join(ROOT_PATH, 'public')
    }
run_simple('127.0.0.1', 8080, app, use_debugger=True, use_reloader=True, threaded=False, processes=1, static_files=STATIC_FILES)
