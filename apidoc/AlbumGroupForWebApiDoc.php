<?php
/**
 * @api {post} /web/albums Create album
 * @apiVersion 0.1.0
 * @apiName CreateAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Object[]} information
 * @apiParam {Number} information.album_property_id ID album property
 * @apiParam {String} [information.value] Value album property
 * @apiParam {Number} album_type_id ID album type
 * @apiParam {String} image_path Album image path
 * @apiParam {Object[]} locations
 * @apiParam {String} locations.title Title album location
 * @apiParam {String} locations.description Description album location
 * @apiParam {Object[]} locations.information
 * @apiParam {Number} locations.information.location_property_id Location property id
 * @apiParam {String} locations,information.value Location property value
 * @apiParam {Boolean} [show_comment] show_comment
 *
 * @apiSuccess album album infomation created
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *               "information" : [
 *                   {
 *                   "album_property_id" : 1,
 *                   "value" : "House ID",
 *                   "value_list": [{"id": 1}]
 *                   }
 *               ],
 *               "album_type_id" : 1,
 *               "image_path" : "image_path.jpg",
 *               "locations" : [
 *                   {
 *                       "title" : "Bedroom",
 *                       "description" : "Bedroom description",
 *                       "information": [
 *                            {
 *                                "location_property_id": 1,
 *                                "value": "45m2"
 *                            }
 *                       ]
 *                   }
 *               ],
 *              "show_comment" : 1
 *       }
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album": {
 *                   "id": 42,
 *                   "user_id": 1,
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg",
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）",
 *                           "checked": 1
 *                       }
 *                   ],
 *                   "album_locations": [
 *                       {
 *                           "id": 39,
 *                           "title": "Bedroom",
 *                           "description": "Bedroom description",
 *                           "information": [
 *                               {
 *                                   "id": 18,
 *                                   "location_property_id": 1,
 *                                   "title": "Diện Tích",
 *                                   "type": 1,
 *                                   "display": 0,
 *                                   "highlight": 1,
 *                                   "value": "45m2"
 *                               }
 *                           ],
 *                           "location_image": "",
 *                           "comments": [],
 *                           "number_comment": 0,
 *                           "created_at": "2020-09-09T18:07:41.000000Z",
 *                           "ts_created_at": 1599674861,
 *                           "medias": []
 *                       }
 *                   ],
 *                   "album_informations": [
 *                       {
 *                           "id": 43,
 *                           "album_property_id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "House ID"
 *                       },
 *                       {
 *                           "id": "",
 *                           "album_property_id": 2,
 *                           "title": "所有者（Owner）",
 *                           "type": 1,
 *                           "require": 0,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": ""
 *                       }
 *                   ],
 *                  "show_comment" : true
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
 * @api {put} /web/albums/:albumId Update album
 * @apiVersion 0.1.0
 * @apiName UpdateAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 * @apiHeader {Number} Force-Update Force update: 1 - not check modify, 0 - check modify
 *
 * @apiParam {Object[]} information
 * @apiParam {Number} information.album_property_id ID album property
 * @apiParam {String} [information.value] Value album property
 * @apiParam {Number} [album_type_id] ID album type
 * @apiParam {String} [image_path] Album image path
 * @apiParam {String} latest_updated_at Latest updated at Album
 * @apiParam {Object[]} [locations]
 * @apiParam {Number} [locations.id] Location id
 * @apiParam {String} [locations.title] Album location title for insert new location. Not allow to update location
 * @apiParam {String} [locations.description] Description album location
 * @apiParam {Object[]} [locations.information]
 * @apiParam {Number} locations.information.location_property_id Location property id
 * @apiParam {String} [locations,information.value] Location property value
 * @apiParam {Boolean} [show_comment] show_comment
 *
 * @apiSuccess album album information
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *               "information" : [
 *                   {
 *                      "album_property_id" : 1,
 *                      "value" : "HHA01",
 *                      "value_list": [{"id": 1}]
 *                   }
 *               ],
 *               "album_type_id" : 1,
 *               "image_path" : "image_path.jpg",
 *               "locations": [
 *                   {
 *                       "id": 1,
 *                       "title": "location title",
 *                       "description": "location description",
 *                       "information": [
 *                          {
 *                               "location_property_id": 1,
 *                               "value": "30m2"
 *                           }
 *                       ]
 *                   }
 *               ],
 *              "show_comment" : 1
 *       }
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album": {
 *                   "id": 42,
 *                   "user_id": 1,
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg",
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）",
 *                           "checked": 1
 *                       },
 *                       {
 *                           "id": 2,
 *                           "title": "マンション（Appartment）",
 *                           "checked": 0
 *                       }
 *                   ],
 *                   "album_locations": [
 *                       {
 *                           "id": 39,
 *                           "title": "Bedroom",
 *                           "description": "location description",
 *                           "information": [
 *                               {
 *                                   "id": 18,
 *                                   "location_property_id": 1,
 *                                   "title": "Diện Tích",
 *                                   "type": 1,
 *                                   "display": 0,
 *                                   "highlight": 1,
 *                                   "value": "30m2"
 *                               }
 *                           ],
 *                           "location_image": "",
 *                           "comments": [
 *                               {
 *                                   "id": 17,
 *                                   "creator": {
 *                                       "shared_album_id": 3,
 *                                       "full_name": "Hoang Tung Lam",
 *                                       "email": "hoanglam995@gmail.com"
 *                                   },
 *                                   "creator_type": 2,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-14T11:34:35.000000Z",
 *                                   "update_at": "2020-08-14T11:34:35.000000Z"
 *                               },
 *                               {
 *                                   "id": 21,
 *                                   "creator": {
 *                                       "id": 1,
 *                                       "full_name": "Hoàng Tùng Lâm",
 *                                       "email": "hoanglam995@gmail.com",
 *                                       "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                   },
 *                                   "creator_type": 1,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-17T04:35:11.000000Z",
 *                                   "update_at": "2020-08-17T04:35:11.000000Z"
 *                               }
 *                           ],
 *                           "number_comment": 2,
 *                           "created_at": "2020-09-09T18:07:41.000000Z",
 *                           "ts_created_at": 1599674861,
 *                           "medias": [
 *                               {
 *                                   "id": 1,
 *                                   "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "image_after_path": null,
 *                                   "image_after_url": null,
 *                                   "thumbnail_path": null,
 *                                   "thumbnail_url": null,
 *                                   "album_location_id": 30,
 *                                   "description": "description",
 *                                   "created_time": "09/09/2020",
 *                                   "information": [
 *                                       {
 *                                          "id": 19,
 *                                          "media_property_id": 1,
 *                                          "title": "Ngày Chụp Ảnh",
 *                                          "type": 1,
 *                                          "display": 1,
 *                                          "highlight": 1,
 *                                          "value": "09/09/2020"
 *                                       }
 *                                   ],
 *                                   "comments": [
 *                                       {
 *                                           "id": 17,
 *                                           "creator": {
 *                                               "shared_album_id": 3,
 *                                               "full_name": "Hoang Tung Lam",
 *                                               "email": "hoanglam995@gmail.com"
 *                                           },
 *                                           "creator_type": 2,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-14T11:34:35.000000Z",
 *                                           "update_at": "2020-08-14T11:34:35.000000Z"
 *                                       },
 *                                       {
 *                                           "id": 21,
 *                                           "creator": {
 *                                               "id": 1,
 *                                               "full_name": "Hoàng Tùng Lâm",
 *                                               "email": "hoanglam995@gmail.com",
 *                                               "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                           },
 *                                           "creator_type": 1,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-17T04:35:11.000000Z",
 *                                           "update_at": "2020-08-17T04:35:11.000000Z"
 *                                       }
 *                                   ],
 *                                   "number_comment": 2,
 *                                   "type": 1,
 *                                   "size": 0.1
 *                               }
 *                           ]
 *                       }
 *                   ],
 *                   "album_informations": [
 *                       {
 *                           "id": 43,
 *                           "album_property_id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "HHA01",
 *                           "value_list": [
 *                              {
 *                                  "id": 1,
 *                                  "name": "name",
 *                                  "file_path": "http://localhost"
 *                              }
 *                           ]
 *                       },
 *                       {
 *                           "id": "",
 *                           "album_property_id": 2,
 *                           "title": "所有者（Owner）",
 *                           "type": 1,
 *                           "require": 0,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "",
 *                           "value_list": [
 *                              {
 *                                  "id": 1,
 *                                  "name": "name",
 *                                  "file_path": "http://localhost"
 *                              }
 *                           ]
 *                       }
 *                   ],
 *                  "show_comment" : true
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
 * @api {get} /web/albums Get List Album
 * @apiVersion 0.1.0
 * @apiName GetListAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search keyword search
 * @apiParam {String} sort[date] sort by created date: ASC - Old, DESC - New
 * @apiParam {String} sort[id] sort by id
 * @apiParam {String} sort[user] sort by created user
 * @apiParam {String} sort[highlight] sort by highlight
 * @apiParam {String} filter[album_type_ids] filter by album type. Ex filter[album_type_ids]=1,2,3
 * @apiParam {String} filter[user_ids] filter by create user. Ex: filter[user_ids]=1,2,3
 *
 * @apiSuccess data_list list album
 * @apiSuccess data_list.id album id
 * @apiSuccess data_list.image_path Album avatar image path
 * @apiSuccess data_list.image_url Album avatar image url
 * @apiSuccess data_list.album_type Album type
 * @apiSuccess data_list.album_type.id Album type id
 * @apiSuccess data_list.album_type.title Album type title
 * @apiSuccess data_list.album_informations Album Information
 * @apiSuccess data_list.album_informations.id Album Information id
 * @apiSuccess data_list.album_informations.album_property_id Album property id
 * @apiSuccess data_list.album_informations.title Album Information title
 * @apiSuccess data_list.album_informations.description Album Information description
 * @apiSuccess data_list.album_informations.require Album property require
 * @apiSuccess data_list.album_informations.display Album property display
 * @apiSuccess data_list.album_informations.highlight Album property highlight
 * @apiSuccess data_list.album_informations.value Album Information value
 * @apiSuccess data_list.user_created_data User created album
 * @apiSuccess data_list.user_created_data.id User id
 * @apiSuccess data_list.user_created_data.full_name full name
 * @apiSuccess data_list.user_created_data.email User Email
 * @apiSuccess data_list.user_created_data.avatar_url Avatar url
 * @apiSuccess data_list.album_size total size album
 * @apiSuccess data_list.created_at Album Created at time
 * @apiSuccess total
 * @apiSuccess current_page
 * @apiSuccess limit
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "data_list": [
 *                   {
 *                       "id": 236,
 *                       "user_id": 1,
 *                       "image_path": "image_path.jpg",
 *                       "image_url": "http://localhost/storage/images/image_path.jpg",
 *                       "album_type": {
 *                           "id": 1,
 *                           "title": "House"
 *                       },
 *                       "album_informations": [
 *                           {
 *                               "id": 457,
 *                               "album_property_id": 1,
 *                               "title": "House ID",
 *                               "type": 1,
 *                               "require": 1,
 *                               "display": 1,
 *                               "highlight": 1,
 *                               "value": "1"
 *                           }
 *                       ],
 *                       "user_created_data" : {
 *                               "id": 1,
 *                               "full_name": "Hoang Lam",
 *                               "email": "hoanglam995@gmail.com",
 *                               "avatar_url": "http://localhost/storage/users/1-2020-09-15_09-14-10-098666.jpeg"
 *                       },
 *                       "album_size": 0,
 *                       "created_at": "2020-04-21T18:00:49.000000Z",
 *                       "show_comment" : true
 *                   }
 *               ],
 *               "total": 33,
 *               "current_page": 1,
 *               "limit": 1
 *           }
 *       }
 */

