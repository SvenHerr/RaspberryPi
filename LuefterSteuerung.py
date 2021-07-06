import time
import RPi.GPIO as GPIO
from gpiozero import CPUTemperature
from datetime import datetime

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(24,GPIO.OUT)
isFanOn = False
GPIO.output(24,GPIO.LOW)

while True:
 cpu = CPUTemperature()
 temp = cpu.temperature
 now = datetime.now() 
 current_time = now.strftime("%H:%M:%S")
 print(current_time, temp,"'C")

 if temp > 48 and not isFanOn:
  GPIO.output(24,GPIO.HIGH)
  print(current_time,temp,"'C  greather than 48, so i turn fan on") 
  isFanOn = True
 if temp < 39 and isFanOn:
  GPIO.output(24,GPIO.LOW)
  print(current_time,temp,"'C  less than 39, so i turn fan off")
  isFanOn = False 
 else: 
  time.sleep(15)
