import json
from pprint import pprint

def convert(input):
    if isinstance(input, dict):
        return {convert(key): convert(value) for key, value in input.iteritems()}
    elif isinstance(input, list):
        return [convert(element) for element in input]
    elif isinstance(input, unicode):
        return input.encode('utf-8')
    else:
        return input

def jsopen(file):
	with open(file) as x:
		return convert(json.load(x))

def getZone(str):
	return [person['base'] for person in people if person['secret'] == str]

finds = jsopen('finds.json')
people = jsopen('people.json')
pts = jsopen('lats.json')

# print pts['1']['lon']

findsdict = [[0 for i in range(25)] for j in range(25)]


#### PLOTTING CONNECTIONS #####
import numpy as np
from matplotlib import pyplot as plt
fig = plt.figure()
ax = fig.add_subplot(111)
ax.get_xaxis().get_major_formatter().set_scientific(False)
ax.get_yaxis().get_major_formatter().set_scientific(False)


center = np.array([59.3262,18.0723])
yz = 0.015
xz = 0.0258
im = plt.imread('map.png')
ax.imshow(im,aspect='auto', extent=[center[1]-xz, center[1]+xz,center[0]-yz,center[0]+yz])
#x-min, x-max, y-min, y-max

for find in finds:
	# print find
	i = (getZone(find['seeker']))[0]
	j = (getZone(find['hider']))[0]
	x1 = pts[i]['lon']
	x2 = pts[j]['lon']
	y1 = pts[i]['lat']
	y2 = pts[j]['lat']
	ax.plot([ x1, x2 ], [y1 , y2], 'r-', alpha=0.05)


for i in zip(
	[pts[str(i)]['lat'] for i in range(1,24)],
	[pts[str(i)]['lon'] for i in range(1,24)],
	[(' '+str(i)+', '+str(i+1), ' ')[i%2==0] for i in range(1,24)]
):
	plt.scatter(i[1], i[0])
	ax.annotate(i[2], xy=(i[1], i[0]), textcoords='data', fontsize=15)

plt.grid()
plt.xlabel('Longitude')
plt.ylabel('Latitude')
plt.axis([18.055, 18.085, 59.32, 59.333])
plt.axes().set_aspect('auto')
plt.show()
##################################

# i=0
# for person in sorted(people, key=lambda people: int(people['score']), reverse = True ):
# 	if (int(person['score']) > 0):
# 		print '#', i, '-', person['firstname'].capitalize(), person['score']; i+=1