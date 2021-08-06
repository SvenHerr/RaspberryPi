#Amison ST-ZV390-ZX 5x Pyroelectrische Infrarot PIR Bewegung Sensor Detektor Modul
#https://www.youtube.com/watch?v=Tw0mG4YtsZk&ab_channel=TechWithTim

from gpiozero import LED
from gpiozero import MotionSensor

green_led = LED(17)
pir = MotionSensor(4)
green_led.off()

while True:
        pir.wait_for_motion()
        print("Motion Detected")
        green_led.on()
        pir.wait_for_no_motion()
        green_led.off()
        print("Motion Stopped")
