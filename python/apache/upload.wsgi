import os
import sys

SITE_ROOT = os.path.join(os.path.dirname(os.path.abspath(__file__)), '..')
sys.path.append(os.path.join(SITE_ROOT))
os.environ['SITE_ROOT'] = SITE_ROOT
from upload import app as application
