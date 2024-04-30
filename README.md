# AlphaFable

### *A glimpse into a history buried underneath the sands of time à la Ozymandias.*

```
I met a traveller from an antique land,
Who said—“Two vast and trunkless legs of stone
Stand in the desert. . . . Near them, on the sand,
Half sunk a shattered visage lies, whose frown,
And wrinkled lip, and sneer of cold command,
Tell that its sculptor well those passions read
Which yet survive, stamped on these lifeless things,
The hand that mocked them, and the heart that fed;
And on the pedestal, these words appear:
My name is Ozymandias, King of Kings;
Look on my Works, ye Mighty, and despair!
Nothing beside remains. Round the decay
Of that colossal Wreck, boundless and bare
The lone and level sands stretch far away.”
```
*Ozymandias by Percy Bysshe Shelley, January 11, 1818 - Public Domain*

## Credits:
- MentalBlank *(Project Manager, Lead Coder, and Bug Fixer)*
- HellFireAE *(Website Design and Bug Fixer)*
- Shinigami *(Coder and Bug Fixer)*
- Members of CrisNMP's old forum *(Testers)*
- Artix Entertainment *(Creators of DragonFable and Intellectual Property holders)*

**I'm not going to credit myself given that none of this would have been possible had it not been for the consorted efforts of countless individuals who all wanted to see these old web flash games archived, personalized, and to have unrestricted access regardless of internet connectivity and account ownership.**

## Contact:

