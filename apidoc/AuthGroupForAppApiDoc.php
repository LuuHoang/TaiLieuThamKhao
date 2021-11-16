<?php

/**
 * @api {post} /app/user/login User Login
 * @apiVersion 0.1.0
 * @apiName UserLogin
 * @apiGroup Auth For App
 *
 * @apiParam {String} company_code Company Code
 * @apiParam {String} email Email
 * @apiParam {String} password Password
 * @apiParam {Integer} [os] Device os: 1 - IOS, 2 - Android
 * @apiParam {String} [device_token] Device token get notification
 *
 * @apiSuccess {Object} data
 *
 * @apiSuccess {Object} data.user_data
 * @apiSuccess {Integer} data.user_data.id User - ID
 * @apiSuccess {String} data.user_data.full_name User name
 * @apiSuccess {String} data.user_data.email Email
 * @apiSuccess {String} data.user_data.department User department
 * @apiSuccess {String} data.user_data.position User position
 * @apiSuccess {String} data.user_data.avatar_path User image path
 * @apiSuccess {String} data.user_data.avatar_url User image url
 * @apiSuccess {Object} data.user_data.permissions User permissions
 *
 * @apiSuccess {Object} data.user_data.company_data
 * @apiSuccess {Integer} data.user_data.company_data.id Company - ID
 * @apiSuccess {String} data.user_data.company_data.company_name Company name
 * @apiSuccess {String} data.user_data.company_data.company_code Company code
 * @apiSuccess {String} data.user_data.company_data.color Company color
 * @apiSuccess {String} data.user_data.company_data.logo_path Company logo path
 * @apiSuccess {String} data.user_data.company_data.address Company address
 * @apiSuccess {Integer} data.user_data.company_data.service_id Service package id
 * @apiSuccess {String} data.user_data.company_data.service_package Service package name
 * @apiSuccess {Integer} data.user_data.company_data.max_user Maximum number of users
 * @apiSuccess {Integer} data.user_data.company_data.count_user Number of current users
 * @apiSuccess {Float} data.user_data.company_data.max_data Maximum number of data media
 * @apiSuccess {Float} data.user_data.company_data.count_data Number of current data media
 * @apiSuccess {Integer} data.user_data.company_data.count_sub_user Number of sub users
 * @apiSuccess {String} data.user_data.company_data.created_at Created at time
 *
 * @apiSuccess {Object} data.user_data.setting_data
 * @apiSuccess {Integer} data.user_data.setting_data.image_size Maximum image size upload
 * @apiSuccess {String} data.user_data.setting_data.language Application language: EN, VI, JA
 * @apiSuccess {Boolean} data.user_data.setting_data.voice Use voice input
 *
 * @apiSuccess {String} data.user_token User token
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *          "code": 200,
 *          "data": {
 *              "user_data": {
 *                  "id": 1,
 *                  "full_name": "Hoàng Lâm",
 *                  "email": "lam.hoang@supenient.vn",
 *                  "department": "Sale",
 *                  "position": "Nhân Viên",
 *                  "avatar_path": "avatar.jpg",
 *                  "avatar_url": "http://avatar.jpg",
 *                  "permissions": {
 *                      "share_album": false
 *                  },
 *                  "company_data": {
 *                      "id": 1,
 *                      "company_name": "SC Soft",
 *                      "company_code": "SCSOFT",
 *                      "color": "#444444",
 *                      "logo_path": "1-2020-04-28_10-44-11-078558.png",
 *                      "logo_url": "http://localhost/storage/companies/1-2020-04-28_10-44-11-078558.png",
 *                      "address": "186 Nguyen Tuan",
 *                      "service_id": 1,
 *                      "service_package": "Vip 1",
 *                      "extend_id": null,
 *                      "extend_package": "",
 *                      "max_user": 5,
 *                      "count_user": 3,
 *                      "max_data": 50,
 *                      "count_data": 20,
 *                      "created_at": "2020-03-19T17:37:55.000000Z",
 *                      "ts_created_at": 1584639475
 *                  },
 *                  "setting_data": {
 *                      "image_size": null,
 *                      "language": null,
 *                      "voice": null
 *                  }
 *              },
 *              "user_token": "$2y$10$tHQxoFwKyXOtNARArPo1j./v9J/dEWPx.WMhG51cFuDjNqbloonh2"
 *          }
 *      }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *         "code": 401,
 *         "message": "Wrong email or password!"
 *     }
 */
