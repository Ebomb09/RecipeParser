from bs4 import BeautifulSoup
import requests
import sys
import json
import wordpress, allrecipes, ziplist

# Show the command arguments
if len(sys.argv) <= 1:
    print(f"{sys.argv[0]} <url>" )
    exit(1)

# Access the website by pretending to be a different browser
link = sys.argv[1]
html = requests.get(link, headers={"User-Agent": "Firefox"}).text

# Parse the HTML
soup = BeautifulSoup(html, "html.parser")
headings = []

# Special cases
if "wordpress" in html:
    headings = wordpress.parse(soup)

elif "mntl" in html:
    headings = allrecipes.parse(soup)

elif "ziplist" in html:
    headings = ziplist.parse(soup)

# Check for the required components to constitute a recipe
recipe_name = headings[0]
recipe_steps = headings[1]
recipe_ingredients = headings[2]
recipe_equipment = headings[3]
recipe_notes = headings[4]
recipe_nutrition = headings[5]
recipe_image_src = headings[6]
recipe_image_alt = headings[7]

if recipe_name != "" and len(recipe_ingredients) > 0 and len(recipe_steps) > 0:
    recipe_name = recipe_name.replace("?", "")
    recipe_name = recipe_name.replace(":", "")
    recipe_name = recipe_name.replace("<", "")
    recipe_name = recipe_name.replace(">", "")
    recipe_name = recipe_name.replace("|", "")
    recipe_name = recipe_name.replace("/", "")
    recipe_name = recipe_name.replace("\\", "")
    recipe_name = recipe_name.replace("*", "")
    recipe_name = recipe_name.replace("\"", "")
    
    print(json.dumps({
        "name": recipe_name, 
        "ingredients": recipe_ingredients, 
        "steps": recipe_steps, 
        "equipment": recipe_equipment, 
        "notes": recipe_notes, 
        "nutrition": recipe_nutrition, 
        "image_src": recipe_image_src, 
        "image_alt": recipe_image_alt
        }))