You can find me through the [RaGEZONE Forums](https://forum.ragezone.com/) and feel free to send me a private message or make a post on the thread.

## Changelog:

- April 21, 2024
    - Created Docker containment for the database and gamefiles.
    - Updated PHP to work on modern versions of the PHP interpreter.

## Old Readme:

Sup...

If you have any questions contact MentalBlank at: ~~mentalblank@live.com~~ *(likely defunct)*
You can also PM MentalBlank on [http://cris-is.stylin-on.me](https://web.archive.org/web/20100530035548/http://cris-is.stylin-on.me/)

The Quickest way would be via PM.

These Private server files should only be downloaded from:
[http://cris-is.stylin-on.me](https://web.archive.org/web/20100530035548/http://cris-is.stylin-on.me/) or ~~http://mental.vidyagaems.net/~~ *(defunct)*

~MentalBlank & The Team.

## Old Changelog:

These private server files were originally *(and were once recommended)* available from:

[http://cris-is.stylin-on.me](https://web.archive.org/web/20100530035548/http://cris-is.stylin-on.me/)

~~http://mental.vidyagaems.net~~ *(not archived by the Wayback Machine)*

- October 25, 2010
    - Updated client to 9.0.6
    - Added cf-characterdelete.asp
    - Added cf-expsave.asp - Can save Exp and Gold to DB but it will mess up when leveling up.
    - Modified cf-usersignup.php so that if the user navigates to the file directly it will just die...
    - Modified cf-characternew.php so that if the user navigates to the file directly it will just die...
    - Modified cf-characterload.asp so that:
        - intExpToLevel - Now is auto calculated.
        - strClassFileName - Loads class-(strClassName).swf
        - intExp, HP, intMP, intSilver, intGold, intGems, intCoins, intMaxBagSlots, intMaxBankSlots, intMaxHouseSlots, intMaxHouseItemSlots, RaceID, strRaceName, BaseClassID, ClassID, strClassName  - are now all loaded from the DB

- October 26, 2010
    - Forgot to connect cf-expsave.asp and cf-characterdelete.asp to the DB...
    - Added cf-questcomplete.asp - You can now finish that first quest! also Saves EXP and Gold
    - Added cf-loadtowninfo.asp - Successfully Loads towns :D
    - Added towns/town-oaklore.swf
    - Modified cf-characterload.asp to also load Oaklore instead of always loading the Intro
    - Added DesignNotes
    - Added Game size modifier links.

- October 27, 2010
    - Fixed the sizes
    - Dragonfable.css now loads inside the game/ folder
    - Took out the DF/ folder and made that work
    - Added databased news, Promo, Sitename, SignupSWF, and LoaderSWF

- October 28, 2010
    - Added cf-statstrain.asp
    - Added cf-statsuntrain.asp
    - Modified cf-characterload.asp - now loads all stats from the database
    - Modified cf-questload.asp - it now loads the correct quest from the database instead of always loading the Intro Quest
    - Added cf-loadwarvars.asp - Incomplete, although you will probably never use it.
        - If your try to update your stats and it crashes, dont worry its fine... the stats will be updated and your gold taken away.

- October 29, 2010
    - Modified cf-characterload.asp and cf-userlogin.asp - Now Loads Class info from DB.
    - Added the Dragon versions of Warrior, Mage & Rouge to the DB.
    - Modified cf-characterload.asp - intWIS is now loaded from the db... don't know how i missed it.
    - Modified cf-questload.asp - Now loads intro quest if quest does not exist instead of crashing
    - Modified cf-characterload.asp - Class Armor & Weapon info also load from the DB
    - Added gamefiles/pets/pet-twilly.swf - So you can fight your First monster with some creepy Moglin.
    - Added cf-changeclass.asp
    - Modified cf-characterload.asp - Hairs are now loaded from the DB (if hair does not exist 'head/M/hair-male-carefree.swf' will load)

- October 30, 2010
    - Added Quest 101 - You can now return to the Intro area without the gay story.
    - Modified cf-characterload.asp - Quest, Skill & Armor Values are now loaded from the DB.
    - Added newCharacter.fla - Maybe you want to change something...
    - The Server now works with female characters... you just need to collect the class & hair swfs
    - Updated some links on the Design notes
    - Tested cf-changeclass.asp - IT WORKS!!!!
    - Added cf-classload.asp - YES!!!!
    - Fixed cf-loadtowninfo.asp - Somewhere between October 28th and 31st I messed it up... You can load your home town properly now.
    - Added topchars.asp
    - Edited the Client to read PHP instead of ASP
    - Entire website is now PHP
    - Sign-up can now be accessed from "Create a New Account" in the client
    - Base Classes fixed

- October 31, 2010
    - Added Quest 101 - You can now return to the Intro area without the gay story.
    - Modified cf-characterload.php - Quest, Skill & Armor Values are now loaded from the DB.
    - Added newCharacter.fla - Maybe you want to change something...
    - The Server now works with female characters... you just need to collect the class & hair swfs
    - Updated some links on the Design notes
    - Added cf-classload.php
    - Fixed cf-loadtowninfo.php - Somewhere between October 28th and 31st MentalBlank messed it up... You can load your home town properly now.
    - Added topchars.php

- November 2, 2010
    - Modified - cf-changehometown.php - minor change
    - Modified - cf-characterload.php - now loads items!
    - Added - cf-getquestcounter.php
    - Finished cf-shopload.php
    - Added cf-itembuy.php
    - Added cf-itemsell.php
    - Modified - Every cf-****.php file - Error codes actually work now...
    - Modified cf-characterload.php - Fixed gold/exp error when exiting quests
    - Finished cf-expsave.php - you can now reach level 2!
    - Fixed cf-questload.php - I messed up where it changes your home town...

- November 5, 2010
    - Edited cf-questload.php - Monsters now load from the database :D
    - Added cf-interfaceload.php - Interface files will now load from the DB
    - Added some more towns and quests - monsters have not been added yet.

- November 7, 2010
    - Added cf-mergeshopload.php - You can now load merge shops... although it has bugs
    - Added cf-itemmerge.php - You can now merge stuff... although it has bugs
    - Noticed that if you want a Monster with MonsterRef 3 you also need to load MonsterRef's 0, 1 & 2...
    - Edited cf-questload.php - Fixed a monster loading bug.

- November 16, 2010
    - Added a more detailed and versatile Design Notes system.
    - Added a online user list to the homepage.
    - Updated the background skin to my own design.
    - Databased the skin, FaceBook Username, and MySpace Username.

- November 21, 2010
    - Added cf-savequeststing.php
    - Added cf-saveskillstring.php
    - Added cf-savearmorstring.php
    - Edited cf-characterload.php - Fixed Quest, Skill, and armor string loading.
    - Added Monsters to Drakaths Quest, Zorbaks Bear Quest, Renegade Ambush Quest and Hydra Bridge Quest.
    - Edited cf-itembuy.php, cf-itemmerge & cf-itemsell.php - Fixed the bugs.
    - Added cf-itemdestroy.php

- November 23, 2010
    - Edited the Game sizes to work better and are now editable by the URL
    - Dragonfable.css now loads
    - Made some changes to the client
    - Added a Latest Release Version after you login, takes the place of the status as Verified.
    - Edited all the links inside of usersignup_05.swf and made them all PHP. So this shouldn't have anymore bugs.

- November 24, 2010
    - Renamed /game/default.php to index.php
    - Added cf-bankload.php
    - Added cf-toBank.php
    - Added cf-toCharFromBank.php
    - Started work on Hair Shops

- November 27, 2010
    - Fixed cf-usersignup.php - You can now signup without any problems

- December 8, 2010
    - Added a better news system complete with a write news, delete news, and edit news option (also available with the more-news section)
    - Updated to the newest client(9.8.0) with PHP
    - Made a login system through cookies
    - Added a account manager fully equipped with a password changer, email changer and Date of Birth changer.

- December 18, 2010
    - Found 2 missing updates... it must have been lost when Cris's Forum went down...
        - November 24, 2010
        - November 27, 2010

- February 4, 2011
    - Added cf-buybankslots1.php
    - Added cf-buybankslots2.php
    - Added cf-buybankslots3.php
    - Added cf-buybagslots1.php
    - Added cf-buybagslots2.php
    - Added cf-buybagslots3.php

- February 5, 2011
    - Modified Client... cf-buybankslots2.php and cf-buybankslots3.php are no longer needed.
    - Modified Client... cf-buybagslots2.php and cf-buybagslots3.php are no longer needed.
    - Added cf-dragonhatch.php
    - Modified cf-characterload.php - Now loads set starting items and can load dragons!.
    - Modified cf-questload.php - Now you can have quest rewards!.
    - Added cf-questreward.php - Saves quest reward to your Inventory.
    - Added cf-dragontrain.php
    - Added cf-dragonuntrain.php
    - ERROR IN: cf-buybagslots1.php - Prices need to be fixed.

- February 6, 2011
    - Fixed Indentation in some files.
    - Fixed some major security issues.

- February 13, 2011
    - Added cf-dragonelement.php
    - Fixed & Added dates and times where needed.
    - Finished dcBuy.php - You can now change your class, gender and name.

- March 14, 2011
    - Added cf-dragonfeed.php -  You can feed your dragon now...
    - Added cf-dragoncustomize.php -  you can customize your dragon!!! YAY :D
    - Added New & Updated Screenshots

- March 26, 2011
    - Fixed Issues when Buying bank slots - Default Bank Slots increased to 10 (Do not change!)
    - Fixed Issues when Creating an Account.
    - Added cf-saveweaponconfig.php
    - Temporarily Fixed Inventory Glitch when merging

- March 27, 2011
    - Added cf-hairbuy.php
    - Improved Normal Shops & Merge Shops and fixed a few errors before they arised, also You shouldn't have any more Item Counts and Max Stack Size errors when merging, buying, selling and destroying items
    - Added cf-hairbuy.php

- April 20, 2011
    - Fixed cf-classload... somehow it got replaced with cf-characterload.php awhile ago
    - Added PrevClassID and Edited files where needed
    - Added BaseClassID and Edited files where needed

- April 21, 2011
    - Shinigami added to the DFPS Team
    - Added cf-hairshopload.php - Hair shops are now Complete!!! Add your own hairs.
    - Added cf-loadpvpchar.php
    - Added cf-loadpvprandom.php
    - Edited cf-questcomplete.php - Quest rewards are now selected randomly

- April 28, 2011
    - Added cf-buybagslots1.php
    - Added cf-loadfriend.php
    - Improved Items - Resistances, Stats and other Junk
    - Added Zones... look in includes/config.php to change the info.

- May 4, 2011
    - Zones Now load from the database (df_extra)
    - Fixed a Few Bugs
    - Fixed cf-characternew.php - Works fine now.
    - Removed some Crap.
    - Fixed some minor Errors
    - Fixed error when exiting Barber, Bank, Town Hall etc.
    - Fixed Bank Errors when bank is empty
    - Fixed cf-statsuntrain.php

## Old TODOs
- Fix Stat Points Remaining Glitch in stat training
- Fix Inventory Glitch when merging
- Fix Prices in cf-buybagslots1.php
- Start & Finish cf-itemexp.php
- Start & Finish cf-loadpvpdragon.php
- Start & Finish cf-questmerge.php
- Start & Finish cf-reportname.php
- Start & Finish cf-requeststatuschange.php
