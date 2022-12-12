rlxlauncher.relax = function () {
    this.game = function () {
        let cdnbase = window.location.protocol + '//' + window.location.hostname;
        let url;
        let relaxsepath = 'g?token=' + rlxlauncher.getData.token + rlxlauncher.getData.entry;
        let baseurl;
        let configurl = 'https://casino-client-api.mit.qs-gaming.com/capi/1.0/casino/games/getlauncherconfig';
        let partner = 'g?token=' + rlxlauncher.getData.token + rlxlauncher.getData.entry;
        let partnerid = 1;
        let channelpath = '-channel-undefined-';
        let channel;
        let launchermode = 'prod';
        let gameid = rlxlauncher.getData.gameid;
        let subgameid = false;
        let jurisdiction;
        let usewrapper = false;

        const legacygames = [
            'charmorama', 'jp20000', 'miniskrapet', 'mobydick', 'ubscratch',
            'dragonsrock', 'escapeartist', 'jurassic', 'ragnarok', 'winstones',
            'wisemonkeys', 'firestorm', 'sinbad',
        ];

        const nonvanilla = [
            'veikkaus', 'playtechvfop', 'playtechpop', 'norsketipping', '888bingo',
            'betvictor', 'betfair', 'paddypower', 'gamesysuk',
        ];

        const dontencode = [
            458, 459, 478, 479, 526, 527,
        ];

        const playtechpopDK = [
            500, 501, 502,
        ];

        const dontencodeticket = [
            'dragon50000', 'spiritsofthestorm'
        ];

        const wrapperjurisdictions = [
            'SE', 'DE'
        ];

        const wrappername = {
            'SE': 'rgwrapper',
            'DE': 'de-wrapper'
        };

        const noDeWrapperStudios = [
            'Quickspin', 'Push Gaming', 'Kalamba'
        ];
        if ('channel' in rlxlauncher.getData) {
            if (rlxlauncher.getData.channel == 'mobile') {
                channelpath = '';
                channel = 'mobile';
            } else if (rlxlauncher.getData.channel == 'web') {
                channelpath = '';
                channel = 'web';
            } else {
                alert('Error: incorrect channel name. Please, use "web" or "mobile"');
                channelpath = '-unsupported-channel-';
            }
        }
        // No channel parameter -> using current-device.min.js
        else {
            if (device.type == 'desktop') {
                channelpath = '';
                channel = 'web';
            } else {
                channelpath = 'mcasino';
                channel = 'mobile';
            }
        }

        baseurl = cdnbase + '/' + channelpath;

        if (gameid.indexOf('minigamearcade.') == 0) {
            let splitgameid = gameid.split('.');
            gameid = splitgameid[0];
            subgameid = splitgameid[1];
        }
        if ('partner' in rlxlauncher.getData) {
            if (nonvanilla.indexOf(rlxlauncher.getData.partner) != -1) {
                partner = rlxlauncher.getData.partner;
            } else if (channel == 'mobile' && legacygames.indexOf(rlxlauncher.getData.gameid) != -1) {
                partner = rlxlauncher.getData.partner;
            }
        }

        if ('partnerid' in rlxlauncher.getData) {
            partnerid = rlxlauncher.getData.partnerid;
        }

        if ('jurisdiction' in rlxlauncher.getData) {
            if (rlxlauncher.getData.jurisdiction.toUpperCase() == 'DK') {
                if (playtechpopDK.indexOf(parseInt(partnerid)) != -1) {
                    partner = 'playtechpop-dk';
                } else {
                    partner = 'games-dk';
                }
            } else if (rlxlauncher.getData.jurisdiction.toUpperCase() == 'SE') {
                jurisdiction = 'SE';
                relaxsepath = 'games-se';
            } else if (rlxlauncher.getData.jurisdiction.toUpperCase() == 'CA-ON') {
                if (partner == "playtechpop") {
                    partner = 'playtechpop-ca-on';
                } else {
                    partner = 'games-ca-on';
                }
            } else {
                jurisdiction = rlxlauncher.getData.jurisdiction.toUpperCase();
            }

            if ((launchermode !== 'prod') && !(nonvanilla.indexOf(rlxlauncher.getData.partner) != -1)) {
                if (rlxlauncher.getData.jurisdiction.toUpperCase() == 'MT' && partnerid != '14') {
                    partner = 'games-mt';
                } else if (rlxlauncher.getData.jurisdiction.toUpperCase() == 'GG') {
                    partner = 'games-gg';
                }
                url =
                    '&loaded=1&gameid=' +
                    gameid +
                    '&jurisdiction=' +
                    rlxlauncher.getData.jurisdiction.toUpperCase();
            } else {
                url =
                    '&loaded=1&gameid=' +
                    gameid +
                    '&jurisdiction=' +
                    rlxlauncher.getData.jurisdiction.toUpperCase();
            }
        } else {
            url = 'gameid=' + gameid;
        }
        if (subgameid) {
            url += "&subgameid=" + subgameid;
        }
        // channel param to url
        url = url + '&channel=' + channel;

        if (rlxlauncher.getData.moneymode && rlxlauncher.getData.moneymode == 'fun') {
            url = url + '&moneymode=fun';
        } else {
            url = url + '&moneymode=real';
        }

        url = url + '&partnerid=' + partnerid;
        if ('ticket' in rlxlauncher.getData) {
            if (dontencodeticket.indexOf(gameid) != -1 && partnerid == 12) {
                url += '&ticket=' + rlxlauncher.getData.ticket;
            } else {
                url = url + '&ticket=' + encodeURIComponent(rlxlauncher.getData.ticket);
            }
        }

        if ('configurl' in rlxlauncher.getData) {
            url += '&configurl=' + encodeURIComponent(rlxlauncher.getData.configurl);
        }

        if ('disableIntro' in rlxlauncher.getData) {
            url += '&disableIntro=' + rlxlauncher.getData.disableIntro;
        } else if ('disableintro' in rlxlauncher.getData) {
            url += '&disableIntro=' + rlxlauncher.getData.disableintro;
        }

        if ('lang' in rlxlauncher.getData) {
            if (rlxlauncher.getData.lang == 'no_NO') {
                lang = 'nb_NO';
            } else {
                lang = rlxlauncher.getData.lang;
            }
            url += '&lang=' + lang;
        }

        if ('currency' in rlxlauncher.getData) {
            url = url + '&currency=' + rlxlauncher.getData.currency.toUpperCase();
        }

        if ('clientid' in rlxlauncher.getData) {
            url = url + '&clientid=' + encodeURIComponent(rlxlauncher.getData.clientid);
        }

        if ('rcelapsed' in rlxlauncher.getData) {
            url = url + '&rcelapsed=' + rlxlauncher.getData.rcelapsed;
        }

        if ('rcinterval' in rlxlauncher.getData) {
            url = url + '&rcinterval=' + rlxlauncher.getData.rcinterval;
        }

        if ('rciframeurl' in rlxlauncher.getData) {
            url = url + '&rciframeurl=' + encodeURIComponent(rlxlauncher.getData.rciframeurl);
        }

        if ('rcenable' in rlxlauncher.getData) {
            url = url + '&rcenable=' + rlxlauncher.getData.rcenable;
        }

        if ('market' in rlxlauncher.getData) {
            url = url + '&market=' + rlxlauncher.getData.market;
        }

        if ('dragoffset' in rlxlauncher.getData && rlxlauncher.gameid == 'roulettenouveau') {
            url = url + '&dragoffset=' + rlxlauncher.getData.dragoffset;
        } else if (rlxlauncher.getData.gameid == 'roulettenouveau') {
            if (partnerid == 14 || partnerid == 4) {
                url = url + '&dragoffset=40';
            }
        }

        if ('rg_panic_uri' in rlxlauncher.getData) {
            url = url + '&rg_panic_uri=' + encodeURIComponent(rlxlauncher.getData.rg_panic_uri);
            usewrapper = true;
        }

        if ('rg_se_uri' in rlxlauncher.getData) {
            url = url + '&rg_se_uri=' + encodeURIComponent(rlxlauncher.getData.rg_se_uri);
            usewrapper = true;
        }

        if ('rg_st_uri' in rlxlauncher.getData) {
            url = url + '&rg_st_uri=' + encodeURIComponent(rlxlauncher.getData.rg_st_uri);
            usewrapper = true;
        }

        if ('rg_sl_uri' in rlxlauncher.getData) {
            url = url + '&rg_sl_uri=' + encodeURIComponent(rlxlauncher.getData.rg_sl_uri);
            usewrapper = true;
        }
        if ('rg_account_uri' in rlxlauncher.getData) {
            url += '&rg_account_uri=' + encodeURIComponent(rlxlauncher.getData.rg_account_uri);
        }
        if ('rg_opt_target' in rlxlauncher.getData) {
            url = url + '&rg_opt_target=' + encodeURIComponent(rlxlauncher.getData.rg_opt_target);
        }

        if ('rcshowresult' in rlxlauncher.getData) {
            url = url + '&rcshowresult=' + rlxlauncher.getData.rcshowresult;
        }

        if ('rg_embedded_frame_url' in rlxlauncher.getData) {
            url =
                url +
                '&rg_embedded_frame_url=' +
                encodeURIComponent(rlxlauncher.getData.rg_embedded_frame_url);
            usewrapper = true;
        }

        if ('rg_embedded_frame_height' in rlxlauncher.getData) {
            url = url + '&rg_embedded_frame_height=' + rlxlauncher.getData.rg_embedded_frame_height;
            usewrapper = true;
        }

        if ('fullscreen' in rlxlauncher.getData) {
            url = url + '&fullscreen=' + rlxlauncher.getData.fullscreen;
        }

        // PPOP
        if ('remoteusername' in rlxlauncher.getData) {
            url = url + '&remoteusername=' + rlxlauncher.getData.remoteusername;
        }

        if ('skinid' in rlxlauncher.getData) {
            url = url + '&skinid=' + rlxlauncher.getData.skinid;
        }

        // QS:
        if ('CurrencyFormattingOverride' in rlxlauncher.getData) {
            url =
                url +
                '&CurrencyFormattingOverride=' +
                encodeURIComponent(rlxlauncher.getData.CurrencyFormattingOverride);
        }

        if ('hidehome' in rlxlauncher.getData) {
            url = url + '&hidehome=' + rlxlauncher.getData.hidehome;
        }

        if ('enablenetposition' in rlxlauncher.getData) {
            url = url + '&enableNetPosition';
        }
        if ('enableNetPosition' in rlxlauncher.getData) {
            url = url + '&enableNetPosition';
        }

        if (partner == 'betfair') {
            url += '&leovegas';
        }

        if (wrapperjurisdictions.indexOf(jurisdiction) == -1) {
            if ('homeurl' in rlxlauncher.getData) {
                if (dontencode.indexOf(parseInt(rlxlauncher.getData.partnerid)) != -1) {
                    url = url + '&homeurl=' + rlxlauncher.getData.homeurl;
                } else {
                    url = url + '&homeurl=' + encodeURIComponent(rlxlauncher.getData.homeurl);
                }
            }

            if ('rchistoryurl' in rlxlauncher.getData) {
                url = url + '&rchistoryurl=' + encodeURIComponent(rlxlauncher.getData.rchistoryurl);
            }

            window.location.replace(baseurl + '/' + partner + url);
        } else {
            const req = new XMLHttpRequest();
            const cu = configurl;
            req.open('POST', cu, true);
            req.setRequestHeader('Content-Type', 'application/json');
            let data = {
                gameref: gameid,
            };
            req.timeout = 5000;
            req.ontimeout = function () {
                alert('Error: Game studio query Timed Out.');
            };
            req.send(JSON.stringify(data));
            req.onreadystatechange = function () {
                if (req.readyState === 4 && req.status === 200) {
                    launcherconfig = JSON.parse(req.responseText);
                    if (launcherconfig.studio == 'Quickspin') {
                        if ('homeurl' in rlxlauncher.getData) {
                            if (dontencode.indexOf(parseInt(rlxlauncher.getData.partnerid)) != -1) {
                                url = url + '&homeurl=' + rlxlauncher.getData.homeurl;
                            } else {
                                url =
                                    url +
                                    '&homeurl=' +
                                    encodeURIComponent(rlxlauncher.getData.homeurl);
                            }
                        }

                        if ('rchistoryurl' in rlxlauncher.getData) {
                            url =
                                url +
                                '&rchistoryurl=' +
                                encodeURIComponent(rlxlauncher.getData.rchistoryurl);
                        }

                        if (!('rcshowresult' in rlxlauncher.getData)) {
                            url = url + '&rcshowresult=false';
                        }
                        window.location.replace(baseurl + '/' + partner + '/' + url);
                    } else {
                        if (channel == 'mobile') {
                            url = url + '&fullscreen=false';
                        }
                        if (( jurisdiction == 'DE' ) && ( noDeWrapperStudios.indexOf(launcherconfig.studio) == -1))  {
                            usewrapper = true;
                        }
                        if (usewrapper == true) {
                            url = url.replace("?","&");
                            if ('homeurl' in rlxlauncher.getData) {
                                if (rlxlauncher.getData.homeurl.substring(0, 7) == 'action:') {
                                    url =
                                        url +
                                        '&homeurl=' +
                                        encodeURIComponent(rlxlauncher.getData.homeurl);
                                } else {
                                    url =
                                        url +
                                        '&homeurl=' +
                                        encodeURIComponent('action:') +
                                        encodeURIComponent(rlxlauncher.getData.homeurl);
                                }
                            }

                            if ('rchistoryurl' in rlxlauncher.getData) {
                                if (rlxlauncher.getData.rchistoryurl.substring(0, 7) == 'action:') {
                                    url =
                                        url +
                                        '&rchistoryurl=' +
                                        encodeURIComponent(rlxlauncher.getData.rchistoryurl);
                                } else {
                                    url =
                                        url +
                                        '&rchistoryurl=' +
                                        encodeURIComponent('action:') +
                                        encodeURIComponent(rlxlauncher.getData.rchistoryurl);
                                }
                            }

                            window.location.replace(
                                cdnbase +
                                    '/casino/' + wrappername[jurisdiction] + '/?gameUrl=' +
                                    baseurl +
                                    '/' +
                                    relaxsepath +
                                    '/' +
                                    url
                            );
                        } else {
                            if ('homeurl' in rlxlauncher.getData) {
                                url =
                                    url +
                                    '&homeurl=' +
                                    encodeURIComponent(rlxlauncher.getData.homeurl);
                            }
                            
                            if ('rchistoryurl' in rlxlauncher.getData) {
                                url =
                                    url +
                                    '&rchistoryurl=' +
                                    encodeURIComponent(rlxlauncher.getData.rchistoryurl);
                            }
                            window.location.replace(baseurl + '/' + relaxsepath + '/' + url);
                        }
                    }
                }
            };
        }
    };
};

/\//