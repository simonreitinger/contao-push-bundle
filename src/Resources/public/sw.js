self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const sendNotification = message => {
        const { title, body } = message;

        return self.registration.showNotification(title, { body });
    };

    if (event.data) {
        const message = event.data.json();
        event.waitUntil(sendNotification(message));
    }
});
