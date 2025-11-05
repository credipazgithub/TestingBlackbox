if ('serviceWorker' in navigator) {
    caches.keys().then(function (cacheNames) {
        cacheNames.forEach(function (cacheName) { caches.delete(cacheName); });
    });

    window.addEventListener('beforeinstallprompt', (e) => {
        _HTTPREQUEST._mobile = (screen.width < 700 || screen.height < 500);
        var _json = _DB.Get("pwa_install");
        if (_json != null && !_json.ask) { return false; }
        if (!_NMF._bInstalling && _HTTPREQUEST._mobile) {
            _NMF._bInstalling = true;
            e.preventDefault();
            _NMF.deferredPrompt = e;
            var _html = "<div class='container text-center p-3 m-0 colorBack' style='border-radius:25px;'><img src='img/Mediya/logo.png' class='img-logo' alt='Logo' style='width:150px;'/></div>";
            _html += "<hr/>";
            _html += ("<div class='area-install'><p>Utilizamos tecnología PWA para brindar una experiencia de uso fluída y confiable.</p><p>Esta aplicación puede instalarse sin inconvenientes en su dispositivo.</p></div>");
            _html += "<hr class='hide-install'/>";
            _html += "<table class='hide-install' style='width:100%;'>";
            _html += "   <tr>";
            _html += "      <td align='left'><button id='noinstall' class='btn bt-md btn-light btn-raised noinstall'>Continuar como web</button></td>";
            _html += "      <td align='right'><button id='install' class='btn bt-md btn-primary btn-raised install'>Instalar</button></td>";
            _html += "   </tr>";
            _html += "</table>";
            _NMF.onModalFullScreen("Instalación", _html);
        }
    });
    window.addEventListener('appinstalled', (e) => {
        _NMF.onModalAlert("Verificación de sistema", "Se ha instalado la aplicación en su dispositivo");
    });

    navigator.serviceWorker.register('website-movil-worker.js')
        .then(reg => {
            reg.onupdatefound = () => {
                const installingWorker = reg.installing;
                installingWorker.onstatechange = () => {
                    switch (installingWorker.state) {
                        case 'installed':
                            if (navigator.serviceWorker.controller) {
                                setTimeout(() => document.location.reload(true), 100);
                                caches.keys().then(keys => { keys.forEach(key => caches.delete(key)); });
                            }
                            break;
                    }
                };
            };
        })
}
async function installApp() {
    $(".hide-install").fadeOut("slow");
    if (_NMF.deferredPrompt) {
        _NMF.deferredPrompt.prompt();
        const { outcome } = await _NMF.deferredPrompt.userChoice;
        _NMF.deferredPrompt = null;
        if (outcome !== 'accepted') {
            setTimeout(function () {
                var _ret = { "color": "danger", "html": _LNG._msg_pwa_cancel_install };
                _NMF.onModalAlert("Verificación de sistema", _ret.html, _ret.color, true);
            }, 100);
        }
    }
}