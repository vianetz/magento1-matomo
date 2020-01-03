Matomo Extension for Magento 1
==============================

Facts
-----
- extension key: Matomo_Analytics

Description
-----------
This module for the Magento 1 online shop software adds the Matomo analytics tracking to your shop.
It is based on the original implementy by 

Requirements
------------
- PHP >= 5.6.x
- Mage_Core

Compatibility
-------------
- Magento >= 1.7

Usage
-----
This extension adds the Matomo analytics tracking to the Magento shop.

Installation Instructions
-------------------------
For installation notes please see also [this FAQ](https://www.vianetz.com/en/faq/how-to-install-the-magento-extension.html).

1. Do a backup of your Magento installation for safety reasons.
2. Disable Magento compilation feature (if activated): System > Tools > Compiler
3. Unzip the setup package and copy the contents of the src/ folder into the Magento root folder. (The folder structure
   is the same as in your Magento installation. No files will be overwritten.)
   Please assure that the files are uploaded with the same file user permissions as the Magento installation!
4. Clear the Magento cache (and related caches like APC if available)
5. Logout from the admin panel and then login again
6. Enable the Magento compilation feature (if it was activated before): System > Tools > Compiler

As an alternative you can install the module via modman.
Please find more information about that installation method at [https://github.com/colinmollenhour/modman](https://github.com/colinmollenhour/modman)
(Thanks @colinmollenhour)

Uninstallation
--------------
1. Remove the folder ``app/code/community/Matomo/Analytics``
2. Remove the file ``app/etc/modules/Matomo_Analytics.xml``

Support
-------
If you have any issues or suggestions with this extension, please create or update a new Github issue.

Credits
-------
https://github.com/contardi/matomo-magento
https://github.com/adrianspeyer/Piwik-for-Magento

Contribution
------------
Please feel free to contribute.

License
-------
[GNU GENERAL PUBLIC LICENSE](http://www.gnu.org/licenses/gpl-3.0.txt)

This Magento Extension uses Semantic Versioning - please find more information at http://semver.org.