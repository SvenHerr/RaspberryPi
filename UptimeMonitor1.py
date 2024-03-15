import subprocess
import os

data = {}
value = {}

def replace_point(string):
    return string.replace(',', '.')

def cast(string):
    return float(string[:-1])

def cast_and_replace(string):
    temp = replace_point(string)
    return cast(temp)

def get_cpu():
    CPU_Pct = round(float(os.popen('''grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage }' ''').readline()), 2)
    data['CPU_USAGE'] = f"{CPU_Pct}%"
    value['CPU_USAGE'] = CPU_Pct

def display(val):
    return round(float(val), 2)

def get_ram():
    ram_total_gb = round(display(subprocess.check_output("free | awk 'FNR == 2 {print $2/1000000}'", shell=True)))
    usage = subprocess.check_output("free | awk 'FNR == 2 {print $3/($3+$4)*100}'", shell=True)
    data['MEM_TOTAL'] = f"{ram_total_gb}GB"
    value['MEM_TOTAL'] = ram_total_gb
    data['MEM_USAGE'] = f"{display(usage)}%"
    value['MEM_USAGE'] = display(usage)
    data['MEM_FREE'] = f"{100 - display(usage)}%"
    value['MEM_FREE'] = 100 - display(usage)

def get_storage():
    storage_total = subprocess.check_output("df -h --total | awk  '/^total/ {print $2}'", shell=True).rstrip().decode('utf-8')
    storage_used = subprocess.check_output("df -h --total | awk  '/^total/ {print $3}'", shell=True).rstrip().decode('utf-8')
    storage_free = subprocess.check_output("df -h --total | awk  '/^total/ {print $4}'", shell=True).rstrip().decode('utf-8')

    global data
    data['STORAGE_TOTAL'] = str(storage_total)
    value['STORAGE_TOTAL'] = cast_and_replace(storage_total)
    data['STORAGE_FREE'] = str(storage_free)
    value['STORAGE_FREE'] = cast_and_replace(storage_free)
    data['STORAGE_USAGE'] = str(storage_used)
    value['STORAGE_USAGE'] = cast_and_replace(storage_used)

def cast_and_replace(string):
    temp = replace_point(string)
    return cast(temp)

def get_sys():
    running_time = subprocess.check_output("cat /proc/uptime | awk '{print $1}'", shell=True).rstrip()
    thermal = subprocess.check_output("cat /sys/class/thermal/thermal_zone0/temp", shell=True).rstrip()
    data['RUNNING_TIME'] = f"{round(display(running_time)/60/60, 2)}Hours"
    value['RUNNING_TIME'] = round(display(running_time)/60/60, 2)
    data['TEMPERATURE'] = f"{round(float(thermal)/1000, 2)}C"
    value['TEMPERATURE'] = cast_and_replace(str(round(float(thermal)/1000, 2)))

def is_program_running():
    usage = subprocess.check_output("ps up `cat daemon.pid` >/dev/null && echo 'Running' || echo 'Not running'", shell=True)
    data['PROGRAM_CHECK'] = usage

if __name__ == "__main__":
    get_cpu()
    get_ram()
    get_storage()
    get_sys()

    print("CPU USAGE:\t", data['CPU_USAGE'])
    print("MEMORY TOTAL:\t", data['MEM_TOTAL'])
    print("MEMORY USAGE:\t", data['MEM_USAGE'])
    print("MEMORY USAGE:\t", data['MEM_FREE'])
    print("STORAGE TOTAL:\t", data['STORAGE_TOTAL'])
    print("STORAGE USAGE:\t", data['STORAGE_USAGE'])
    print("STORAGE FREE:\t", data['STORAGE_FREE'])
    print("RUNNING TIME:\t", data['RUNNING_TIME'])
    print("CPU TEMP:\t", data['TEMPERATURE'])

    # Uncomment the following block if needed
    '''
    try:
        webservice.send('token', 'Magic Mirror', '', '1', value['CPU_USAGE'], value['MEM_TOTAL'], value['MEM_USAGE'],
                        value['MEM_FREE'], value['STORAGE_TOTAL'], value['STORAGE_USAGE'], value['STORAGE_FREE'],
                        value['RUNNING_TIME'], value['TEMPERATURE'])
        print(r)
    except Exception as e:
        print("Error:", e)
    '''

    print("Script erfolgreich")