/**
 * @api {get} /web/albums/:albumId Get Album Detail
 * @apiVersion 0.1.0
 * @apiName GetAlbumDetail
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album": {
 *                   "id": 41,
 *                   "user_id": 1,
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg",
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）",
 *                           "checked": 1
 *                       },
 *                       {
 *                           "id": 2,
 *                           "title": "マンション（Appartment）",
 *                           "checked": 0
 *                       }
 *                   ],
 *                   "album_locations": [
 *                       {
 *                           "id": 30,
 *                           "title": "Bedroom",
 *                           "description": "Gara oto description",
 *                           "information": [
 *                               {
 *                                  "id": 12,
 *                                  "location_property_id": 1,
 *                                  "title": "Diện Tích",
 *                                  "type": 1,
 *                                  "display": 0,
 *                                  "highlight": 1,
 *                                  "value": "45m2"
 *                               }
 *                           ],
 *                           "location_image": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "comments": [
 *                               {
 *                                   "id": 17,
 *                                   "creator": {
 *                                       "shared_album_id": 3,
 *                                       "full_name": "Hoang Tung Lam",
 *                                       "email": "hoanglam995@gmail.com"
 *                                   },
 *                                   "creator_type": 2,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-14T11:34:35.000000Z",
 *                                   "update_at": "2020-08-14T11:34:35.000000Z"
 *                               },
 *                               {
 *                                   "id": 21,
 *                                   "creator": {
 *                                       "id": 1,
 *                                       "full_name": "Hoàng Tùng Lâm",
 *                                       "email": "hoanglam995@gmail.com",
 *                                       "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                   },
 *                                   "creator_type": 1,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-17T04:35:11.000000Z",
 *                                   "update_at": "2020-08-17T04:35:11.000000Z"
 *                               }
 *                           ],
 *                           "number_comment": 2,
 *                           "created_at": "2020-09-09T06:26:57.000000Z",
 *                           "ts_created_at": 1599632817,
 *                           "medias": [
 *                               {
 *                                   "id": 1,
 *                                   "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "image_after_path": null,
 *                                   "image_after_url": null,
 *                                   "thumbnail_path": null,
 *                                   "thumbnail_url": null,
 *                                   "album_location_id": 30,
 *                                   "description": "description",
 *                                   "created_time": "09/09/2020",
 *                                   "information": [
 *                                       {
 *                                          "id": 19,
 *                                          "media_property_id": 1,
 *                                          "title": "Ngày Chụp Ảnh",
 *                                          "type": 1,
 *                                          "display": 1,
 *                                          "highlight": 1,
 *                                          "value": "09/09/2020"
 *                                       }
 *                                   ],
 *                                   "comments": [
 *                                       {
 *                                           "id": 17,
 *                                           "creator": {
 *                                               "shared_album_id": 3,
 *                                               "full_name": "Hoang Tung Lam",
 *                                               "email": "hoanglam995@gmail.com"
 *                                           },
 *                                           "creator_type": 2,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-14T11:34:35.000000Z",
 *                                           "update_at": "2020-08-14T11:34:35.000000Z"
 *                                       },
 *                                       {
 *                                           "id": 21,
 *                                           "creator": {
 *                                               "id": 1,
 *                                               "full_name": "Hoàng Tùng Lâm",
 *                                               "email": "hoanglam995@gmail.com",
 *                                               "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                           },
 *                                           "creator_type": 1,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-17T04:35:11.000000Z",
 *                                           "update_at": "2020-08-17T04:35:11.000000Z"
 *                                       }
 *                                   ],
 *                                   "number_comment": 2,
 *                                   "type": 1,
 *                                   "size": 0.1
 *                               }
 *                           ]
 *                       }
 *                   ],
 *                   "album_informations": [
 *                       {
 *                           "id": 42,
 *                           "album_property_id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "House ID",
 *                           "value_list": [
 *                              {
 *                                  "id": 1,
 *                                  "name": "name",
 *                                  "file_path": "http://localhost"
 *                              }
 *                           ]
 *                       },
 *                       {
 *                           "id": "",
 *                           "album_property_id": 2,
 *                           "title": "所有者（Owner）",
 *                           "type": 1,
 *                           "require": 0,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "",
 *                           "value_list": [
 *                              {
 *                                  "id": 1,
 *                                  "name": "name",
 *                                  "file_path": "http://localhost"
 *                              }
 *                           ]
 *                       }
 *                   ],
 *                  "show_comment" : true
 *               }
 *           }
 *       }
 */

