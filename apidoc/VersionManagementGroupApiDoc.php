<?php

/**
 * @api {get} /admin/versions  Get list version app
 * @apiVersion 0.1.0
 * @apiName GetListVersions
 * @apiGroup Version Management
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} [page] Current page
 * @apiParam {Number} [limit] item in page
 * @apiParam {String} [search] keyword search
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 45,
 *                       "name": "1.0",
 *                       "en_description": "",
 *                       "ja_description": "",
 *                       "vi_description": "",
 *                       "active": true,
 *                       "version_ios" : "9.8",
 *                       "version_android" : "10",
 *                   }
 *               ],
 *               "total": 1,
 *               "current_page": 1,
 *               "limit": 10
 *           }
 *       }
 *
 */

/**
 * @api {get} /admin/versions/:versionId Get details version app
 * @apiVersion 0.1.0
 * @apiName GetDetailVersions
 * @apiGroup Version Management
 *
 * @apiUse HeaderToken
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
 *                   "version_ios" : "9.8",
 *                   "version_android" : "10",
 *               }
 *           }
 *       }
 *
 */

/**
 * @api {post} /admin/versions  create version app
 * @apiVersion 0.1.0
 * @apiName CreateVersions
 * @apiGroup Version Management
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} name Version name
 * @apiParam {String} en_description Version description EN
 * @apiParam {String} ja_description Version description JA
 * @apiParam {String} vi_description Version description VI
 * @apiParam {Boolean} active Current version active
 * @apiParam {String} version_ios version_ios
 * @apiParam {String} version_android version_android

 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {}
 *       }
 *
 */

/**
 * @api {put} /admin/versions/:versionId  update version app
 * @apiVersion 0.1.0
 * @apiName UpdateVersions
 * @apiGroup Version Management
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} name Version name
 * @apiParam {String} en_description Version description EN
 * @apiParam {String} ja_description Version description JA
 * @apiParam {String} vi_description Version description VI
 * @apiParam {Boolean} active Current version active
 * @apiParam {String} version_ios version_ios
 * @apiParam {String} version_android version_android

 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {}
 *       }
 *
 */

/**
 * @api {post} /admin/version/links create or update link version app
 * @apiVersion 0.1.0
 * @apiName CreateOrUpdateLinkVersion
 * @apiGroup Version Management
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} link_ios Link ios
 * @apiParam {String} link_android Link android
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {}
 *       }
 *
 */

/**
 * @api {get} /admin/version/links get link version app
 * @apiVersion 0.1.0
 * @apiName GetLinkVersion
 * @apiGroup Version Management
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
