const express = require('express');
let router = express.Router();
const { locationCommentService, mediaCommentService } = require('../services/comment');

router.post('/locations/comment', function(req, res, next) {
    let response = locationCommentService(req.app.get('io'), req.body);
    res.status(200).json({ code: 200, data: response});
});

router.post('/locations/medias/comment', function(req, res, next) {
    let response = mediaCommentService(req.app.get('io'), req.body);
    res.status(200).json({ code: 200, data: response});
});

module.exports = router;
