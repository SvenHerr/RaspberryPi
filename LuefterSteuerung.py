import time
import RPi.GPIO as GPIO
from gpiozero import CPUTemperature

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(24,GPIO.OUT)
isFanOn = False
GPIO.output(24,GPIO.LOW)

while True:
 cpu = CPUTemperature()
 temp = cpu.temperature
 print(temp,"'C")

 if temp > 45 and not isFanOn:
  GPIO.output(24,GPIO.HIGH)
  print(temp,"'C  greather than 45, so i turn fan on") 
  isFanOn = True
 if temp < 43 and isFanOn:
  GPIO.output(24,GPIO.LOW)
  print(temp,"'C  less than 43, so i turn fan off")
  isFanOn = False 
 else: 
  time.sleep(15)
