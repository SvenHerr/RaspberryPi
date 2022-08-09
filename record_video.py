# Simple Python recipe for recording video with timestamp using PiCamera + Raspberry Pi.

from picamera import PiCamera, Color
from time import sleep
from datetime import datetime as dt

with PiCamera() as camera:

    camera.rotation = 180 # omit or use 90, 180, 270 depending on setup
    camera.annotate_background = Color("black")
    start = dt.now()
    camera.start_preview()
    camera.start_recording("video.h264")

    while (dt.now() - start).seconds < 10: # records video for 10 seconds
        camera.annotate_text = dt.now().strftime("%H:%M:%S %D")
        camera.wait_recording(0.2)
    camera.stop_recording()
    
    
   # Script is from: https://gist.github.com/AO8/73947953edfd6e1e82340955c792cd9f
