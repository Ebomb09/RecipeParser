def parse(soup):
    recipe_name = ""
    recipe_steps = []
    recipe_ingredients = []
    recipe_equipment = []
    recipe_notes = []
    recipe_nutrition = []
    recipe_image_src = ""
    recipe_image_alt = ""
    
    parse = soup.find(class_="article-heading")

    if parse:
        recipe_name = parse.text

    parse = soup.find(class_="mm-recipes-structured-ingredients__list")

    if parse:
        for li in parse.children:
            recipe_ingredients.append(li.text)

    parse = soup.find(class_="mm-recipes-steps").find("ol")

    if parse:
        for li in parse.children:
            recipe_steps.append(li.text)

    parse = soup.find(class_="mm-recipes-nutrition-facts-summary__table-body")
    
    if parse:
        for li in parse.children:
            recipe_nutrition.append(li.text)

    parse = soup.find(class_="mntl-primary-image--blurry")

    if parse:
        recipe_image_src = parse.get("src")
        recipe_image_alt = parse.get("alt")

    return [recipe_name, recipe_steps, recipe_ingredients, recipe_equipment, recipe_notes, recipe_nutrition, recipe_image_src, recipe_image_alt]