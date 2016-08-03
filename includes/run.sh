echo "STOP ALL WIFI"
killall mdk3

LIST_WIFI=$1

for i in `seq 1 5`;
do
    sleep 2
    echo "START WIFI $i"
    screen -md mdk3 mon0 b -v $LIST_WIFI -g -t
done