/**
 * @api {post} /web/albums/:albumId/avatar update avatar album
 * @apiVersion 0.1.0
 * @apiName UpdateAlbumAvatar
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {File} file Image avatar album. Accept jpg, png
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "image_data": {
 *                  "path": "1-2020-04-23_10-20-57-375618.png",
 *                  "url": "http://localhost/storage/images/1-2020-04-23_10-20-57-375618.png"
 *               }
 *          }
 *     }
 */

/**
 * @api {post} /web/albums/:albumId/share Share album for Email
 * @apiVersion 0.1.0
 * @apiName ShareAlbumForEmail
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} email Share to email
 * @apiParam {String} full_name Name
 * @apiParam {String} message Message
 * @apiParam {Number} template_id Template id
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *          "email" : "lam.hoang@supenient.vn",
 *          "full_name" : "Hoàng Lâm",
 *          "message" : "Album nha moi",
 *          "template_id" : 1
 *       }
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
 * @api {post} /web/albums/shared Get Album Shared For Email
 * @apiVersion 0.1.0
 * @apiName GetAlbumSharedForEmail
 * @apiGroup Albums For Web
 *
 * @apiParam {String} token Token get shared album
 * @apiParam {String} password Password get shared album
 *
 * @apiParamExample {json} Request-Example:
 *       {
 *          "token" : "JDJ5JDEwJGRxYUdmbjhEWFkuZFp2LnJPMnRiTWVkQUlmb3ZONmRPTUROTVc1dS9SSVBXSmNLM1E2VFYu",
 *          "password" : "ofCb8C9mBX"
 *       }
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album": {
 *                   "id": 41,
 *                   "user_id": 1,
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg",
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）",
 *                           "checked": 1
 *                       },
 *                       {
 *                           "id": 2,
 *                           "title": "マンション（Appartment）",
 *                           "checked": 0
 *                       }
 *                   ],
 *                   "album_locations": [
 *                       {
 *                           "id": 30,
 *                           "title": "Bedroom",
 *                           "description": "Gara oto description",
 *                           "information": [
 *                               {
 *                                  "id": 12,
 *                                  "location_property_id": 1,
 *                                  "title": "Diện Tích",
 *                                  "type": 1,
 *                                  "display": 0,
 *                                  "highlight": 1,
 *                                  "value": "45m2"
 *                               }
 *                           ],
 *                           "location_image": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                           "comments": [
 *                               {
 *                                   "id": 17,
 *                                   "creator": {
 *                                       "shared_album_id": 3,
 *                                       "full_name": "Hoang Tung Lam",
 *                                       "email": "hoanglam995@gmail.com"
 *                                   },
 *                                   "creator_type": 2,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-14T11:34:35.000000Z",
 *                                   "update_at": "2020-08-14T11:34:35.000000Z"
 *                               },
 *                               {
 *                                   "id": 21,
 *                                   "creator": {
 *                                       "id": 1,
 *                                       "full_name": "Hoàng Tùng Lâm",
 *                                       "email": "hoanglam995@gmail.com",
 *                                       "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                   },
 *                                   "creator_type": 1,
 *                                   "content": "comment content",
 *                                   "create_at": "2020-08-17T04:35:11.000000Z",
 *                                   "update_at": "2020-08-17T04:35:11.000000Z"
 *                               }
 *                           ],
 *                           "number_comment": 2,
 *                           "created_at": "2020-09-09T06:26:57.000000Z",
 *                           "ts_created_at": 1599632817,
 *                           "medias": [
 *                               {
 *                                   "id": 1,
 *                                   "path": "1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "url": "http://localhost/storage/images/1-2020-09-01_02-56-53-865214.jpeg",
 *                                   "image_after_path": null,
 *                                   "image_after_url": null,
 *                                   "thumbnail_path": null,
 *                                   "thumbnail_url": null,
 *                                   "album_location_id": 30,
 *                                   "description": "description",
 *                                   "created_time": "09/09/2020",
 *                                   "information": [
 *                                       {
 *                                          "id": 19,
 *                                          "media_property_id": 1,
 *                                          "title": "Ngày Chụp Ảnh",
 *                                          "type": 1,
 *                                          "display": 1,
 *                                          "highlight": 1,
 *                                          "value": "09/09/2020"
 *                                       }
 *                                   ],
 *                                   "comments": [
 *                                       {
 *                                           "id": 17,
 *                                           "creator": {
 *                                               "shared_album_id": 3,
 *                                               "full_name": "Hoang Tung Lam",
 *                                               "email": "hoanglam995@gmail.com"
 *                                           },
 *                                           "creator_type": 2,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-14T11:34:35.000000Z",
 *                                           "update_at": "2020-08-14T11:34:35.000000Z"
 *                                       },
 *                                       {
 *                                           "id": 21,
 *                                           "creator": {
 *                                               "id": 1,
 *                                               "full_name": "Hoàng Tùng Lâm",
 *                                               "email": "hoanglam995@gmail.com",
 *                                               "avatar_url": "http://localhost/storage/users/1-2020-08-14_07-37-32-624990.jpeg"
 *                                           },
 *                                           "creator_type": 1,
 *                                           "content": "comment content",
 *                                           "create_at": "2020-08-17T04:35:11.000000Z",
 *                                           "update_at": "2020-08-17T04:35:11.000000Z"
 *                                       }
 *                                   ],
 *                                   "number_comment": 0,
 *                                   "type": 1,
 *                                   "size": 0.1
 *                               }
 *                           ]
 *                       }
 *                   ],
 *                   "album_informations": [
 *                       {
 *                           "id": 42,
 *                           "album_property_id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": "House ID"
 *                       },
 *                       {
 *                           "id": "",
 *                           "album_property_id": 2,
 *                           "title": "所有者（Owner）",
 *                           "type": 1,
 *                           "require": 0,
 *                           "display": 1,
 *                           "highlight": 0,
 *                           "value": ""
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

/**
 * @api {get} /web/share/albums Get List Shared Albums For Email
 * @apiVersion 0.1.0
 * @apiName GetListSharedAlbumsForEmail
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Number} page Current page
 * @apiParam {Number} limit item in page
 * @apiParam {String} search Search name or email
 * @apiParam {String} sort[id] sort by id
 * @apiParam {String} sort[album] sort by album id
 * @apiParam {String} sort[name] sort by full name
 * @apiParam {String} sort[email] sort by email
 * @apiParam {String} sort[status] sort by status
 * @apiParam {String} sort[date] sort by created date: ASC - Old, DESC - New
 *
 * @apiSuccess {Array} data_list List link shared album
 * @apiSuccess {Number} data_list.id id link shared album
 * @apiSuccess {String} data_list.full_name Name
 * @apiSuccess {String} data_list.email Email address
 * @apiSuccess {Number} data_list.album_id Album id
 * @apiSuccess {String} data_list.link Link view shared album
 * @apiSuccess {Boolean} data_list.status Status
 * @apiSuccess {String} data_list.created_at created at - TimeZone
 * @apiSuccess {String} data_list.ts_created_at Created at - TimeStamp
 * @apiSuccess {Number} total total
 * @apiSuccess {Number} current_page
 * @apiSuccess {Number} limit
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *       "data_list": [
 *           {
 *               "id": 12,
 *               "full_name": "hoang lam",
 *               "email": "hoanglam995@gmail.com",
 *               "album_data": {
 *                   "id": 249,
 *                   "image_path": "image_path.jpg",
 *                   "image_url": "http://localhost/storage/images/image_path.jpg"
 *               },
 *               "link": "http://web.localhost/albums/shared?token=JDJ5JDEwJDA3QkJXNU9jUk84ZjRlT1NpODZ3Si5DdjdKOUpJQnNnZG1yaUQ1TlI5VDA1eG9GaFNBS0Jp",
 *               "status": 1,
 *               "created_at": "2020-05-05T09:57:24.000000Z",
 *               "ts_created_at": 1588672644
 *           }
 *       ],
 *       "total": 1,
 *       "current_page": 1,
 *       "limit": 10
 *  }
 *
 */

