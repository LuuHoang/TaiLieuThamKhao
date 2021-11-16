const moment = require('moment');
const { USER_TYPE } = require('../config/constants');
let users = {};
let sharedUsers = {};

let online = (socketId, user, userType, token) => {
    let socketIds = [];
    let tokens = [];
    if (parseInt(userType) === USER_TYPE.USER) {
        if (parseInt(user.id) in users) {
            socketIds = users[parseInt(user.id)].socketIds;
            tokens = users[parseInt(user.id)].tokens;
        }
        socketIds.push(socketId);
        tokens.push(token);

        users[parseInt(user.id)] = {
            id: parseInt(user.id),
            socketIds: socketIds,
            tokens: tokens,
            connectedAt: moment().format('YYYY/MM/DD HH:mm:ss')
        };
    }
    if (parseInt(userType) === USER_TYPE.SHARE_USER) {
        if (parseInt(user.id) in sharedUsers) {
            socketIds = sharedUsers[parseInt(user.id)].socketIds;
        }
        socketIds.push(socketId);

        sharedUsers[parseInt(user.id)] = {
            id: parseInt(user.id),
            socketIds: socketIds,
            connectedAt: moment().format('YYYY/MM/DD HH:mm:ss')
        };
    }
    let log = {
        user_id: user.id,
        user_type: userType,
        user_token: token,
        socket_id: socketId
    };
    console.log(log);
};

let offline = (socketId, token) => {

    for (const userId in users) {
        if (users[userId].socketIds.includes(socketId)) {
            let index = users[userId].socketIds.indexOf(socketId);
            let indexToken = users[userId].tokens.indexOf(token);
            users[userId].socketIds.splice(index, 1);
            users[userId].tokens.splice(indexToken, 1);
            if (users[userId].socketIds.length === 0) {
                delete users[userId];
            }
            return 1;
        }
    }
    for (const sharedUserId in sharedUsers) {
        if (sharedUsers[sharedUserId].socketIds.includes(socketId)) {
            let index = sharedUsers[sharedUserId].socketIds.indexOf(socketId);
            sharedUsers[sharedUserId].socketIds.splice(index, 1);
            if (sharedUsers[sharedUserId].socketIds.length === 0) {
                delete sharedUsers[sharedUserId];
            }
            return 1;
        }
    }
    return 0;
};

let getTargetSocketIds = (userTargets, sharedUserTarget) => {
    let socketIds = [];

    if (typeof userTargets !== 'undefined' && userTargets.length > 0) {
        for (let userId of userTargets) {
            if (parseInt(userId) in users) {
                socketIds = socketIds.concat(users[userId].socketIds);
            }
        }
    }

    if (typeof sharedUserTarget !== 'undefined' && sharedUserTarget.length > 0) {
        for (let userId of sharedUserTarget) {
            if (parseInt(userId) in sharedUsers) {
                socketIds = socketIds.concat(sharedUsers[userId].socketIds);
            }
        }
    }
    return socketIds;
};

let getUserOfflineTargets = (userTargets) => {
    let userOfflineTarget = {};
    for (let userId in userTargets) {
        if (!(parseInt(userId) in users)) {
            userOfflineTarget[userId] = userTargets[userId];
        }
    }
    return userOfflineTarget;
};

let getUserBySocketId = (socketId) => {
    for (const userId in users) {
        if (users[userId].socketIds.includes(socketId)) {
            return userId;
        }
    }
    return null;
}

let getUserDeviceTokensPushNotification = (usersTargetNotification) => {
    let deviceTokens = [];
    if (Object.keys(usersTargetNotification).length !== 0 && usersTargetNotification.constructor === Object) {
        for (let userId in usersTargetNotification) {
            if (parseInt(userId) in users) {
                for (let userToken of usersTargetNotification[userId]) {
                    if (!users[userId].tokens.includes(userToken.token) && !deviceTokens.includes(userToken.device_token) && userToken.device_token && userToken.device_token !== "") {
                        deviceTokens.push(userToken.device_token);
                    }
                }
            } else {
                let newDeviceTokens = usersTargetNotification[userId].map(value => value.device_token);
                for (let deviceToken of newDeviceTokens) {
                    if (!deviceTokens.includes(deviceToken) && deviceToken && deviceToken !== "") {
                        deviceTokens.push(deviceToken);
                    }
                }
            }
        }
    }
    return deviceTokens;
};

module.exports = { online, offline, getUserOfflineTargets, getTargetSocketIds, getUserBySocketId, getUserDeviceTokensPushNotification };
