import requests
import pandas as pd
import os
import sys
import pathlib
import mysql.connector
import math
from dotenv import load_dotenv

load_dotenv()

mydb = mysql.connector.connect(
  host=os.environ.get("DB_HOST"),
  port=os.environ.get("DB_PORT"),
  user=os.environ.get("DB_USERNAME"),
  password=os.environ.get("DB_PASSWORD"),
  database=os.environ.get("DB_DATABASE")
)

GOOGLE_MAPS_API_URL = 'https://maps.googleapis.com/maps/api/geocode/json'

input_path = sys.argv[1]
if len(sys.argv) <= 2:
    output_path = os.environ.get('OUTPUT_PATH')
else:
    output_path = sys.argv[2]
input_extension=pathlib.Path(input_path).suffix
if(not os.path.exists(input_path)):
    raise Exception("path input given is invalid")
dir_path = os.path.dirname(os.path.realpath(__file__))

df_master=pd.DataFrame()
if(input_extension == '.xlsx'):
    df_master = pd.read_excel(input_path, converters={'pin_code':str})
elif(input_extension == '.csv'):
    df_master = pd.read_csv(input_path, converters={'pin_code':str})

df = df_master.copy()

print('duplicate values in df' ,df.duplicated().sum() )
if df.duplicated().sum() > 0:
    df.drop_duplicates(inplace=True)
print('duplicate dropped \n')

lat = []
lng = []
for index, row in df.iterrows():
    if hasattr(row, 'lat') and hasattr(row, 'lng') and not math.isnan(row['lat']) and not math.isnan(row['lng']):
        print(f"{index}. Row {index+1} already has latitude, longitude")
        lat.append(row['lat'])
        lng.append(row['lng'])
    else:
        mycursor = mydb.cursor(dictionary=True)
        mycursor.execute(f"SELECT hosidno1, lat, lng FROM hospitals WHERE hosidno1 = {row['hosidno1']} AND lat IS NOT NULL AND lng IS NOT NULL AND CAST(lat as INT) <> 0 AND CAST(lng as INT) <> 0")
        hospitals = mycursor.fetchall()
        if (len(hospitals)>0):
            print(f"{index}. Row {index+1} already has latitude and longitude in hospitals table of databse")
            hospital = hospitals[0]
            lat.append(hospital['lat'])
            lng.append(hospital['lng'])
        else:
            params = {
                'key': os.environ.get('API_KEY'),
                'address': f"{row['hospital_name']}, {row['city_name']}, {row['state_name']}, India",
                'sensor': 'false',
                'region': 'india'
            }
            req = requests.get(GOOGLE_MAPS_API_URL, params=params)
            res = req.json()
            # Use the first result
            if(len(res['results'])>0):
                result = res['results'][0]
                geodata = dict()
                geodata['lat'] = result['geometry']['location']['lat']
                geodata['lng'] = result['geometry']['location']['lng']
                geodata['address'] = result['formatted_address']
                lat.append(geodata['lat'])
                lng.append(geodata['lng'])
                print(f"{index}. Find location based on {row['hospital_name']}, {row['city_name']}, {row['state_name']}, India success")
            else:
                lat.append('')
                lng.append('')
                print(f"{index}. Find location based on {row['hospital_name']}, {row['city_name']}, {row['state_name']}, India failed")

df['lat']=lat
df['lng']=lng

df.to_csv(output_path, index=False)
print("Run python script success")