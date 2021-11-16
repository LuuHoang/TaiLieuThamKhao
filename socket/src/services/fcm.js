const admin = require('../config/firebase');

let pushNotifications = (deviceTokens, notification, data) => {
    if (typeof deviceTokens !== 'undefined' && deviceTokens.length > 0) {
        let message = generateMessageForMultipleDevice(deviceTokens, notification, data);
        admin.messaging().sendMulticast(message)
            .then((response) => {
                if (response.failureCount > 0) {
                    const failedTokens = [];
                    response.responses.forEach((resp, idx) => {
                        if (!resp.success) {
                            failedTokens.push(deviceTokens[idx]);
                            console.log(resp.error);
                        }
                    });
                    console.log('List of tokens that caused failures: ' + failedTokens);
                } else {
                    console.log('Success push notification FCM');
                }
            });
    }
};

let generateMessageForMultipleDevice = (deviceTokens, notification, data) => {
    return {
        tokens: deviceTokens,
        notification: notification,
        data: { data: JSON.stringify(data) }
    }
};

module.exports = { pushNotifications };
