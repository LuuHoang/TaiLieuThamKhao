const { getTargetSocketIds } = require('./socket');
const { emitAlbumPDFEvent } = require('./socket-event');

let pdfService = (io, data) => {
    let user = [data.user.id];
    let targetSocketIds = getTargetSocketIds(user);
    if (Array.isArray(targetSocketIds) && targetSocketIds.length) {
        emitAlbumPDFEvent(io, targetSocketIds,{data: data.data});
    }
    return {};
};

module.exports = { pdfService };
