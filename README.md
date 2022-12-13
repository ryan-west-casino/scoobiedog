## Scoobiedog
Repository I use to gather slot results and save them for offline replay.

## Start
Use docker-compose up -d then bash into the container (see commands.txt) and run php artisan migrate

## Game Generation
This provides the backend to load the games (proxy in the middle) and save the results to the database, use [puppeteer](https://github.com/ryan-west-casino/puppeteer-rtg) to actually play the games automatically for them to be saved.

You can set number of slotmachine results you wish, once this number is reached it will switch state to completed for slotmachine and go to the next slotmachine you queued.

In the dump.sql.zip is a sample with around 40K [redtiger](https://redtiger.com) slotmachine results.

![backend admin](https://i.ibb.co/kHhnmzB/Screenshot-2022-12-13-at-00-00-06.png)

## Purpose
Purpose is to make offline play possible and also to understand/investigate actual RTP of slotmachines. It should not be used to steal actual game assets like bragg.games etc is doing.


## Based on [casino-dog](https://github.com/four-by-two/casino-slots-aggregation-app)

