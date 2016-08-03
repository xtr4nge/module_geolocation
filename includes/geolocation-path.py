#!/usr/bin/env python

import os, sys
import time
import getopt

# ------- MENU -------
def usage():
    print "\ngeolocation-path 1.0 by xtr4nge"
    
    print "Usage: geolocation-path.py <options>\n"
    print "Options:"
    print "-i <i>, --interface=<i>                  set interface (default: mon0)"
    print "-w <seconds>, --wait=<seconds>           wait time on spot (default: 120s)"
    print "-f <file>, --file=<file>                 file containing path"
    print "-l <log>, --log=<log>                    log file"
    print "-d <dir>, --dir=<dir>                    directory [geolocation path] (default: './')"
    print "-t <num>, --threads=<num>                concurrent threads"
    print "-h                                       Print this help message."
    print ""
    print "Author: xtr4nge"
    print ""

def parseOptions(argv):
    INTERFACE = "mon0"
    WAIT =  int(120)
    FILE = ""
    LOG = ""
    DIR = "./"
    THREADS = int(5)

    try:
        opts, args = getopt.getopt(argv, "hi:w:f:d:l:t:",
                                   ["help", "interface=", "wait=", "file=", "dir=", "log=", "threads="])

        for opt, arg in opts:
            if opt in ("-h", "--help"):
                usage()
                sys.exit()
            elif opt in ("-i", "--interface"):
                INTERFACE = arg
            elif opt in ("-w", "--wait"):
                WAIT = int(arg)
            elif opt in ("-f", "--file"):
                FILE = arg
            elif opt in ("-d", "--dir"):
                DIR = arg
            elif opt in ("-l", "--log"):
                LOG = arg
                with open(LOG, 'w') as f:
                    f.write("")
            elif opt in ("-t", "--threads"):
                THREADS = int(arg)
                
        return (INTERFACE, WAIT, FILE, DIR, LOG, THREADS)
                    
    except getopt.GetoptError:           
        usage()
        sys.exit(2) 

# -------------------------
# GLOBAL VARIABLES
# -------------------------

(INTERFACE, WAIT, FILE, DIR, LOG, THREADS) = parseOptions(sys.argv[1:])

WIFI_LIST = []

with open(FILE, "r") as lines:
    for line in lines:
        line = line.strip()
        WIFI_LIST.append(DIR + line)

try:
    while True:
        for wifi in WIFI_LIST:
            print
            print "KILLALL MDK3!"
            print
            os.system("killall mdk3")
            print "RUN: " + wifi
            for i in range(int(THREADS)):
                os.system("screen -md mdk3 "+INTERFACE+" b -v "+wifi+" -g -t")
                time.sleep(1)
            print "WAIT: "+str(WAIT)+" seconds"
            time.sleep(WAIT)
except:
    os.system("killall mdk3")
    print "Bye ;)"
