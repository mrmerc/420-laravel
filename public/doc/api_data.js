define({ "api": [
  {
    "type": "get",
    "url": "/article/:article_id",
    "title": "Request article by ID.",
    "name": "GetArticleById",
    "group": "Article",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "article_id",
            "description": "<p>Article's unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "article",
            "description": "<p>Article.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "article.id",
            "description": "<p>Article's unique ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "article.title",
            "description": "<p>Title.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "article.body",
            "description": "<p>Body.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "article.type_id",
            "description": "<p>Type.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "ArticleNotFound",
            "description": "<p>The <code>id</code> of the Article was not found.</p>"
          }
        ],
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/ArticleController.php",
    "groupTitle": "Article"
  },
  {
    "type": "post",
    "url": "/article",
    "title": "Submit article for further moderation.",
    "name": "SubmitArticle",
    "group": "Article",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "size": "24..9999",
            "optional": false,
            "field": "body",
            "description": "<p>Article's body.</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "type_id",
            "description": "<p>Article's type.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Success message.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "article_id",
            "description": "<p>ID of created article.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/ArticleController.php",
    "groupTitle": "Article"
  },
  {
    "type": "get",
    "url": "/auth/:provider/url",
    "title": "Get provider's authentication URL.",
    "name": "GetProviderURL",
    "group": "Authentication",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "provider",
            "description": "<p>Provider name</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<p>Authentication URL.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "SocialiteProviderError",
            "description": "<p>Failed to get authentication URL.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Authentication"
  },
  {
    "type": "post",
    "url": "/auth/:provider/callback",
    "title": "Login user with data from provider.",
    "name": "LoginUser",
    "group": "Authentication",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "provider",
            "description": "<p>Provider name</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "token",
            "description": "<p>Token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token.access_token",
            "description": "<p>Token for API authorization.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token.token_type",
            "description": "<p>Token type.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "token.expires_in",
            "description": "<p>Token TTL (time-to-live) in seconds.</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "user",
            "description": "<p>The authenticated User.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "user.id",
            "description": "<p>User's ID.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.nickname",
            "description": "<p>User's username.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.email",
            "description": "<p>User's email.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.social_name",
            "description": "<p>User's social name.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "user.social_avatar",
            "description": "<p>User's social avatar.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "SocialiteProviderError",
            "description": "<p>Failed to get authentication URL.</p>"
          },
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Authentication"
  },
  {
    "type": "post",
    "url": "/auth/token/refresh",
    "title": "Refresh a token.",
    "name": "RefreshToken",
    "group": "Authentication",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "token",
            "description": "<p>Token.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token.access_token",
            "description": "<p>Token for API authorization.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token.token_type",
            "description": "<p>Token type.</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "token.expires_in",
            "description": "<p>Token TTL (time-to-live) in seconds.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 5xx": [
          {
            "group": "Error 5xx",
            "optional": false,
            "field": "ServerError",
            "description": "<p>Unhandled server error.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/AuthController.php",
    "groupTitle": "Authentication"
  },
  {
    "type": "get",
    "url": "/chat/message/history/:room_id",
    "title": "Get a room's paginated message history.",
    "name": "GetMessageHistory",
    "group": "Chat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "size": "1..",
            "optional": false,
            "field": "room_id",
            "description": "<p>Room to get the history from.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "total",
            "description": "<p>Message counter</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "per_page",
            "description": "<p>Message per page</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "current_page",
            "description": "<p>Current page Int</p>"
          },
          {
            "group": "Success 200",
            "type": "Int/Null",
            "optional": false,
            "field": "last_page",
            "description": "<p>Last page Int</p>"
          },
          {
            "group": "Success 200",
            "type": "String/Null",
            "optional": false,
            "field": "first_page_url",
            "description": "<p>First page url</p>"
          },
          {
            "group": "Success 200",
            "type": "String/Null",
            "optional": false,
            "field": "last_page_url",
            "description": "<p>Last page url</p>"
          },
          {
            "group": "Success 200",
            "type": "String/Null",
            "optional": false,
            "field": "next_page_url",
            "description": "<p>Next page url</p>"
          },
          {
            "group": "Success 200",
            "type": "String/Null",
            "optional": false,
            "field": "prev_page_url",
            "description": "<p>Previous page url</p>"
          },
          {
            "group": "Success 200",
            "type": "String/Null",
            "optional": false,
            "field": "path",
            "description": "<p>Absolute path</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "from",
            "description": "<p>Number of message to start with</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "to",
            "description": "<p>Number of message to end with</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "data",
            "description": "<p>Array of messages</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/ChatController.php",
    "groupTitle": "Chat"
  },
  {
    "type": "post",
    "url": "/chat/message",
    "title": "Send a message.",
    "name": "SendMessage",
    "group": "Chat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "provider",
            "description": "<p>Provider name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "size": "1..1024",
            "optional": false,
            "field": "body",
            "description": "<p>Message body.</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "size": "1..6",
            "optional": true,
            "field": "attachments",
            "description": "<p>Message attachments.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "attachments.type",
            "description": "<p>Message attachment type.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "attachments.source",
            "description": "<p>Message attachment data.</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "timestamp",
            "description": "<p>Message UTC timestamp (in milliseconds).</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "size": "1..",
            "optional": false,
            "field": "user_id",
            "description": "<p>User who sent the message.</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "size": "1..",
            "optional": false,
            "field": "room_id",
            "description": "<p>Room the message has been sent from.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>Success message.</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/ChatController.php",
    "groupTitle": "Chat"
  },
  {
    "type": "get",
    "url": "/high/people",
    "title": "Get high people counter.",
    "name": "GetHighPeople",
    "group": "HighPeople",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "count",
            "description": "<p>High people counter</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/HighPeopleController.php",
    "groupTitle": "HighPeople"
  },
  {
    "type": "put",
    "url": "/high/people",
    "title": "Increment high people counter.",
    "name": "IncrementHighPeople",
    "group": "HighPeople",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "count",
            "description": "<p>High people counter</p>"
          }
        ]
      }
    },
    "error": {
      "fields": {
        "Error 500": [
          {
            "group": "Error 500",
            "optional": false,
            "field": "DatabaseError",
            "description": ""
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/Http/Controllers/HighPeopleController.php",
    "groupTitle": "HighPeople"
  }
] });
