status.mc-api.net
=================
A mongo based minecraft server tracker

I use this to show player counts of popular servers as well as user-submitted servers at [status.mc-api.net](http://status.mc-api.net)

Setup
-------------

You will need a working [MongoDB](http://mongodb.org) installation. You will also need to have basic knowledge with both Mongo and PHP.

To setup you will need to edit the ```status.api.php``` file and input your mongo connection details.

You will need to create a collection called tracker_servers and add the servers you want to track (see [this file](https://github.com/njb-said/status.mc-api.net/blob/master/mongo/tracker_servers.json) for an example list). You will also need a cron job running at your chosen interval to ping the servers and update the database.
