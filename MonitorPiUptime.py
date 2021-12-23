#!/usr/bin/python3

import shlex, subprocess
cmd = "uptime -p"
args = shlex.split(cmd)
p = subprocess.Popen(args, stdout=subprocess.PIPE)
output = p.communicate()
print (output)


Found here: https://forums.raspberrypi.com/viewtopic.php?t=164276
