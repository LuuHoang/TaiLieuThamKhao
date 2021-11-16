<?php

/**
 * @api {get} /web/album-pdfs/formats Get list album pdf formats
 * @apiVersion 0.1.0
 * @apiName GetListAlbumPDFFormats
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 1,
 *                       "title": "Album pdf format title",
 *                       "description": "Album pdf format description",
 *                       "album_type_id": 20;
 *                       "user_creator": {
 *                           "id": 1,
 *                           "full_name": "Hoang Lam",
 *                           "email": "hoanglam995@gmail.com",
 *                           "avatar_url": "http://localhost/storage/users/1-2020-09-17_07-23-44-415491.jpeg"
 *                       }
 *                   }
 *               ],
 *               "total": 1,
 *               "current_page": 1,
 *               "limit": 10
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

/**
 * @api {post} /web/album-pdfs/formats Create album pdf format
 * @apiVersion 0.1.0
 * @apiName CreateAlbumPDFFormat
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title album pdf format
 * @apiParam {String} description Description album pdf format
 * @apiParam {Number} [album_type_id] Id album type
 * @apiParam {String} [cover_page] cover page album pdf format
 * @apiParam {String} [content_page] Content page album pdf format
 * @apiParam {String} [last_page Last] page album pdf format
 * @apiParam {Number} [number_images] number images on page content
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_pdf_format": {
 *                   "id": 2,
 *                   "title": "Album pdf format title",
 *                   "album_type_id": 20,
 *                   "description": "Album pdf format description",
 *                   "cover_page": "<html><head>...</head><body>...</body></html>",
 *                   "content_page": "<html><head>...</head><body>...</body></html>",
 *                   "last_page": "<html><head>...</head><body>...</body></html>",
 *                   "number_images": 2
 *               }
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

/**
 * @api {get} /web/album-pdfs/formats/:formatId Get album pdf format
 * @apiVersion 0.1.0
 * @apiName GetAlbumPDFFormat
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_pdf_format": {
 *                   "id": 2,
 *                   "title": "Album pdf format title",
 *                   "description": "Album pdf format description",
 *                   "album_type_id": 20,
 *                   "cover_page": "<html><head>...</head><body>...</body></html>",
 *                   "content_page": "<html><head>...</head><body>...</body></html>",
 *                   "last_page": "<html><head>...</head><body>...</body></html>",
 *                   "number_images": 2
 *               }
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

/**
 * @api {put} /web/album-pdfs/formats/:formatId Update album pdf format
 * @apiVersion 0.1.0
 * @apiName UpdateAlbumPDFFormat
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title album pdf format
 * @apiParam {String} description Description album pdf format
 * @apiParam {Number} [album_type_id] Id album type
 * @apiParam {String} [cover_page] cover page album pdf format
 * @apiParam {String} [content_page] Content page album pdf format
 * @apiParam {String} [last_page] Content page album pdf format
 * @apiParam {Number} [number_images] Number images on page content. require if modify content_page
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_pdf_format": {
 *                   "id": 2,
 *                   "title": "Album pdf format title",
 *                   "description": "Album pdf format description",
 *                   "album_type_id": 20,
 *                   "cover_page": "<html><head>...</head><body>...</body></html>",
 *                   "content_page": "<html><head>...</head><body>...</body></html>",
 *                   "last_page": "<html><head>...</head><body>...</body></html>",
 *                   "number_images": 2
 *               }
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

/**
 * @api {delete} /web/album-pdfs/formats/:formatId Delete album pdf format
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumPDFFormat
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {}
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

/**
 * @api {get} /web/album-pdfs/config Get config album pdf format
 * @apiVersion 0.1.0
 * @apiName GetConfigAlbumPDFFormat
 * @apiGroup Album PDF Format For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "company": {
 *                   "company.company_name": "SCSoft VN",
 *                   "company.company_code": "SCSOFT",
 *                   "company.address": "HN",
 *                   "company.representative": "Dat Truong",
 *                   "company.tax_code": "TX1111",
 *                   "company.logo_url": "http://localhost/storage/companies/1600327399.png"
 *               },
 *               "album": {
 *                   "album.album_type": "Sample album type",
 *                   "album.user_creator": "Sample user creator",
 *                   "album.image_url": "https://picsum.photos/1600/900",
 *                   "information": [
 *                       {
 *                           "album.information.9.title": "House ID",
 *                           "album.information.9.value": "Sample text value album information"
 *                       }
 *                   ]
 *               },
 *               "location": {
 *                   "location.title": "Sample location title",
 *                   "location.description": "Sample location description",
 *                   "information": [
 *                       {
 *                           "location.information.3.title": "Diện Tích",
 *                           "location.information.3.value": "Sample text value location information"
 *                       }
 *                   ]
 *               },
 *               "medias": {
 *                   "medias.*.url": "https://picsum.photos/1600/900",
 *                   "medias.*.name": "image.jpg",
 *                   "medias.*.created_time": "--/--/----",
 *                   "medias.*.description": "Sample media description",
 *                   "information": [
 *                       {
 *                           "medias.*.information.2.title": "Ngày chụp",
 *                           "medias.*.information.2.value": "Sample text value location information"
 *                       }
 *                   ]
 *               },
 *               "medias_after": {
 *                   "medias_after.*.url": "https://picsum.photos/1600/900",
 *                   "medias_after.*.name": "image.jpg",
 *                   "medias_after.*.created_time": "--/--/----",
 *                   "medias_after.*.description": "Sample media description",
 *                   "information": [
 *                       {
 *                           "medias_after.*.information.2.title": "Ngày chụp",
 *                           "medias_after.*.information.2.value": "Sample text value location information"
 *                       }
 *                   ]
 *               }
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
