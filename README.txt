## Recipe Parser
A self hosted recipe parser and browser.

Given a URL to a valid recipe, it will parse (if available):
- Cover image
- Ingredients
- Steps
- Equipment
- Notes
- Nutrition

Currently the parser looks for specific frameworks that many websites currently use from providers such as WordPress. 

## Technologies
* [bs4](https://www.crummy.com/software/BeautifulSoup/)
* [requests](https://pypi.org/project/requests/)
* [bootstrap](https://getbootstrap.com/)

## Usage
1. Install the required packages `pip install -r requirements.txt`.
2. Setup the database `python ./scripts/setup-db.py`.
3. Add the `www/` to a php enabled apache site.