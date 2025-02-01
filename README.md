# Rosalana Accounts

# Publishing
To publish a new version of the package, run the following command:
```bash
git tag v0.0.1
git push origin master --tags
```


# Responses from basecamp
Basecamp by měl vracet vždy 200 i když nastane chyba v požadavku. Chyby se vrací v těle odpovědi.
Pokud Basecamp vrátí jiný status než 200, je to chyba na straně Basecampu a je třeba to nahlásit.