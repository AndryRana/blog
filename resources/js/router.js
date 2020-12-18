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
import login from "./admin/pages/login"
import role from "./admin/pages/role"
import assignRole from "./admin/pages/assignRole"
import createBlog from "./admin/pages/createBlog"

const routes = [

    // Projects routes...

    {
        path: "/testvuex",
        component: usecom,
    },
    {
        path: "/",
        component: home,
        name: 'home'
    },
    {
        path: "/tags",
        component: tags,
        name: 'tags'
    },
    {
        path: "/category",
        component: category,
        name: 'category'
    },
    {
        path: "/createBlog",
        component: createBlog,
        name: 'createBlog'
    },
    {
        path: "/adminusers",
        component: adminusers,
        name: 'adminusers'
    },
    {
        path: "/adminlogin",
        component: login,
        name: 'login'
    },
    {
        path: "/role",
        component: role,
        name: 'role'
    },
    {
        path: "/assignRole",
        component: assignRole,
        name: 'assignRole'
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
