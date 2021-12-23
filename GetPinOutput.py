#simple script that shows if pin is high or low

import RPi.GPIO as GPIO
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

for i in range(1,27):
	GPIO.setup(i,GPIO.OUT)
	if (GPIO.input(i) == 0):
		print("Pin"+str(i)+": 0")
	else:
		print("Pin"+str(i)+": 1")
