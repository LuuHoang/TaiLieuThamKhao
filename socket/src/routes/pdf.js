const express = require('express');
let router = express.Router();
const { pdfService } = require('../services/pdf');

router.post('/albums/pdf', function(req, res, next) {
    let response = pdfService(req.app.get('io'), req.body);
    res.status(200).json({ code: 200, data: response});
});

module.exports = router;
