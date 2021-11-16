<?php

/**
 * @api {get} /web/albums/:albumId/export Export album PDF
 * @apiVersion 0.1.0
 * @apiName ExportAlbumPDF
 * @apiGroup Export Album PDF For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} style Style export number. EX param: ?style=1
 *
 * @apiSuccess {String} [url] Url download album pdf
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "url": "http://localhost/AlbumMakerV2/public/storage/downloads/251_1589440633.pdf"
 *          }
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
 * @api {get} /web/albums/:albumId/pdfs/export Export album PDF by format
 * @apiVersion 0.1.0
 * @apiName ExportAlbumPDFByFormat
 * @apiGroup Export Album PDF For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} format_id Id pdf format. EX param: ?format_id=1
 *
 * @apiSuccess {String} [url] Url download album pdf
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "url": "http://localhost/AlbumMakerV2/public/storage/downloads/251_1589440633.pdf"
 *          }
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
 * @api {get} /web/albums/:albumId/pdfs/preview Preview album PDF by format
 * @apiVersion 0.1.0
 * @apiName PreviewAlbumPDFByFormat
 * @apiGroup Export Album PDF For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} format_id Id pdf format. EX param: ?format_id=1
 *
 * @apiSuccess {String} cover_page Html cover page
 * @apiSuccess {String[]} content_pages Array content pages
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "cover_page": "html><html><head>...</head><body>...</body></html>",
 *               "content_pages": [
 *                   "html><html><head>...</head><body>...</body></html>",
 *                   "html><html><head>...</head><body>...</body></html>"
 *               ],
 *               "last_page": "html><html><head>...</head><body>...</body></html>",
 *           }
 *       }
 *
 * @apiError message System has error
 *
 * @apiErrorExample {json} SystemError-Response:
 *     {
 *       "code": 400,
 *       "message": "System Error!"
 *     }
 */