/**
 * @api {patch} /web/share/albums/:sharedAlbumId Change Status Shared Album For Email
 * @apiVersion 0.1.0
 * @apiName ChangeStatusSharedAlbumForEmail
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object} shared_data shared album
 * @apiSuccess {Number} shared_data.id id shared album
 * @apiSuccess {String} shared_data.full_name Name
 * @apiSuccess {String} shared_data.email Email address
 * @apiSuccess {Number} shared_data.album_id Album id
 * @apiSuccess {String} shared_data.link Link view shared album
 * @apiSuccess {Boolean} shared_data.status Status
 * @apiSuccess {String} shared_data.created_at created at - TimeZone
 * @apiSuccess {String} shared_data.ts_created_at Created at - TimeStamp
 *
 * @apiSuccessExample {json} Success-Example:
 *  {
 *       "shared_data": {
 *           "id": 12,
 *           "full_name": "hoang lam",
 *           "email": "hoanglam995@gmail.com",
 *           "album_data": {
 *               "id": 249,
 *               "image_path": "image_path.jpg",
 *               "image_url": "http://localhost/storage/images/image_path.jpg"
 *           },
 *           "link": "http://web.localhost/albums/shared?token=JDJ5JDEwJDA3QkJXNU9jUk84ZjRlT1NpODZ3Si5DdjdKOUpJQnNnZG1yaUQ1TlI5VDA1eG9GaFNBS0Jp",
 *           "status": 1,
 *           "created_at": "2020-05-05T09:57:24.000000Z",
 *           "ts_created_at": 1588672644
 *       }
 *  }
 *
 */

