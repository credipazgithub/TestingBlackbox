if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('worker.js')
        .then(reg => {
            reg.onupdatefound = () => {
                const installingWorker = reg.installing;
                installingWorker.onstatechange = () => {
                    switch (installingWorker.state) {
                        case 'installed':
                            if (navigator.serviceWorker.controller) {
                                setTimeout(() => document.location.reload(true), 1000);
                                caches.keys().then(keys => {
                                    keys.forEach(key => caches.delete(key));
                                })
                            }
                            break;
                    }
                };
            };
        })
}

