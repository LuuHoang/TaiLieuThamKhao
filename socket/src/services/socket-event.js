const { SOCKET_EVENT } = require('../config/constants');
const { getUserBySocketId } = require('./socket');

let emitLocationCommentEvent = (io, socketIds, data) => {
    for (let socketId of socketIds) {
        let dataPayload = generateDataEmit(socketId, data);
        io.to(socketId).emit(SOCKET_EVENT.LOCATION_COMMENT, dataPayload);
    }
};

let emitMediaCommentEvent = (io, socketIds, data) => {
    for (let socketId of socketIds) {
        let dataPayload = generateDataEmit(socketId, data);
        io.to(socketId).emit(SOCKET_EVENT.MEDIA_COMMENT, dataPayload);
    }
};

let emitAlbumPDFEvent = (io, socketIds, data) => {
    for (let socketId of socketIds) {
        io.to(socketId).emit(SOCKET_EVENT.GENERATE_ALBUM_PDF, data);
    }
};

function generateDataEmit(socketId, data)
{
    let {notifications, ...dataPayload} = data.data;
    if (data.data.hasOwnProperty('notifications')) {
        let userTargetId = getUserBySocketId(socketId);
        if (userTargetId != null && typeof notifications !== 'undefined' && notifications.length > 0) {
            for (let notification of notifications) {
                if (parseInt(userTargetId) in notification) {
                    dataPayload.notification = notification[userTargetId];
                }
            }
        }
    }
    return { data: dataPayload };
}

module.exports = { emitLocationCommentEvent, emitMediaCommentEvent, emitAlbumPDFEvent };