/**
 * @api {get} /web/share/targets Get List Name, Email Shared Album
 * @apiVersion 0.1.0
 * @apiName GetListTargetSharedAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "data_list": [
 *               {
 *                   "full_name": "Hoàng Lâm",
 *                   "email": "hoanglam995@gmail.com"
 *               },
 *               {
 *                   "full_name": "Hoang LAm",
 *                   "email": "hoanglam@gmail.com"
 *               }
 *           ]
 *       }
 *
 */

/**
 * @api {get} /web/albums/:albumId/config Get Config Name Album
 * @apiVersion 0.1.0
 * @apiName GetConfigNameAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "data_config": [
 *               {
 *                   "id": 5,
 *                   "title": "Address"
 *               },
 *               {
 *                   "id": 1,
 *                   "title": "House ID"
 *               },
 *               {
 *                   "id": 3,
 *                   "title": "Phone"
 *               },
 *               {
 *                   "id": null,
 *                   "title": "Hoang"
 *               },
 *               {
 *                   "id": 2,
 *                   "title": "Owner"
 *               },
 *               {
 *                   "id": 4,
 *                   "title": "Email"
 *               }
 *           ],
 *           "data_properties": [
 *               {
 *                   "id": 1,
 *                   "title": "House ID"
 *               },
 *               {
 *                   "id": 2,
 *                   "title": "Owner"
 *               },
 *               {
 *                   "id": 3,
 *                   "title": "Phone"
 *               },
 *               {
 *                   "id": 4,
 *                   "title": "Email"
 *               },
 *               {
 *                   "id": 5,
 *                   "title": "Address"
 *               },
 *               {
 *                   "id": 6,
 *                   "title": "Price"
 *               }
 *           ]
 *       }
 *
 */

