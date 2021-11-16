<?php
/**
 * @api {get} /admin/list-short-code Get Short Code
 * @apiVersion 0.1.0
 * @apiName List Short Code
 * @apiGroup Admin
 *
 * @apiHeader {String} token_access : token of Admin
 *
 *
 * @apiSuccessExample {json} Success-Example:
 *   {
 *       "code": 200,
 *       "data": {
 *              "company":{
                        "company_name": "Công ty TNHH SC Soft Việt Nam",
                        "company_code": null,
                        "address": "187 Nguyễn Tuân, Thanh Xuân, Hà Nội",
                        "representative": null,
                        "tax_code": null,
                        "logo_url": null,
                        "phone": "0965484497"
                    },
                "contract": {
                    "represent_company_hire": "represent_company_hire",
                    "phone_company_hire": "phone_company_hire",
                    "name_company_rental": "name_company_rental",
                    "address_company_rental": "address_company_rental",
                    "represent_company_rental": "represent_company_rental",
                    "phone_number_rental": "phone_number_rental",
                    "tax": "tax",
                    "total_price": "total_price"
                }
 *           }
 *   }
 */
