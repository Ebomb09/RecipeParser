def parse(soup):
    recipe_name = ""
    recipe_steps = []
    recipe_ingredients = []
    recipe_equipment = []
    recipe_notes = []
    recipe_nutrition = []
    recipe_image_src = ""
    recipe_image_alt = ""
    
    parse = soup.find(id="zlrecipe-title")

    if parse:
        recipe_name = parse.text

    parse = soup.find(id="zlrecipe-ingredients-list")

    if parse:
        for li in parse.children:
            recipe_ingredients.append(li.text)

    parse = soup.find(id="zlrecipe-instructions-list")

    if parse:
        for li in parse.children:
            recipe_steps.append(li.text)

    return [recipe_name, recipe_steps, recipe_ingredients, recipe_equipment, recipe_notes, recipe_nutrition, recipe_image_src, recipe_image_alt]