const express = require('express');
let router = express.Router();
const path = require('path');

router.get('/connection', function(req, res, next) {
    res.setHeader("Content-Security-Policy", "script-src 'self' * 'unsafe-inline' 'unsafe-eval';");
    res.sendFile(path.resolve(__dirname , '../../test/connection-socket.html'));
});

module.exports = router;
