#!/bin/bash

# 1
gpio -g mode 4 out
gpio -g write 4 1

# 2
gpio -g mode 17 out
gpio -g write 17 1

# 3
gpio -g mode 27 out
gpio -g write 27 1

# 4
gpio -g mode 22 out
gpio -g write 22 1

# 5
gpio -g mode 24 out
gpio -g write 24 1

# 6
gpio -g mode 25 out
gpio -g write 25 1

# 7
gpio -g mode 18 out
gpio -g write 18 1

# 8
gpio -g mode 23 out
gpio -g write 23 1

exit 0
