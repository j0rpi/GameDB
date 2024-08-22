![Alt text](/screens/logo_github.png?raw=true "Logo")

# What is GameDB
GameDB is a personal project of mine which features one single database
for keeping tracks of games I have played. It features a simple yet
sleek design that is appealing to the eye. I was inspired by [TheKotti's](https://www.twitch.tv/thekotti "TheKotti's Twitch Channel") page where he manages the games he has played. 

GameDB also has a rating system, ability to filter by platform/system, and also
a cover art download system, thanks to IGDB.com API. 

GameDB uses PHP and SQLite3. 

# Features
- Search engine
- Ability to add/remove/modify games
- Ability to add/remove/modify categories
- Ability to add/remove/modify platforms/systems
- Rating system
- Platform system
- Administration system
- Speedrun.com / Splits.IO integration (Not yet implemented)

... and more to come i guess.

# Clean Install
Since i edit all code in the repository directly, **games.db** will also ship with GameDB. 

1. Delete **games.db**
2. Run **/install/**
3. [How to get API access from IGDB for cover search functionality. You will need **Client ID** and **Client Secret**](https://api-docs.igdb.com/#getting-started)
4. Edit **/install/generate_token.php** to match your **ClientID** and **ClientSecret**
5. Edit **/admin/coversearch.php** to match your **ClientID**, **ClientSecret** and **AccessToken**
6. Rename or delete **/install/** folder

Default admin username and password is **admin / admin**

# Screenshots 
![Alt text](/screens/index.jpg?raw=true "Index")
![Alt text](/screens/modal.jpg?raw=true "Modal")
![Alt text](/screens/admin.jpg?raw=true "Admin Index")
![Alt text](/screens/admin2.jpg?raw=true "Game Index")
![Alt text](/screens/admin3.jpg?raw=true "Categories Index")
![Alt text](/screens/admin4.jpg?raw=true "Platform Index")
![Alt text](/screens/igdb.jpg?raw=true "IGDB Cover Search")
