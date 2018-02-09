webpackJsonp([0],{

/***/ 198:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(73)
/* script */
var __vue_script__ = __webpack_require__(201)
/* template */
var __vue_template__ = __webpack_require__(202)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
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
Component.options.__file = "resources/assets/js/components/upload.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-21e4de58", Component.options)
  } else {
    hotAPI.reload("data-v-21e4de58", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 201:
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


/* harmony default export */ __webpack_exports__["default"] = ({
    mounted: function mounted() {
        console.log('Component mounted.');
    },
    data: function data() {
        return {
            avatar: Constant.avatar,
            nickname: Constant.nickname
        };
    },

    methods: {
        choose: function choose() {
            console.log("has click the choose!");
            wx.chooseImage({
                count: 1, // 默认9
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function success(res) {
                    var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                    console.log(localIds);
                    wx.previewImage({
                        current: '', // 当前显示图片的http链接
                        urls: localIds
                    });
                }
            });
        }
    }
});

/***/ }),

/***/ 202:
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
          _c("el-col", { attrs: { span: 6, offset: 3 } }, [
            _c("img", { attrs: { src: _vm.avatar } })
          ])
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-row",
        { attrs: { gutter: 20 } },
        [
          _c("el-col", { attrs: { span: 6, offset: 3 } }, [
            _c("div", { staticClass: "grid-content bg-purple" }, [
              _vm._v(_vm._s(_vm.nickname))
            ])
          ])
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "el-row",
        { attrs: { gutter: 20 } },
        [
          _c("el-col", { attrs: { span: 6, offset: 3 } }, [
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
          ])
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
    require("vue-hot-reload-api")      .rerender("data-v-21e4de58", module.exports)
  }
}

/***/ })

});