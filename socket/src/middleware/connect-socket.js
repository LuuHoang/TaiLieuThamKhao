const { USER_TYPE } = require('../config/constants');
const DB = require('../config/database');
const bcrypt = require('bcryptjs');
const socketService = require('../services/socket');

let middleware = (socket, next) => {
    let query = socket.handshake.query;

    if (!!query.user_type && parseInt(query.user_type) === USER_TYPE.USER) {
        if (!!query.token) {
            DB.getConnection(function(err, connection) {
                if (err) throw err; // not connected!

                connection.query({
                    sql: 'SELECT `users`.`id` AS `id`, `users`.`full_name` AS `full_name`, `users`.`email` AS `email`, `user_tokens`.`os` AS `os`, `user_tokens`.`device_token` AS `device_token` ' +
                        'FROM `users` LEFT JOIN `user_tokens` ON `users`.`id` = `user_tokens`.`user_id` ' +
                        'WHERE `user_tokens`.`token` = ? AND `users`.`deleted_at` IS NULL AND `user_tokens`.`deleted_at` IS NULL LIMIT 1',
                    timeout: 40000, // 40s
                    values: [query.token]
                }, function (error, results, fields) {
                    connection.release();
                    if (error) throw error;
                    if (results.length === 0) {
                        return next(new Error('authentication error'));
                    }
                    socketService.online(socket.id, results[0], USER_TYPE.USER, query.token);
                    next();
                });
            });
        }
    }

    if (!!query.user_type && parseInt(query.user_type) === USER_TYPE.SHARE_USER) {
        if (!!query.token && !!query.password) {
            DB.getConnection(function(err, connection) {
                if (err) throw err; // not connected!

                connection.query({
                    sql: 'SELECT `id`, `album_id`, `full_name`, `email`, `password` FROM `shared_albums` WHERE `token` = ? AND `deleted_at` IS NULL AND `status` = 1 LIMIT 1',
                    timeout: 40000, // 40s
                    values: [query.token]
                }, function (error, results, fields) {
                    connection.release();
                    if (error) throw error;
                    if (results.length === 0 || !bcrypt.compareSync(query.password, results[0].password)) {
                        return next(new Error('authentication error'));
                    }
                    socketService.online(socket.id, results[0], USER_TYPE.SHARE_USER, query.token);
                    next();
                });
            });
        }
    }

    return next(new Error('authentication error'));
};

module.exports = middleware;
