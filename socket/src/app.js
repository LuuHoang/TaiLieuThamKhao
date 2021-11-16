const express = require('express');
const path = require('path');
const logger = require('morgan');
const cors = require('cors');
const helmet = require('helmet');

let locationCommentRouter = require('./routes/comment');
let albumPDFRouter = require('./routes/pdf');
let testRouter = require('./routes/test');

let app = express();

app.use(logger('dev'));
app.use(helmet());
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, '../public')));

app.use('/api', locationCommentRouter, albumPDFRouter);
app.use('/test', testRouter);

module.exports = app;
