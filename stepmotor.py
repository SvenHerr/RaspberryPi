#script von https://www.youtube.com/watch?v=4fHL6BpJrC4&ab_channel=TechnikTests%26Reviews
import time
import RPi.GPIO as GPIO

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)

A = 7
B = 11
C = 13
D = 15

GPIO.setup(A, GPIO.OUT)
GPIO.setup(B, GPIO.OUT)
GPIO.setup(C, GPIO.OUT)
GPIO.setup(D, GPIO.OUT)

def GPIO_SETUP(a,b,c,d):
 GPIO.output(A, a)
 GPIO.output(B, b)
 GPIO.output(C, c)
 GPIO.output(D, d)
 time.sleep(0.001)

def RIGHT_TURN(deg):

 full_circle = 510.0
 degree = full_circle/360*deg
 GPIO_SETUP(0,0,0,0)

 while degree > 0.0:
  GPIO_SETUP(1,0,0,0)
  GPIO_SETUP(1,1,0,0)
  GPIO_SETUP(0,1,0,0)
  GPIO_SETUP(0,1,1,0)
  GPIO_SETUP(0,0,1,0)
  GPIO_SETUP(0,0,1,1)
  GPIO_SETUP(0,0,0,1)
  GPIO_SETUP(1,0,0,1)
  degree -= 1

def LEFT_TURN(deg):

 full_circle = 510.0
 degree = full_circle/360*deg
 GPIO_SETUP(0,0,0,0)

 while degree > 0.0:
  GPIO_SETUP(1,0,0,1)
  GPIO_SETUP(0,0,0,1)
  GPIO_SETUP(0,0,1,1)
  GPIO_SETUP(0,0,1,0)
  GPIO_SETUP(0,1,1,0)
  GPIO_SETUP(0,1,0,0)
  GPIO_SETUP(1,1,0,0)
  GPIO_SETUP(1,0,0,0)
  degree -= 1

RIGHT_TURN(90)
#LEFT_TURN(90)
RIGHT_TURN(1000)
GPIO_SETUP(0,0,0,0)