/**
 * @api {patch} /web/albums/:albumId/config Update Config Name Album
 * @apiVersion 0.1.0
 * @apiName UpdateConfigNameAlbum
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 * @apiParam {Object[]} [config] List object config
 * @apiParam {Object[]} [config.id] Id property. Null if custom name
 * @apiParam {Object[]} [config.title] Title custom name. Require if id property null
 *
 * @apiSuccessExample {json} Success-Example:
 *       {
 *           "data_config": [
 *               {
 *                   "id": 5,
 *                   "title": "Address"
 *               },
 *               {
 *                   "id": 1,
 *                   "title": "House ID"
 *               },
 *               {
 *                   "id": 3,
 *                   "title": "Phone"
 *               },
 *               {
 *                   "id": null,
 *                   "title": "Hoang"
 *               },
 *               {
 *                   "id": 2,
 *                   "title": "Owner"
 *               },
 *               {
 *                   "id": 4,
 *                   "title": "Email"
 *               }
 *           ],
 *           "data_properties": [
 *               {
 *                   "id": 1,
 *                   "title": "House ID"
 *               },
 *               {
 *                   "id": 2,
 *                   "title": "Owner"
 *               },
 *               {
 *                   "id": 3,
 *                   "title": "Phone"
 *               },
 *               {
 *                   "id": 4,
 *                   "title": "Email"
 *               },
 *               {
 *                   "id": 5,
 *                   "title": "Address"
 *               },
 *               {
 *                   "id": 6,
 *                   "title": "Price"
 *               }
 *           ]
 *       }
 *
 */

/**
 * @api {get} /web/albums/:albumId/medias/download Download album media by name config
 * @apiVersion 0.1.0
 * @apiName DownloadAlbumMediaByNameConfig
 * @apiGroup Albums For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} medias String media id. EX param: ?medias=1,2,3,4
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *              "url": "http://localhost/storage/downloads/1_1589264850.zip"
 *              "image_after_url": "http://localhost/storage..."
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

