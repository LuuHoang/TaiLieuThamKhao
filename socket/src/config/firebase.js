const admin = require("firebase-admin");

const serviceAccount = require("../../" + process.env.FCM_SERVICE_ACCOUNT_PATH);

admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
    databaseURL: process.env.FCM_DATABASE_URL
});

module.exports = admin;
