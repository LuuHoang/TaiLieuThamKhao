<?php

/**
 * @api {post} /albums/:albumId/modify Check modify album
 * @apiVersion 0.1.0
 * @apiName CheckModifyAlbum
 * @apiGroup Other
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} updated_at Updated at field Album
 *
 * @apiSuccess modify Modify status: false - not modify, true - modify
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "modify" : true
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System error!"
 *     }
 */

/**
 * @api {post} /albums/:albumId/locations/:locationId/modify Check modify album location
 * @apiVersion 0.1.0
 * @apiName CheckModifyAlbumLocation
 * @apiGroup Other
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} updated_at Updated at field Album location
 *
 * @apiSuccess modify Modify status: false - not modify, true - modify
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "modify" : true
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System error!"
 *     }
 */

/**
 * @api {post} /albums/:albumId/locations/:locationId/medias/:mediaId/modify Check modify album location media
 * @apiVersion 0.1.0
 * @apiName CheckModifyAlbumLocationMedia
 * @apiGroup Other
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} updated_at Updated at field Album location media
 *
 * @apiSuccess modify Modify status: false - not modify, true - modify
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "modify" : true
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System error!"
 *     }
 */

/**
 * @api {get} /albums/:albumId/locations get locations title
 * @apiVersion 0.1.0
 * @apiName GetLocationsTitle
 * @apiGroup Other
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "locations" : [
 *                  {
 *                      "title": "bedroom"
 *                  }
 *              ]
 *         }
 *     }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System error!"
 *     }
 */
