let rlxlauncher = {};

// GET parameters to getData using QueryData() from QueryData.compressed.js
rlxlauncher.getData = new QueryData();

function QueryData(_1,_2){
    if(_1==undefined){
    _1=location.search?location.search:"";
    }
    if(_1.charAt(0)=="?"){
    _1=_1.substring(1);
    }
    if(_1.length>0){
    _1=_1.replace(/\+/g," ");
    var _3=_1.split(/[&;]/g);
    for(var _4=0;_4<_3.length;_4++){
    var _5=_3[_4].split("=");
    var _6=decodeURIComponent(_5[0]);
    var _7=_5.length>1?decodeURIComponent(_5[1]):"";
    if(_2){
    if(!(_6 in this)){
    this[_6]=[];
    }
    this[_6].push(_7);
    }else{
    this[_6]=_7;
    }
    }
    }
    };
const removeSEbuttons = [
    102, 202, 261,
];
rlxlauncher.launchgame = function () {
    if ('jurisdiction' in rlxlauncher.getData) {
        if (
            (removeSEbuttons.indexOf(parseInt(rlxlauncher.getData.partnerid)) != -1) &&
            rlxlauncher.getData.jurisdiction.toUpperCase() == 'SE'
        ) {
            delete rlxlauncher.getData.rg_st_uri;
            delete rlxlauncher.getData.rg_sl_uri;
            delete rlxlauncher.getData.rg_se_uri;
            delete rlxlauncher.getData.rg_panic_uri;
        }
        if (
            ( rlxlauncher.getData.jurisdiction.toUpperCase() == 'DE' ) &&
            ( 'rg_panic_uri' in rlxlauncher.getData ) &&
            ( rlxlauncher.getData.gameid.indexOf('rlx.') == 0 ) &&
            !('deWrapped' in rlxlauncher.getData)
        ) {
            if ('homeurl' in rlxlauncher.getData) {
                if (!(rlxlauncher.getData.homeurl.substring(0, 7) == 'action:')) {
                    rlxlauncher.getData.homeurl = 'action:' + rlxlauncher.getData.homeurl;
                }
            }
            if ('rchistoryurl' in rlxlauncher.getData) {
                if (!(rlxlauncher.getData.rchistoryurl.substring(0, 7) == 'action:')) {
                    rlxlauncher.getData.rchistoryurl = 'action:' + rlxlauncher.getData.rchistoryurl;
                }
            }
            let wrappedurl='';
            for (let key in rlxlauncher.getData) {
                wrappedurl += key + '=' + rlxlauncher.getData[key] + '&';
            }
            window.location.replace('/casino/de-wrapper/?gameUrl=' +
               location.protocol + '//' +
               location.hostname +
               location.pathname + '&' + wrappedurl + 'deWrapped=1');
            return;
        }
    }
    if ('rciframeurl' in rlxlauncher.getData) {
        if (
            ( rlxlauncher.getData.rciframeurl.toLowerCase().indexOf('https:') !== 0 ) &&
            ( rlxlauncher.getData.rciframeurl.toLowerCase().indexOf('http:') !==0 )
        ) {
            delete rlxlauncher.getData.rciframeurl;
        }
    }
    if ('clientid' in rlxlauncher.getData) {
        if (rlxlauncher.getData.clientid == rlxlauncher.getData.ticket) {
            rlxlauncher.getData.clientid = '';
        }
    }
    if ('gameid' in rlxlauncher.getData) {
        rlxlauncher.gameid = rlxlauncher.getData.gameid;
        rlxlauncher.partnerid = rlxlauncher.getData.partnerid;

        if (rlxlauncher.gameid.indexOf('rlx.nlc.') == 0) {
            /* No Limit City */
            loadScript(
                'https://nolimitcity.github.io/nolimit.js/dist/nolimit-latest.min.js',
                function () {
                    loadScript('https://d1k6j4zyghhevb.cloudfront.net/casino/relaxlibs/rg/RgPostMessageAPI.min.js', function () {
                        loadScript('launcherlibs/nlc.js', function () {
                            let nlc = new rlxlauncher.nlc();
                            nlc.game();
                        });
                    });
                }
            );
        } else if (rlxlauncher.gameid.indexOf('rlx.pgsoft.') == 0) {
            /* PGSoft */
            loadScript('launcherlibs/pgsoft.js', function () {
                let pgsoft = new rlxlauncher.pgsoft();
                pgsoft.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.ags.') == 0) {
            /* ags */
            loadScript('launcherlibs/ags.js', function () {
                let ags = new rlxlauncher.ags();
                ags.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.le.') == 0) {
            /* Leander */
            loadScript('launcherlibs/leander.js', function () {
                let leander = new rlxlauncher.leander();
                leander.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.rgs3.') == 0) {
            /* rgs3 */
            loadScript('launcherlibs/rgs3init.js', function () {
                let rgs3init = new rlxlauncher.rgs3init();
                rgs3init.init();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.rgs3test.') == 0) {
            /* rgs3 test*/
            loadScript('launcherlibs/rgs3inittest.js', function () {
                let rgs3init = new rlxlauncher.rgs3init();
                rgs3init.init();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.redrake.') == 0) {
            /* Red Rake */
            loadScript('launcherlibs/redrake.js', function () {
                let redrake = new rlxlauncher.redrake();
                redrake.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.wazdan.') == 0) {
            /* Wazdan */
            loadScript('launcherlibs/wazdan.js', function () {
                let wazdan = new rlxlauncher.wazdan();
                wazdan.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.playson.') == 0) {
            /* Playson */
            loadScript('launcherlibs/playson.js', function () {
                let playson = new rlxlauncher.playson();
                playson.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.hacksaw.') == 0) {
            /* Hacksaw */
            loadScript('https://static-live.hacksawgaming.com/launcher/hacksaw-launcher.min.js', function () {
                loadScript('launcherlibs/hacksaw.js', function () {
                    let hacksaw = new rlxlauncher.hacksaw();
                    hacksaw.game();
                });
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.slingo.') == 0) {
            /* Slingo */
            loadScript('launcherlibs/slingo.js', function () {
                let slingo = new rlxlauncher.slingo();
                slingo.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.sw.') == 0) {
            /* Skywind */
            loadScript('launcherlibs/skywind.js', function () {
                let skywind = new rlxlauncher.skywind();
                skywind.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.revolver.') == 0) {
            /* Revolver Games */
            loadScript('launcherlibs/revolver.js', function () {
                let revolver = new rlxlauncher.revolver();
                revolver.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.high5.') == 0) {
            /* High 5 Games */
            loadScript('launcherlibs/high5.js', function () {
                let high5 = new rlxlauncher.high5();
                high5.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.sp.') == 0) {
            /* Spearhead */
            loadScript('launcherlibs/spearhead.js', function () {
                let sp = new rlxlauncher.sp();
                sp.game();
            });
        } else if (rlxlauncher.gameid.indexOf('rlx.') == 0) {
            /* Generic P2P */
            loadScript('launcherlibs/vanilla.js', function () {
                let vanilla = new rlxlauncher.vanilla();
                vanilla.game();
            });
        } else if (rlxlauncher.gameid.length > 0) {
            /* Relax platform */
            loadScript('dynamic_asset/quickspin/current-device.min.js', function () {
                loadScript('dynamic_asset/quickspin/relax.js', function () {
                    let relax = new rlxlauncher.relax();
                    relax.game();
                });
            });
        } else {
            console.log('error: empty gameid parameter.');
        }
    } else {
        console.log('error: gameid parameter missing.');
    }
};
/\//