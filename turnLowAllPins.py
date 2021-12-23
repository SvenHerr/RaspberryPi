#sets all GPIO pins to low.

import RPi.GPIO as GPIO
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

for i in range(1,27):
	GPIO.setup(i,GPIO.OUT)
	GPIO.output(i,GPIO.LOW)
