const middleware = require('../middleware/connect-socket');
const socketService = require('../services/socket');
let socket = (server) => {
    const io = require('socket.io')(server);

    // middleware
    io.use((socket, next) => middleware(socket, next));

    io.on('connection', onConnect);

    function onConnect(socket) {
        console.log('Socket connected success!');
        socket.on('disconnect', (reason) => {
            socketService.offline(socket.id, socket.handshake.query.token);
            console.log('Socket disconnected success!');
        });
    }

    return io;
};

module.exports = socket;
