const { getTargetSocketIds, getUserDeviceTokensPushNotification } = require('./socket');
const { emitLocationCommentEvent, emitMediaCommentEvent } = require('./socket-event');
const { pushNotifications } = require('./fcm');

let locationCommentService = (io, data) => {
    let targetSocketIds = getTargetSocketIds(data.users, data.shared_users);
    if (Array.isArray(targetSocketIds) && targetSocketIds.length) {
        emitLocationCommentEvent(io, targetSocketIds,{data: data.data});
    }
    if (typeof data.notification_users != "undefined" && typeof data.notification != "undefined") {
        let deviceTokens = getUserDeviceTokensPushNotification(data.notification_users);
        if (typeof deviceTokens !== 'undefined' && deviceTokens.length > 0) {
            pushNotifications(deviceTokens,data.notification, data.data);
        }
    }
    return {};
};

let mediaCommentService = (io, data) => {
    let targetSocketIds = getTargetSocketIds(data.users, data.shared_users);
    if (Array.isArray(targetSocketIds) && targetSocketIds.length) {
        emitMediaCommentEvent(io, targetSocketIds,{data: data.data});
    }
    if (typeof data.notification_users != "undefined" && typeof data.notification != "undefined") {
        let deviceTokens = getUserDeviceTokensPushNotification(data.notification_users);
        if (typeof deviceTokens !== 'undefined' && deviceTokens.length > 0) {
            pushNotifications(deviceTokens, data.notification, data.data);
        }
    }
    return {};
};

module.exports = { locationCommentService, mediaCommentService };
