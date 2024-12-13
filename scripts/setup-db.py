import sqlite3
import os

os.mkdir("data")

con = sqlite3.connect("data/recipes.db")

con.execute("DROP TABLE IF EXISTS recipes")
con.execute("CREATE TABLE recipes(url, name, json)")

con.commit()