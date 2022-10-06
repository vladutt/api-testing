import { unref, withCtx, createVNode, openBlock, createBlock, withDirectives, isRef, vModelText, Fragment, renderList, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderList, ssrInterpolate } from "vue/server-renderer";
import _ from "lodash";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout.507f0361.mjs";
import { Head } from "@inertiajs/inertia-vue3";
import "./ApplicationLogo.331c46e2.mjs";
const __default__ = {
  data() {
    return {
      searchTerm: "",
      mutableHosts: ""
    };
  },
  methods: {
    search: _.debounce(function() {
      if (this.searchTerm.length === 0) {
        this.mutableHosts = this.hosts;
      }
      axios.get(route("search-hosts"), {
        params: {
          "searchTerm": this.searchTerm
        }
      }).then((data) => {
        console.log(data);
        this.mutableHosts = data.data.data;
      });
    }, 1e3)
  },
  mounted() {
    this.mutableHosts = this.hosts;
  }
};
const _sfc_main = /* @__PURE__ */ Object.assign(__default__, {
  __name: "Hosts",
  __ssrInlineRender: true,
  props: {
    hosts: Object
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Hosts" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 leading-tight"${_scopeId}> Hosts </h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 leading-tight" }, " Hosts ")
            ];
          }
        }),
        default: withCtx((_2, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="pb-12"${_scopeId}><div class="max-w-7xl mx-auto"${_scopeId}><div class="relative overflow-x-auto shadow-md sm:rounded-lg"${_scopeId}><div class="p-4"${_scopeId}><label for="table-search" class="sr-only"${_scopeId}>Search</label><div class="relative mt-1"${_scopeId}><div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"${_scopeId}><svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"${_scopeId}><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"${_scopeId}></path></svg></div><input type="text"${ssrRenderAttr("value", _ctx.searchTerm)} id="table-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items"${_scopeId}></div></div><table class="w-full text-sm text-left text-gray-500 dark:text-gray-400"${_scopeId}><thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"${_scopeId}><tr${_scopeId}><th scope="col" class="p-4"${_scopeId}><div class="flex items-center"${_scopeId}><input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"${_scopeId}><label for="checkbox-all-search" class="sr-only"${_scopeId}>checkbox</label></div></th><th scope="col" class="px-6 py-3"${_scopeId}> Host </th><th scope="col" class="px-6 py-3"${_scopeId}> Description </th><th scope="col" class="px-6 py-3"${_scopeId}> Created At </th><th scope="col" class="px-6 py-3"${_scopeId}> Updated At </th><th scope="col" class="px-6 py-3"${_scopeId}><span class="sr-only"${_scopeId}>Edit</span></th></tr></thead><tbody${_scopeId}><!--[-->`);
            ssrRenderList(_ctx.mutableHosts, (host, index) => {
              _push2(`<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"${_scopeId}><td class="w-4 p-4"${_scopeId}><div class="flex items-center"${_scopeId}><input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"${_scopeId}><label for="checkbox-table-search-1" class="sr-only"${_scopeId}>checkbox</label></div></td><th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"${_scopeId}>${ssrInterpolate(host.host)}</th><td class="px-6 py-4"${_scopeId}>${ssrInterpolate(host.description)}</td><td class="px-6 py-4"${_scopeId}>${ssrInterpolate(host.created_at)}</td><td class="px-6 py-4"${_scopeId}>${ssrInterpolate(host.updated_at)}</td><td class="px-6 py-4 text-right"${_scopeId}><a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline"${_scopeId}>Edit</a></td></tr>`);
            });
            _push2(`<!--]--></tbody></table></div></div></div>`);
          } else {
            return [
              createVNode("div", { class: "pb-12" }, [
                createVNode("div", { class: "max-w-7xl mx-auto" }, [
                  createVNode("div", { class: "relative overflow-x-auto shadow-md sm:rounded-lg" }, [
                    createVNode("div", { class: "p-4" }, [
                      createVNode("label", {
                        for: "table-search",
                        class: "sr-only"
                      }, "Search"),
                      createVNode("div", { class: "relative mt-1" }, [
                        createVNode("div", { class: "absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none" }, [
                          (openBlock(), createBlock("svg", {
                            class: "w-5 h-5 text-gray-500 dark:text-gray-400",
                            fill: "currentColor",
                            viewBox: "0 0 20 20",
                            xmlns: "http://www.w3.org/2000/svg"
                          }, [
                            createVNode("path", {
                              "fill-rule": "evenodd",
                              d: "M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z",
                              "clip-rule": "evenodd"
                            })
                          ]))
                        ]),
                        withDirectives(createVNode("input", {
                          type: "text",
                          "onUpdate:modelValue": ($event) => isRef(searchTerm) ? searchTerm.value = $event : null,
                          onKeyup: _ctx.search,
                          id: "table-search",
                          class: "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-80 pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500",
                          placeholder: "Search for items"
                        }, null, 40, ["onUpdate:modelValue", "onKeyup"]), [
                          [vModelText, _ctx.searchTerm]
                        ])
                      ])
                    ]),
                    createVNode("table", { class: "w-full text-sm text-left text-gray-500 dark:text-gray-400" }, [
                      createVNode("thead", { class: "text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" }, [
                        createVNode("tr", null, [
                          createVNode("th", {
                            scope: "col",
                            class: "p-4"
                          }, [
                            createVNode("div", { class: "flex items-center" }, [
                              createVNode("input", {
                                id: "checkbox-all-search",
                                type: "checkbox",
                                class: "w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                              }),
                              createVNode("label", {
                                for: "checkbox-all-search",
                                class: "sr-only"
                              }, "checkbox")
                            ])
                          ]),
                          createVNode("th", {
                            scope: "col",
                            class: "px-6 py-3"
                          }, " Host "),
                          createVNode("th", {
                            scope: "col",
                            class: "px-6 py-3"
                          }, " Description "),
                          createVNode("th", {
                            scope: "col",
                            class: "px-6 py-3"
                          }, " Created At "),
                          createVNode("th", {
                            scope: "col",
                            class: "px-6 py-3"
                          }, " Updated At "),
                          createVNode("th", {
                            scope: "col",
                            class: "px-6 py-3"
                          }, [
                            createVNode("span", { class: "sr-only" }, "Edit")
                          ])
                        ])
                      ]),
                      createVNode("tbody", null, [
                        (openBlock(true), createBlock(Fragment, null, renderList(_ctx.mutableHosts, (host, index) => {
                          return openBlock(), createBlock("tr", {
                            key: index,
                            class: "bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                          }, [
                            createVNode("td", { class: "w-4 p-4" }, [
                              createVNode("div", { class: "flex items-center" }, [
                                createVNode("input", {
                                  id: "checkbox-table-search-1",
                                  type: "checkbox",
                                  class: "w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                }),
                                createVNode("label", {
                                  for: "checkbox-table-search-1",
                                  class: "sr-only"
                                }, "checkbox")
                              ])
                            ]),
                            createVNode("th", {
                              scope: "row",
                              class: "px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                            }, toDisplayString(host.host), 1),
                            createVNode("td", { class: "px-6 py-4" }, toDisplayString(host.description), 1),
                            createVNode("td", { class: "px-6 py-4" }, toDisplayString(host.created_at), 1),
                            createVNode("td", { class: "px-6 py-4" }, toDisplayString(host.updated_at), 1),
                            createVNode("td", { class: "px-6 py-4 text-right" }, [
                              createVNode("a", {
                                href: "#",
                                class: "font-medium text-blue-600 dark:text-blue-500 hover:underline"
                              }, "Edit")
                            ])
                          ]);
                        }), 128))
                      ])
                    ])
                  ])
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Hosts.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
