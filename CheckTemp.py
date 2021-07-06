import time;
from gpiozero import CPUTemperature

i = 1
while i < 60:
  cpu = CPUTemperature()
  print(cpu.temperature,"'C")
  time.sleep(20)
  i += 1
