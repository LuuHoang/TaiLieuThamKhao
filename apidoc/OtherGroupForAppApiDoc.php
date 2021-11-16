<?php

/**
 * @api {get} /app/departments Get List departments
 * @apiVersion 0.1.0
 * @apiName GetListDepartment
 * @apiGroup Other For App
 *
 * @apiSuccess {Object[]} departments List user
 * @apiSuccess {Number} departments.id
 * @apiSuccess {String} departments.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "departments": [
 *                   {
 *                       "id": 1,
 *                       "title": "Kế Toán"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /app/positions Get List positions
 * @apiVersion 0.1.0
 * @apiName GetListPositions
 * @apiGroup Other For App
 *
 * @apiSuccess {Object[]} positions List user
 * @apiSuccess {Number} positions.id
 * @apiSuccess {String} positions.title
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "positions": [
 *                   {
 *                       "id": 1,
 *                       "title": "Trưởng Phòng"
 *                   },
 *                   {
 *                       "id": 2,
 *                       "title": "Nhân Viên"
 *                   }
 *               ]
 *           }
 *       }
 *
 */

/**
 * @api {get} /app/versions Get list version
 * @apiVersion 0.1.0
 * @apiName GetListVersion
 * @apiGroup Other For App
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "versions": [
 *                   {
 *                       "id": 45,
 *                       "name": "1.0",
 *                       "en_description": "",
 *                       "ja_description": "",
 *                       "vi_description": "",
 *                       "active": true,
 *                       "version_ios": "9.8",
 *                       "version_android": "10.0",
 *                   }
 *              ],
 *              "links": {
 *                    "ios": "https://baomoi.com/",
 *                    "android": "https://dantri.com.vn/"
 *               }
 *           }
 *       }
 *
 */

/**
 * @api {get} /app/version/links get link version app
 * @apiVersion 0.1.0
 * @apiName GetLinkVersion
 * @apiGroup Other For App
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "links": {
 *                   "ios": "http://appstore.vn/maker",
 *                   "android": "http://playstore.vn/maker",
 *               }
 *           }
 *       }
 *
 */

/**
 * @api {get} /app/versions/active Get version active
 * @apiVersion 0.1.0
 * @apiName GetVersionActive
 * @apiGroup Other For App
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "version": {
 *                   "id": 45,
 *                   "name": "1.0",
 *                   "en_description": "",
 *                   "ja_description": "",
 *                   "vi_description": "",
 *                   "active": true,
 *                   "version_ios": "9.8",
 *                   "version_android": "10.0",
 *               },
 *              "links": {
 *                   "ios": "http://appstore.vn/maker",
 *                   "android": "http://playstore.vn/maker",
 *               }
 *           }
 *       }
 *
 */
