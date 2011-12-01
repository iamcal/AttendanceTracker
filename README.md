Raid Attendance Tracker
=======================

An experimental WoW attendance tracker I created for raids in 2007.

At the time we were using zero-sum DKP with complex attendance rules. Many players were joining and leaving mid-raid, and we wanted to correctly give points full & partial attendance. In addition, we needed to quickly know the DKP ranking by player class and tier when a loot event occured. This tool was used for about 3 months before we switched DKP systems.

It has some serious security issues (like the database password being in a text file in the webroot), but could easily be adapted to be less awful. Maybe i'll get around to it one day.


## Screenshots

* <a href="http://github.iamcal.com/AttendanceTracker/index.png">Homepage - raid & player index</a>
* <a href="http://github.iamcal.com/AttendanceTracker/raid.png">Raid summary</a>
* <a href="http://github.iamcal.com/AttendanceTracker/timeline.png">Timeline editor</a>
* <a href="http://github.iamcal.com/AttendanceTracker/attendance.png">Attendance editor</a>
* <a href="http://github.iamcal.com/AttendanceTracker/loot.png">Loot event</a>


## Installation

The database schema in <code>schema.sql</code> is for the core tables - it also assumes you have an EQDKP install with table prefix <code>eqdkp_bees_</code>. It also makes some assuptions about the way you set up tiers in EQDKP. Yeah, sorry.