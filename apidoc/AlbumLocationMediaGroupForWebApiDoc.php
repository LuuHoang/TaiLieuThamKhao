<?php

/**
 * @api {delete} /web/albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId Delete Album Location Media
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumLocationMedia
 * @apiGroup Album Location Media For Web
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
 * @api {put} /web/albums/:albumId/locations/:albumLocationId/medias/:albumLocationMediaId/change Change location of album location media
 * @apiVersion 0.1.0
 * @apiName ChangeLocationOfAlbumLocationMedia
 * @apiGroup Album Location Media For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} location_title Location title
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
