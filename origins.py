import json
import math
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

finds = jsopen('finds.json')
people = jsopen('people.json')
origins = jsopen('origins.json')

labels=[]
fracs=[]
explode=[]

total = 1141 #1141 people!
countries = 127 #127 countries!

print total
for key in sorted(origins, key=origins.__getitem__, reverse=True):
	labels.append(key.title())
	# print origins[key]/total
	fracs.append(float(origins[key])/float(total))

from pylab import *


fig, ax = plt.subplots()

index = np.arange(countries)
bar_width = 0.35

opacity = 0.4
error_config = {'ecolor': '0.3'}

rects1 = plt.bar(index, fracs, bar_width,
                 alpha=opacity,
                 color='r',
                 error_kw=error_config,
                 label='Countries')


plt.xlabel('Group')
plt.ylabel('Scores')
plt.yscale('log', nonposy='clip')
plt.title('Scores by group and gender')
plt.xticks(index + bar_width, labels)
plt.legend()

# plt.tight_layout()
plt.show()

show()