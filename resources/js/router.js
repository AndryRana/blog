import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);
import newvue from "./components/pages/NewVueComponent";
import hooks from "./components/pages/basic/hooks"
import methods from "./components/pages/basic/methods"
import usecom from "./vuex/usecom";

//Admin project pages
import home from "./components/pages/home"
import tags from "./admin/pages/tags"
import category from "./admin/pages/category"
import adminusers from "./admin/pages/adminusers"

const routes = [

    // Projects routes...

    {
        path: "/testvuex",
        component: usecom,
    },
    {
        path: "/",
        component: home,
    },
    {
        path: "/tags",
        component: tags,
    },
    {
        path: "/category",
        component: category,
    },
    {
        path: "/adminusers",
        component: adminusers,
    },











    // basic tutorials routes
    {
        path: "/new-vue",
        component: newvue,
    },

    //  Vue hooks
    {
        path: "/hooks",
        component: hooks,
    },

    //  more basic
    {
        path: "/methods",
        component: methods,
    }
];

export default new Router({
    mode: "history",
    routes
});
