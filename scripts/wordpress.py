def parse(soup):
    recipe_name = ""
    recipe_steps = []
    recipe_ingredients = []
    recipe_equipment = []
    recipe_notes = []
    recipe_nutrition = []
    recipe_image_src = ""
    recipe_image_alt = ""

    parse = soup.find(class_="wprm-recipe-name")

    if parse:
        recipe_name = parse.text

    parse = soup.find(class_="wprm-recipe-ingredients")

    if parse:
        for li in parse.children:
            recipe_ingredients.append(li.text)

    parse = soup.find(class_="wprm-recipe-instructions")

    if parse:
        for li in parse.children:
            recipe_steps.append(li.text)

    parse = soup.find(class_="wprm-recipe-equipment")

    if parse:
        for li in parse.children:
            recipe_equipment.append(li.text)

    parse = soup.find(class_="wprm-recipe-notes")
    
    if parse:
        for li in parse.children:
            recipe_notes.append(li.text)

    parse = soup.find(class_="wprm-nutrition-label-container")
    
    if parse:
        for li in parse.children:
            recipe_nutrition.append(li.text)

    parse = soup.find(class_="wprm-recipe-image").find("img")

    if parse:
        recipe_image_src = parse.get("data-lazy-src")
        recipe_image_alt = parse.get("alt")

    return [recipe_name, recipe_steps, recipe_ingredients, recipe_equipment, recipe_notes, recipe_nutrition, recipe_image_src, recipe_image_alt]