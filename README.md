Magento Help Customers Extension
======================

Sends an email after a given time range to customers which failed to login.

1. A customer tries to logon several times with a wrong password
2. Failed tries will be logged into the database
3. If the logon is successful, the fail entry will be deleted from the database
4. A cron job checks for customers which failed to login every 10 minutes
5. All customers are informed by mail that they failed to logon
6. The customer uses a link in the mail to reset their password
7. Everyone is happy :)

This extension should stop frustration of customers if they fail to logon because of forgotton passwords.

By Matthias Kleine (http://mkleine.de)
