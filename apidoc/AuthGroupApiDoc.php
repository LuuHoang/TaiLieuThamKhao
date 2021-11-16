<?php

/**
 * @api {post} /user/logout User Logout
 * @apiVersion 0.1.0
 * @apiName UserLogout
 * @apiGroup Auth
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {Post} /user/password/forgot User Forgot Password
 * @apiVersion 0.1.0
 * @apiName UserForgotPassword
 * @apiGroup Auth
 *
 * @apiParam {String} company_code Company Code
 * @apiParam {String} email Email
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {Post} /user/password/reset User Reset Password
 * @apiVersion 0.1.0
 * @apiName UserResetPassword
 * @apiGroup Auth
 *
 * @apiParam {String} otp_code OTP Code attached with email
 * @apiParam {String} password New password
 * @apiParam {String} password_confirmation Password confirmation
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {Post} /user/password/change User Change Password
 * @apiVersion 0.1.0
 * @apiName UserChangePassword
 * @apiGroup Auth
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} old_password Old password
 * @apiParam {String} password New password
 * @apiParam {String} password_confirmation New password confirmation
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */

/**
 * @api {patch} /user/device Update device token
 * @apiVersion 0.1.0
 * @apiName UpdateDeviceToken
 * @apiGroup Auth
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} [os] Device OS: 1 - IOS, 2 - Android
 * @apiParam {String} device_token Device Token
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {}
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */
