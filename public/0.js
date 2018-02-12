webpackJsonp([0],{

/***/ 202:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(209)
}
var normalizeComponent = __webpack_require__(74)
/* script */
var __vue_script__ = __webpack_require__(211)
/* template */
var __vue_template__ = __webpack_require__(212)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\upload.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-loader/node_modules/vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2092db98", Component.options)
  } else {
    hotAPI.reload("data-v-2092db98", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 209:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(210);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(75)("73411946", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/_css-loader@0.28.9@css-loader/index.js!../../../../node_modules/_vue-loader@13.7.1@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-2092db98\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/_vue-loader@13.7.1@vue-loader/lib/selector.js?type=styles&index=0!./upload.vue", function() {
     var newContent = require("!!../../../../node_modules/_css-loader@0.28.9@css-loader/index.js!../../../../node_modules/_vue-loader@13.7.1@vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-2092db98\",\"scoped\":false,\"hasInlineConfig\":true}!../../../../node_modules/_vue-loader@13.7.1@vue-loader/lib/selector.js?type=styles&index=0!./upload.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 210:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(46)(false);
// imports


// module
exports.push([module.i, "\n.userPhone{\n    width: 132px;\n    height: 132px;\n    margin: 100px auto 0;\n    padding: 0 !important;\n    float: inherit;\n    -webkit-box-sizing: content-box;\n            box-sizing: content-box;\n    border-radius: 50%;\n    overflow:hidden;\n}\n.userPhone img{\n    width: 100%;\n    height: 100%;\n}\n.userName{\n    text-align: center;\n    color:white;;\n    font-size: 18px;\n    line-height: 35px;\n    text-align: center;\n    padding: 0;\n    margin: 0;\n    width: 100%;\n}\n.codeBth{\n    float: inherit;\n    padding: 0;\n    margin: 0;\n    width: 100%;\n}\n.codeBth button{\n    margin: 0 auto 50px;\n    display: block;\n}\n.nextBtn{\n    float: inherit;\n    padding: 0;\n    margin: 0;\n    width: 100%;\n}\n.nextBtn button{\n    margin: 0 auto;\n    display: block;\n}\n", ""]);

// exports


/***/ }),

/***/ 211:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {
        console.log('Component mounted.');
    },
    data: function data() {
        return {
            avatar: Constant.avatar,
            nickname: Constant.nickname,
            shoukuanma: 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo_top_ca79a146.png',
            queryCount: 0,
            setout: ''
        };
    },

    methods: {
        choose: function choose() {
            console.log("has click the choose!");
            var that = this;
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function success(data) {
                    var localIds = data.localIds[0].toString(); // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    console.log(localIds);

                    wx.uploadImage({
                        localId: localIds, // 需要上传的图片的本地ID，由chooseImage接口获得
                        isShowProgressTips: 1, // 默认为1，显示进度提示
                        success: function success(res) {
                            var mediaId = res.serverId; // 返回图片的服务器端ID
                            // $(".myimg").attr("src", localIds);
                            that.shoukuanma = localIds;
                            console.log("获取到mediaId:" + mediaId);

                            that.queryCount = 0;
                            $.post("/shoukuanma?serverId=" + mediaId, {}, function () {
                                if (that.queryCount < 5) {
                                    that.setout = setInterval(function () {
                                        that.queryQrcode(mediaId);that.queryCount++;
                                    }, 1000);
                                }
                            });
                        },
                        fail: function fail(error) {
                            var localIds = '';
                            alert(Json.stringify(error));
                        }
                    });
                }
            });
        },
        queryQrcode: function queryQrcode(serverId) {
            var that = this;
            // this.queryCount++;
            console.log(this.queryCount);
            if (that.queryCount > 5) {
                clearInterval(that.setout);
            }
            $.ajax({
                url: "/qrcode?serverId=" + serverId,
                type: 'get'
            }).done(function (data) {
                if (data.image) {
                    that.shoukuanma = data.image;
                    clearInterval(that.setout);
                }
            });
        }
    }
});

/***/ }),

/***/ 212:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container" },
    [
      _c(
        "el-row",
        { attrs: { gutter: 20 } },
        [
          _c(
            "el-col",
            { staticClass: "userPhone", attrs: { span: 6, offset: 3 } },
            [_c("img", { attrs: { src: _vm.avatar } })]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-row",
        { attrs: { gutter: 20 } },
        [
          _c(
            "el-col",
            { staticClass: "userName", attrs: { span: 6, offset: 3 } },
            [
              _c("div", { staticClass: "grid-content bg-purple" }, [
                _vm._v(_vm._s(_vm.nickname))
              ])
            ]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-row",
        { attrs: { gutter: 20 } },
        [
          _c(
            "el-col",
            { staticClass: "codeBth", attrs: { span: 6, offset: 3 } },
            [
              _c(
                "div",
                { staticClass: "grid-content bg-purple" },
                [
                  _c(
                    "el-button",
                    { attrs: { type: "danger" }, on: { click: _vm.choose } },
                    [_vm._v("上传二维收款码")]
                  )
                ],
                1
              )
            ]
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-row",
        [
          _c(
            "el-col",
            { staticClass: "nextBtn", attrs: { span: 6, offset: 3 } },
            [
              _c(
                "el-button",
                { attrs: { type: "danger" } },
                [
                  _c("router-link", { attrs: { to: "select" } }, [
                    _vm._v("下一步")
                  ])
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-loader/node_modules/vue-hot-reload-api")      .rerender("data-v-2092db98", module.exports)
  }
}

/***/ })

});