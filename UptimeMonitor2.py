import json
import subprocess
import os, time
import webservice

data = {};
value = {};
debug = "false";

def replacePoint(str):
  temp = str.replace(',','.')
  return temp

def cast(str):
  temp = str[:-1]
  return float(temp)

def castAndReplace(str):
  temp = replacePoint(str)
  return cast(temp)

def getCPU():
  CPU_Pct=(round(float(os.popen('''grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage }' ''').readline()),2))
  global data;
  value['CPU_USAGE'] = CPU_Pct
def display(val):
  return round(float(val),2)
def getRam():
  
  ramTotalGB = round(display(subprocess.check_output("free | awk 'FNR == 2 {print $2/1000000}'", shell=True)))
  Usage = subprocess.check_output("free | awk 'FNR == 2 {print $3/($3+$4)*100}'", shell=True)
  
  global data;
  value['MEM_TOTAL'] = ramTotalGB
  value['MEM_USAGE'] = display(Usage)
  value['MEM_FREE'] = 100 - display(Usage)
def getStorage():
  
  storageTotal = subprocess.check_output("df -h --total | awk  '/^total/ {print $2}'", shell=True).rstrip()
  storageUsed = subprocess.check_output("df -h --total | awk  '/^total/ {print $3}'", shell=True).rstrip()
  storageFree = subprocess.check_output("df -h --total | awk  '/^total/ {print $4}'", shell=True).rstrip()
  
  
  global data;
  value['STORAGE_TOTAL'] = castAndReplace(storageTotal)
  value['STORAGE_FREE'] = castAndReplace(storageFree)
  value['STORAGE_USAGE'] = castAndReplace(storageUsed) 

def getSys():
  runningTime = subprocess.check_output("cat /proc/uptime | awk '{print $1}'", shell=True).rstrip()
  thermal = subprocess.check_output("cat /sys/class/thermal/thermal_zone0/temp", shell=True).rstrip()
  
  global data;
  value['RUNNING_TIME'] = round(display(runningTime)/60/60,2)
  temp = str(round(float(thermal)/1000,2))
  value['TEMPERATURE'] = castAndReplace(temp)
def isProgramRunning():
  Usage = subprocess.check_output("ps up `cat daemon.pid` >/dev/null && echo 'Running' || echo 'Not running'", shell=True)
  global data;
  data['PROGRAM_CHECK'] = Usage
  #print Usage
if __name__ == "__main__":
  getCPU();
  getRam();
  getStorage();
  getSys();
  #isProgramRunning();

if debug == "true":
  #print data
  print "CPU USAGE:\t" + str(data['CPU_USAGE']) + "%";
  print "MEMORY TOTAL:\t" + str(data['MEM_TOTAL']) + "GB";
  print "MEMORY USAGE:\t" + str(display(data['MEM_USAGE'])) + "%"
  print "MEMORY USAGE:\t" + str(data['MEM_FREE']) + "%";
  print "STORAGE TOTAL:\t" + data['STORAGE_TOTAL'];
  print "STORAGE USAGE:\t" + data['STORAGE_USAGE'];
  print "STORAGE FREE:\t" + data['STORAGE_FREE'];
  print "RUNNING TIME:\t" + str(data['RUNNING_TIME']) +"Hours";
  print "CPU TEMP:\t" + str(data['TEMPERATURE']) +"C";

try:
 webservice.send('123','Magic Mirror','','1',value['CPU_USAGE'],value['MEM_TOTAL'],value['MEM_USAGE'],value['MEM_FREE'],value['STORAGE_TOTAL'],value['STORAGE_USAGE'],value['STORAGE_FREE'],value['RUNNING_TIME'],value['TEMPERATURE'])
 #print(r)
except:
  print("Error")
print("Script erfolgreich")
