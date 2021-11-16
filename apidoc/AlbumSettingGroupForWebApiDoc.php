<?php

/**
 * @api {post} /web/album-types/:albumTypeId/album-properties  Add Album Property
 * @apiVersion 0.1.0
 * @apiName AddAlbumProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title album property
 * @apiParam {String} [description] description album property
 * @apiParam {Number} type Type album property: 1 - Text, 2 - Date, 3 - Short date, 4 - Date time, 5 - Number, 6 - Email
 * @apiParam {Boolean} require Require album property
 * @apiParam {Boolean} display display property in item list album
 * @apiParam {Boolean} highlight Highlight property in item list album
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "House ID",
 *       "type": 1,
 *       "require": 1,
 *       "display": 1,
 *       "highlight": 1
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "album_property": {
 *                   "id": 32,
 *                   "title": "House ID",
 *                   "type": 1,
 *                   "require": 1,
 *                   "display": 1,
 *                   "highlight": 1,
 *                   "album_type_id":20
 *               }
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
 * @api {post} /web/album-types Add Album Type
 * @apiVersion 0.1.0
 * @apiName AddAlbumType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title album type
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "Apartment",
 *       "description": "Apartment",
 *       "album_information":[
 *         {
 *              "title":"アルバム名",
 *              "type":1,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":1,
 *              "display":0,
 *              "highlight":1,
 *         },
 *         {
 *              "title":"作成日",
 *              "type":2,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":0,
 *              "display":0,
 *              "highlight":0,
 *         }
 *       ],
 *       "location_information":[
 *          {
 *              "title":"説明",
 *              "type":1,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":1,
 *              "display":0,
 *              "highlight":1,
 *         },
 *         {
 *              "title":"作成日",
 *              "type":2,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":0,
 *              "display":0,
 *              "highlight":0,
 *         }
 *       ],
 *       "media_information":[
 *          {
 *              "title":"写真名",
 *              "type":1,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":1,
 *              "display":0,
 *              "highlight":1,
 *         },
 *         {
 *              "title":"作成日",
 *              "type":2,
 *              "description":"Khi tạo mặc định có title và type này",
 *              "require":0,
 *              "display":0,
 *              "highlight":0,
 *         }
 *       ],
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "album_type": {
 *                   "id": 10,
 *                   "title": "Apartment Copy",
 *                   "description ":"Apartment description",
 *                  "default":1,
 *                  "album_information":[
 *                      {
 *                          "title":"アルバム名",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                   ],
 *                  "location_information":[
 *                      {
 *                          "title":"説明",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                  ],
 *                  "media_information":[
 *                      {
 *                          "title":"写真名",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                  ],
 *               }
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
 * @api {post} /web/companies/:companyId/location-types Add Location Type
 * @apiVersion 0.1.0
 * @apiName AddLocationType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title location type
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "Bedroom"
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_type": {
 *                   "id": 13,
 *                   "title": "Bedroom"
 *               }
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
 * @api {get} /web/companies/:companyId/album-settings Get Album Setting
 * @apiVersion 0.1.0
 * @apiName GetAlbumSetting
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.album_settings
 *
 * @apiSuccess {Object[]} data.album_settings.album_properties
 * @apiSuccess {Number} data.album_settings.album_properties.id Album property ID
 * @apiSuccess {String} data.album_settings.album_properties.title Album property title
 * @apiSuccess {String} data.album_settings.album_properties.description Album property description
 * @apiSuccess {Number} data.album_settings.album_properties.type Album property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.album_properties.require Album property require
 * @apiSuccess {Boolean} data.album_settings.album_properties.display Display property in item list album
 * @apiSuccess {Boolean} data.album_settings.album_properties.highlight Highlight property in item list album
 *
 * @apiSuccess {Object[]} data.album_settings.album_types
 * @apiSuccess {Number} data.album_settings.album_types.id Album type ID
 * @apiSuccess {String} data.album_settings.album_types.title Album type title
 *
 * @apiSuccess {Object[]} data.album_settings.location_properties
 * @apiSuccess {Number} data.album_settings.location_properties.id Location property ID
 * @apiSuccess {String} data.album_settings.location_properties.title Location property title
 * @apiSuccess {Number} data.album_settings.location_properties.type Location property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.location_properties.display Display property in item list location
 * @apiSuccess {Boolean} data.album_settings.location_properties.highlight Highlight property in item list location
 *
 * @apiSuccess {Object[]} data.album_settings.location_types
 * @apiSuccess {Number} data.album_settings.location_types.id Location type ID
 * @apiSuccess {String} data.album_settings.location_types.title Location type title
 *
 * @apiSuccess {Object[]} data.album_settings.media_properties
 * @apiSuccess {Number} data.album_settings.media_properties.id Media property ID
 * @apiSuccess {String} data.album_settings.media_properties.title Media property title
 * @apiSuccess {Number} data.album_settings.media_properties.type Media property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.album_settings.media_properties.display Display property in item list media
 * @apiSuccess {Boolean} data.album_settings.media_properties.highlight Highlight property in item list media
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "album_settings": {
 *                   "album_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "物件No.（HouseID）",
 *                           "type": 1,
 *                           "require": 1,
 *                           "display": 1,
 *                           "highlight": 0
 *                       }
 *                   ],
 *                   "album_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "戸建（House）"
 *                       }
 *                   ],
 *                   "location_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "Diện Tích",
 *                           "type": 1,
 *                           "display": 0,
 *                           "highlight": 1
 *                       }
 *                   ],
 *                   "location_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "リビング　Living room"
 *                       }
 *                   ],
 *                   "media_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "Ngày Chụp Ảnh",
 *                           "type": 1,
 *                           "display": 1,
 *                           "highlight": 1
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
 * @api {get} /web/companies/:companyId/location-types Get Location Type
 * @apiVersion 0.1.0
 * @apiName GetLocationType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *             "location_types": [
 *                   {
 *                       "id": 1,
 *                       "title": "Location Type 1"
 *                   }
 *              ]
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
 * @api {put} /web/album-types/:albumTypeId/album-properties/:albumPropertyId Update Album Property
 * @apiVersion 0.1.0
 * @apiName UpdateAlbumProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} [description] Description album property
 * @apiParam {Boolean} require Require album property
 * @apiParam {Boolean} display display property in item list album
 * @apiParam {Boolean} highlight highlight property in item list album
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "description":"Đây là cái Tên Album",
 *       "require": 1,
 *       "display": 0,
 *       "highlight": 1
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "album_property": {
 *                   "title": "Tên Album",
 *                   "type":1,
 *                   "description":"Đây là cái Tên Album",
 *                   "require": 1,
 *                   "display": 0,
 *                   "highlight": 1
 *               }
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
 * @api {put} /web/album-types/:albumTypeId Update Album Type
 * @apiVersion 0.1.0
 * @apiName UpdateAlbumType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title album type
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "Apartment",
 *       "description": "Apartment description",
 *       "default" : 1
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "album_type": {
 *                   "id": 10,
 *                   "title": "Apartment",
 *                   "description": "Apartment description",
 *                   "default" : 1"
 *                   "album_information":[
 *                      {
 *                          "title":"アルバム名",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                   ],
 *                  "location_information":[
 *                      {
 *                          "title":"説明",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                  ],
 *                  "media_information":[
 *                      {
 *                          "title":"写真名",
 *                          "type":1,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":1,
 *                          "display":0,
 *                          "highlight":1,
 *                      },
 *                      {
 *                          "title":"作成日",
 *                          "type":2,
 *                          "description":"Khi tạo mặc định có title và type này",
 *                          "require":0,
 *                          "display":0,
 *                          "highlight":0,
 *                      }
 *                  ],
 *               }
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
 * @api {put} /web/companies/:companyId/location-types/:locationTypeId Update Location Type
 * @apiVersion 0.1.0
 * @apiName UpdateLocationType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title location type
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "Bedroom"
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_type": {
 *                   "id": 13,
 *                   "title": "Bedroom"
 *               }
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
 * @api {delete} /web/album-types/:albumTypeId/album-properties/:albumPropertyId Delete Album Property
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
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
 * @api {delete} /web/album-types/:albumTypeId Delete Album Type
 * @apiVersion 0.1.0
 * @apiName DeleteAlbumType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
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
 * @api {delete} /web/companies/:companyId/location-types/:locationTypeId Delete Location Type
 * @apiVersion 0.1.0
 * @apiName DeleteLocationType
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
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
 * @api {get} /web/album-types/:albumTypeId/album-information-config Get Config Album Type
 * @apiVersion 0.1.0
 * @apiName GetAlbumProperties
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Array} data.album_properties
 * @apiSuccess {Number} data.album_properties.id is id album information of Album Type
 * @apiSuccess {String} data.album_properties.title is name album information of Album Type (TEXT= 1;DATE= 2;SHORT_DATE= 3;DATE_TIME= 4;NUMBER= 5; EMAIL= 6;IMAGES= 7; VIDEOS= 8; PDFS= 9;)
 * @apiSuccess {String} data.album_properties.description is id album information of Album Type
 * @apiSuccess {Number} data.album_properties.type is type album information of Album Type
 * @apiSuccess {Number} data.album_properties.require is require (require:1 | NOT require:2)
 * @apiSuccess {Number} data.album_properties.display is display (display:1 | NOT display:2)
 * @apiSuccess {Number} data.album_properties.highlight is highlight (highlight:1 | NOT highlight:2)
 * @apiSuccess {Number} data.album_properties.album_type_id is of Album Type
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *                "album_information_config": {
 *                      "id": 20,
 *                      "title": "土地（Land)",
 *                      "description": null,
 *                      "default": 0,
 *                      "company_id": 5,
 *                      "album_information": [
 *                          {
 *                                  "id": 68,
                                    "title": "Việt Nam",
                                    "description": "Việt Nam vô địch 3",
                                    "type": 1,
                                    "require": 1,
                                    "display": 0,
                                    "highlight": 1,
                                    "album_type_id": 20
 *                          },
 *                          {
 *
                                    "id": 69,
                                    "title": "Việt Nam ABC",
                                    "description": "Việt Nam vô địch 3",
                                    "type": 1,
                                    "require": 1,
                                    "display": 0,
                                    "highlight": 1,
                                    "album_type_id": 20

 *                          }
 *                  ],
 *                  "location_information": [
                            {
                                "id": 23,
                                "title": "A",
                                "type": 1,
                                "description": "Mô tả",
                                "require": "0",
                                "display": 1,
                                "highlight": 1,
                                "album_type_id": 20
                            },
                            {
                                "id": 26,
                                "title": "Tiêu đề 1",
                                "type": 1,
                                "description": "0",
                                "require": "0",
                                "display": 1,
                                "highlight": 1,
                                "album_type_id": 20
                            }
                        ],
                        "media_information": [
 *                          "id": 26,
                            "title": "Tên ảnh ",
                            "type": 1,
                            "description": "0",
                            "require": "0",
                            "display": 1,
                            "highlight": 1,
                            "album_type_id": 20
 *                      ]
 *                  }
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
 * @api {post} /web/album-types/:albumTypeId/location-properties Add location Property
 * @apiVersion 0.1.0
 * @apiName AddLocationProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title location property
 * @apiParam {Number} type Type location property: 1 - Text, 2 - Date, 3 - Short date, 4 - Date time, 5 - Number, 6 - Email
 * @apiParam {Text} [description] description location property
 * @apiParam {Boolean} require require property
 * @apiParam {Boolean} display display property
 * @apiParam {Boolean} highlight Highlight property
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "type": 1,
 *       "display": 1,
 *       "highlight": 1,
 *       "require":0
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_property": {
 *                   "id": 32,
 *                   "title": "Chiều rộng",
 *                   "description":"Chiều rộng ngôi nhà",
 *                   "require": 0,
 *                   "type": 1,
 *                   "display": 1,
 *                   "highlight": 1,
 *                   "album_type_id": 20
 *               }
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
 * @api {put} /web/album-types/:albumTypeId/location-properties/:locationPropertyId Update Location Property
 * @apiVersion 0.1.0
 * @apiName UpdateLocationProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Boolean} display display property
 * @apiParam {Boolean} highlight Highlight property
 * @apiParam {Text} [description] description location property
 * @apiParam {Boolean} require require property
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "description":"Chiều cao ngôi nhà",
 *       "type": 2,
 *       "display": 0,
 *       "highlight": 0,
 *       "require":1
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_property": {
 *                  "title": "Chiều cao",
 *                  "description":"Chiều cao ngôi nhà",
 *                  "type": 2,
 *                  "display": 0,
 *                  "highlight": 0,
 *                  "require":1,
 *                  "album_type_id":20
 *               }
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
 * @api {delete} /web/album-types/:albumTypeId/location-properties/:locationPropertyId Delete Location Property
 * @apiVersion 0.1.0
 * @apiName DeleteLocationProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
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
 * @api {post} /web/album-types/:albumTypeId/media-properties Add Media Property
 * @apiVersion 0.1.0
 * @apiName AddMediaProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {String} title Title media property
 * @apiParam {Number} type Type media property: 1 - Text, 2 - Date, 3 - Short date, 4 - Date time, 5 - Number, 6 - Email
 * @apiParam {Boolean} display display property
 * @apiParam {Boolean} highlight Highlight property
 * @apiParam {Boolean} require Require property
 * @apiParam {Text} description Description media property

 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "title": "Ngày tạo",
 *       "type": 1,
 *       "display": 1,
 *       "highlight": 1,
 *       "require": 0,
 *       "description": "Text description"
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "media_property": {
 *                   "id": 32,
 *                   "title": "Ngày tạo",
 *                   "type": 1,
 *                   "display": 1,
 *                   "highlight": 1,
 *                   "require": 0,
 *                   "description": "Text description",
 *               }
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
 * @api {put} /web/album-types/:albumTypeId/media-properties/:mediaPropertyId Update Media Property
 * @apiVersion 0.1.0
 * @apiName UpdateMediaProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiParam {Boolean} display display property
 * @apiParam {Boolean} highlight highlight property
 * @apiParam {Boolean} require Require property
 * @apiParam {Text} description Description media property
 *
 * @apiParamExample {json} Request-Example:
 *     {
 *       "display": 1,
 *       "highlight": 0,
 *       "require": 1,
 *       "description": "Text description",
 *     }
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "media_property": {
 *                   "id": 32,
 *                   "title": "Ngày Tạo",
 *                   "type": 1,
 *                   "display": 1,
 *                   "highlight": 0,
 *                   "require": 1,
 *                   "description": "Text description",
 *               }
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
 * @api {delete} /web/album-types/:albumTypeId/media-properties/:mediaPropertyId Delete Media Property
 * @apiVersion 0.1.0
 * @apiName DeleteMediaProperty
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
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
 * @api {get} /web/companies/:companyId/album-types Get Album Type
 * @apiVersion 0.1.0
 * @apiName GetAlbumType
 * @apiGroup Album Setting For Web
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Array} data.album_types
 * @apiSuccess {Number} data.album_types.id is Id of Album_Type
 * @apiSuccess {String} data.album_types.title is NAME of Album_Type
 * @apiSuccess {String} data.album_types.description is description of Album_Type
 * @apiSuccess {Number} data.album_types.default (default :1 | NOT default :0)
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "album_types": [
 *                   {
 *                       "id": 1,
 *                       "title": "戸建（House）",
 *                       "description": "House",
 *                       "default": 1
 *                   },
 *                   {
 *                       "id": 2,
 *                       "title": "マンション（Appartment）",
 *                       "description": "Appartment",
 *                       "default": 0
 *                   }
 *               ]
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
 * @api {get} /web/companies/:companyId/location-properties Get Location Properties
 * @apiVersion 0.1.0
 * @apiName GetLocationProperties
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "location_properties": [
 *                   {
 *                       "id": 1,
 *                       "title": "Diện Tích",
 *                       "type": 1,
 *                       "display": 0,
 *                       "highlight": 1
 *                   }
 *               ]
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
 * @api {get} /web/companies/:companyId/media-properties Get media Properties
 * @apiVersion 0.1.0
 * @apiName GetMediaProperties
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccessExample {json} Success-Response:
 *     {
 *         "code": 200,
 *         "data": {
 *               "media_properties": [
 *                   {
 *                       "id": 1,
 *                       "title": "Ngày Chụp Ảnh",
 *                       "type": 1,
 *                       "display": 1,
 *                       "highlight": 1
 *                   }
 *               ]
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
 * @api {get} /web/companies/:companyId/location-settings Get Location Setting
 * @apiVersion 0.1.0
 * @apiName GetLocationSetting
 * @apiGroup Album Setting For Web
 *
 * @apiUse HeaderToken
 *
 * @apiSuccess {Object} data
 * @apiSuccess {Object} data.location_settings
 *
 * @apiSuccess {Object[]} data.location_settings.location_properties
 * @apiSuccess {Number} data.location_settings.location_properties.id Location property ID
 * @apiSuccess {String} data.location_settings.location_properties.title Location property title
 * @apiSuccess {Number} data.location_settings.location_properties.type Location property type: 1 - Text, 2 - Date...
 * @apiSuccess {Boolean} data.location_settings.location_properties.display Display property in item list location
 * @apiSuccess {Boolean} data.location_settings.location_properties.highlight Highlight property in item list location
 *
 * @apiSuccess {Object[]} data.album_settings.location_types
 * @apiSuccess {Number} data.album_settings.location_types.id Location type ID
 * @apiSuccess {String} data.album_settings.location_types.title Location type title
 *
 * @apiSuccessExample {json} Success-Response:
 *       {
 *           "code": 200,
 *           "data": {
 *               "location_settings": {
 *                   "location_properties": [
 *                       {
 *                           "id": 1,
 *                           "title": "Diện Tích",
 *                           "type": 1,
 *                           "display": 0,
 *                           "highlight": 1
 *                       }
 *                   ],
 *                   "location_types": [
 *                       {
 *                           "id": 1,
 *                           "title": "リビング　Living room"
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
